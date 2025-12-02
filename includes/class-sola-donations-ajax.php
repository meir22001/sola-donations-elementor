<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Sola_Donations_Ajax {

	public function __construct() {
		add_action( 'wp_ajax_sola_process_donation', [ $this, 'process_donation' ] );
		add_action( 'wp_ajax_nopriv_sola_process_donation', [ $this, 'process_donation' ] );
	}

	public function process_donation() {
		check_ajax_referer( 'sola_donation_nonce', 'nonce' );

		$xToken = isset( $_POST['xToken'] ) ? sanitize_text_field( $_POST['xToken'] ) : '';
		$amount = isset( $_POST['amount'] ) ? floatval( $_POST['amount'] ) : 0;
		$is_recurring = isset( $_POST['is_recurring'] ) && $_POST['is_recurring'] === 'true';

		if ( empty( $xToken ) || $amount <= 0 ) {
			wp_send_json_error( [ 'message' => __( 'Invalid donation data.', 'sola-donations' ) ] );
		}

		$settings = get_option( 'sola_donations_settings' );
		$xKey = isset( $settings['xkey'] ) ? $settings['xkey'] : '';

		if ( empty( $xKey ) ) {
			wp_send_json_error( [ 'message' => __( 'Configuration error: Missing API Key.', 'sola-donations' ) ] );
		}

		// Prepare API Request
		$api_url = 'https://api.cardknox.com/gateway/json';
		
		$body = [
			'xKey' => $xKey,
			'xVersion' => '4.5.9',
			'xSoftwareName' => 'Sola Donations for Elementor',
			'xSoftwareVersion' => Sola_Donations_Elementor::VERSION,
			'xToken' => $xToken,
			'xAmount' => number_format( $amount, 2, '.', '' ),
		];

		if ( $is_recurring ) {
			$body['xCommand'] = 'schedule:create';
			$body['xScheduleStart'] = date( 'Y-m-d', strtotime( '+1 month' ) ); // Start next month? Or today? Usually today for first payment, but schedule:create might be future.
			// Strategy: Run cc:sale NOW for first payment, then schedule:create for future? 
			// Or just schedule:create with xStartDate = today?
			// Sola/Cardknox schedule:create usually starts on xStartDate.
			// Let's assume simple monthly starting today.
			$body['xStartDate'] = date( 'Y-m-d' );
			$body['xInterval'] = 'Month';
			$body['xPeriod'] = 1;
		} else {
			$body['xCommand'] = 'cc:sale';
		}

		// Send Request
		$response = wp_remote_post( $api_url, [
			'body' => json_encode( $body ),
			'headers' => [ 'Content-Type' => 'application/json' ],
			'timeout' => 45,
		] );

		if ( is_wp_error( $response ) ) {
			wp_send_json_error( [ 'message' => $response->get_error_message() ] );
		}

		$response_body = wp_remote_retrieve_body( $response );
		$data = json_decode( $response_body, true );

		if ( isset( $data['xResult'] ) && $data['xResult'] == 'A' ) {
			// Approved
			wp_send_json_success( [ 
				'message' => __( 'Thank you for your donation!', 'sola-donations' ),
				'refNum' => $data['xRefNum'],
				'authCode' => $data['xAuthCode']
			] );
		} else {
			// Declined or Error
			$error_msg = isset( $data['xError'] ) ? $data['xError'] : __( 'Transaction declined.', 'sola-donations' );
			wp_send_json_error( [ 'message' => $error_msg ] );
		}
	}

}
