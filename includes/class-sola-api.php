<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Sola_API {

	private $api_key;
	private $is_sandbox;

	public function __construct() {
		$settings = get_option( 'sola_donations_settings' );
		$this->is_sandbox = isset( $settings['mode'] ) && $settings['mode'] === 'sandbox';
		$this->api_key = $this->is_sandbox ? 
			( isset( $settings['sandbox_key'] ) ? $settings['sandbox_key'] : '' ) : 
			( isset( $settings['live_key'] ) ? $settings['live_key'] : '' );
	}

	public function process_donation( $data ) {
		if ( empty( $this->api_key ) ) {
			return array( 'success' => false, 'message' => 'API Key is missing.' );
		}

		$frequency = isset( $data['frequency'] ) ? $data['frequency'] : 'onetime';

		if ( $frequency === 'monthly' ) {
			return $this->process_recurring( $data );
		} else {
			return $this->process_one_time( $data );
		}
	}

	private function process_one_time( $data ) {
		$endpoint = 'https://x1.cardknox.com/gatewayjson';

		$payload = array(
			'xKey' => $this->api_key,
			'xCommand' => 'cc:sale',
			'xVersion' => '4.5.9',
			'xSoftwareName' => 'SolaDonationsWP',
			'xSoftwareVersion' => SOLA_DONATIONS_VERSION,
			'xAmount' => $data['amount'],
			'xCardNum' => $data['ccNumber'],
			'xExp' => $data['ccExpiry'], // MM/YY or MMYY
			'xCVV' => $data['ccCvv'],
			'xBillFirstName' => $data['firstName'],
			'xBillLastName' => $data['lastName'],
			'xBillStreet' => $data['address'],
			'xEmail' => $data['email'],
			'xBillPhone' => $data['phone'],
			'xCurrency' => $data['currency'],
			'xAllowDuplicate' => 'true' // Allow testing duplicates
		);

		$response = wp_remote_post( $endpoint, array(
			'body' => json_encode( $payload ),
			'headers' => array( 'Content-Type' => 'application/json' ),
			'timeout' => 45
		) );

		if ( is_wp_error( $response ) ) {
			return array( 'success' => false, 'message' => $response->get_error_message() );
		}

		$body = wp_remote_retrieve_body( $response );
		$result = json_decode( $body, true );

		if ( isset( $result['xResult'] ) && $result['xResult'] === 'A' ) {
			return array( 'success' => true, 'message' => 'Transaction Approved', 'ref' => $result['xRefNum'] );
		} else {
			$error = isset( $result['xError'] ) ? $result['xError'] : 'Transaction Declined';
			return array( 'success' => false, 'message' => $error );
		}
	}

	private function process_recurring( $data ) {
		$charge_immediately = isset( $data['chargeImmediately'] ) && $data['chargeImmediately'];
		$charge_day = isset( $data['chargeDay'] ) ? intval( $data['chargeDay'] ) : date( 'j' );
		
		// Calculate Start Date
		$today_day = intval( date( 'j' ) );
		$start_date = '';

		if ( $charge_immediately ) {
			// Process immediate payment first
			$immediate_result = $this->process_one_time( $data );
			if ( ! $immediate_result['success'] ) {
				return $immediate_result; // Return error if immediate charge fails
			}
			
			// Schedule starts next month on the selected day
			// If today is Jan 2, and chargeDay is 15. Next month Feb 15.
			// If today is Jan 20, and chargeDay is 15. Next month Feb 15.
			// So always next month.
			$start_date = date( 'Y-m-d', strtotime( "next month" ) );
			// Adjust day
			$year_month = date( 'Y-m', strtotime( "next month" ) );
			$start_date = $year_month . '-' . sprintf( '%02d', $charge_day );
			
		} else {
			// No immediate charge.
			// If today < chargeDay, start this month.
			// If today >= chargeDay, start next month.
			if ( $today_day < $charge_day ) {
				$start_date = date( 'Y-m-' ) . sprintf( '%02d', $charge_day );
			} else {
				$year_month = date( 'Y-m', strtotime( "next month" ) );
				$start_date = $year_month . '-' . sprintf( '%02d', $charge_day );
			}
		}

		// Create Schedule
		$endpoint = 'https://api.cardknox.com/v2/schedule';
		$exp = str_replace( array( '/', ' ' ), '', $data['ccExpiry'] );

		$payload = array(
			'SoftwareName' => 'SolaDonationsWP',
			'SoftwareVersion' => SOLA_DONATIONS_VERSION,
			'IntervalType' => 'month',
			'IntervalCount' => 1,
			'Amount' => (float) $data['amount'],
			'Currency' => $data['currency'],
			'StartDate' => $start_date,
			'NewCustomer' => array(
				'FirstName' => $data['firstName'],
				'LastName' => $data['lastName'],
				'Email' => $data['email'],
				'BillStreet' => $data['address'],
				'BillPhone' => $data['phone']
			),
			'NewPaymentMethod' => array(
				'CardNumber' => $data['ccNumber'],
				'ExpirationDate' => $exp,
				'CVV' => $data['ccCvv'],
				'NameOnCard' => $data['firstName'] . ' ' . $data['lastName']
			)
		);

		$response = wp_remote_post( $endpoint, array(
			'body' => json_encode( $payload ),
			'headers' => array(
				'Content-Type' => 'application/json',
				'Authorization' => $this->api_key,
				'X-Recurring-Api-Version' => '2.1'
			),
			'timeout' => 45
		) );

		if ( is_wp_error( $response ) ) {
			return array( 'success' => false, 'message' => $response->get_error_message() );
		}

		$body = wp_remote_retrieve_body( $response );
		$result = json_decode( $body, true );

		if ( isset( $result['Result'] ) && $result['Result'] === 'S' ) {
			$msg = 'Subscription Created';
			if ( $charge_immediately ) {
				$msg .= ' (First payment processed successfully)';
			}
			return array( 'success' => true, 'message' => $msg, 'ref' => $result['ScheduleId'] );
		} else {
			$error = isset( $result['Error'] ) ? $result['Error'] : 'Subscription Failed';
			return array( 'success' => false, 'message' => $error );
		}
	}
}
