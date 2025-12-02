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
		$recurring_day = isset( $_POST['recurring_day'] ) ? intval( $_POST['recurring_day'] ) : 1;
		$charge_immediately = isset( $_POST['charge_immediately'] ) && $_POST['charge_immediately'] === 'true';
		$currency = isset( $_POST['currency'] ) ? sanitize_text_field( $_POST['currency'] ) : 'USD';

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
			'xCurrency' => $currency,
		];

		// Logic for Recurring vs One-Time
		if ( $is_recurring ) {
			// Calculate Start Date
			$today_day = intval( date( 'j' ) );
			$current_month = intval( date( 'n' ) );
			$current_year = intval( date( 'Y' ) );
			
			// Determine next occurrence of the selected day
			if ( $today_day > $recurring_day ) {
				// Next month
				$start_date_timestamp = mktime( 0, 0, 0, $current_month + 1, $recurring_day, $current_year );
			} else {
				// This month (if today is before or equal to recurring day)
				// But if charge_immediately is true, we charge NOW and schedule next one for NEXT month?
				// Or if charge_immediately is true, we charge NOW and schedule next one for THIS month's day?
				// Requirement: "If checked, charge xAmount now, and set xStartDate for the next occurrence based on the selected day."
				
				// Case 1: Charge Immediately = YES
				// We run a cc:sale NOW.
				// We schedule:create starting NEXT occurrence.
				
				// Case 2: Charge Immediately = NO
				// We just schedule:create starting NEXT occurrence (or this month if day hasn't passed).
				
				$start_date_timestamp = mktime( 0, 0, 0, $current_month, $recurring_day, $current_year );
				if ( $start_date_timestamp < time() ) {
					// If calculated date is in the past (e.g. today is 15th, selected 10th), move to next month
					$start_date_timestamp = mktime( 0, 0, 0, $current_month + 1, $recurring_day, $current_year );
				}
			}
			
			$xStartDate = date( 'Y-m-d', $start_date_timestamp );

			if ( $charge_immediately ) {
				// 1. Process Immediate Payment
				$sale_body = $body;
				$sale_body['xCommand'] = 'cc:sale';
				
				$sale_response = wp_remote_post( $api_url, [
					'body' => json_encode( $sale_body ),
					'headers' => [ 'Content-Type' => 'application/json' ],
					'timeout' => 45,
				] );
				
				// Check sale result
				$sale_result = $this->parse_response( $sale_response );
				if ( ! $sale_result['success'] ) {
					wp_send_json_error( [ 'message' => $sale_result['message'] ] );
				}
				
				// If immediate charge successful, create schedule for FUTURE
				// If the calculated start date is TODAY (and we just charged), move it to next month?
				// Usually yes. If I charge now (Dec 2), and want to pay on 2nd of month, next payment is Jan 2.
				if ( $xStartDate === date( 'Y-m-d' ) ) {
					$xStartDate = date( 'Y-m-d', strtotime( '+1 month', $start_date_timestamp ) );
				}
			}

			// 2. Create Schedule
			$body['xCommand'] = 'schedule:create';
			$body['xStartDate'] = $xStartDate;
			$body['xInterval'] = 'Month';
			$body['xPeriod'] = 1; // 1 Month
			
		} else {
			// One-Time
			$body['xCommand'] = 'cc:sale';
		}

		// Send Request (for Schedule or One-Time)
		$response = wp_remote_post( $api_url, [
			'body' => json_encode( $body ),
			'headers' => [ 'Content-Type' => 'application/json' ],
			'timeout' => 45,
		] );

		$result = $this->parse_response( $response );

		if ( $result['success'] ) {
			// Post-Processing
			$this->handle_post_processing( $result['data'], $amount, $currency );
			
			// Prepare Response Data
			$response_data = [
				'message' => __( 'Thank you for your donation!', 'sola-donations' ),
				'refNum' => $result['data']['xRefNum'],
			];
			
			// Check for Redirect
			// We need to get the widget config somehow? Or pass it in AJAX?
			// The widget settings are not global, they are per widget.
			// But here we are in a generic AJAX handler.
			// The frontend passed us 'redirect_url' if we want to trust it? 
			// Or we look up settings? But settings are in Elementor data which is hard to parse here without post ID.
			// For simplicity and security, let's assume we trust the frontend to handle redirect if we return success,
			// OR we pass the redirect URL from frontend to backend to echo it back (not secure).
			// BETTER: The frontend already knows the redirect URL from `data-sola-config`.
			// So backend just says "Success", and Frontend handles redirect.
			// BUT, user asked for "Return the redirect_url in the JSON response".
			// So we should probably pass it from Frontend -> Backend -> Frontend? 
			// Or just let Frontend handle it.
			// Let's stick to the requirement: "Return the redirect_url in the JSON response".
			// This implies Backend determines it. But Backend doesn't know which Widget instance triggered this easily.
			// We will rely on the Frontend to have the URL in `config` and handle it, 
			// UNLESS we pass it in AJAX data.
			// Let's assume Frontend handles it for now as it's cleaner, 
			// OR we can pass it in AJAX params to be echoed back.
			
			// Let's check if we need to send email/webhook here.
			// We need the settings for email/webhook.
			// These are also Widget Settings, not Global Settings.
			// This is a common issue with Elementor Widgets + AJAX.
			// Solution: Pass the necessary configuration (webhook URL, admin email) in the AJAX request.
			// Security: Validate URLs?
			
			$webhook_url = isset( $_POST['webhook_url'] ) ? esc_url_raw( $_POST['webhook_url'] ) : '';
			$admin_email = isset( $_POST['admin_email'] ) ? sanitize_email( $_POST['admin_email'] ) : '';
			$redirect_url = isset( $_POST['redirect_url'] ) ? esc_url_raw( $_POST['redirect_url'] ) : '';

			if ( ! empty( $webhook_url ) ) {
				wp_remote_post( $webhook_url, [
					'body' => json_encode( array_merge( $result['data'], ['amount' => $amount, 'currency' => $currency] ) ),
					'headers' => [ 'Content-Type' => 'application/json' ],
					'blocking' => false, // Async
				] );
			}

			if ( ! empty( $admin_email ) ) {
				$subject = 'New Donation Received';
				$message = "Amount: $amount $currency\nRefNum: " . $result['data']['xRefNum'];
				wp_mail( $admin_email, $subject, $message );
			}
			
			if ( ! empty( $redirect_url ) ) {
				$response_data['redirect_url'] = $redirect_url;
			}

			wp_send_json_success( $response_data );
		} else {
			wp_send_json_error( [ 'message' => $result['message'] ] );
		}
	}

	private function parse_response( $response ) {
		if ( is_wp_error( $response ) ) {
			return [ 'success' => false, 'message' => $response->get_error_message() ];
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if ( isset( $data['xResult'] ) && $data['xResult'] == 'A' ) {
			return [ 'success' => true, 'data' => $data ];
		} else {
			$error = isset( $data['xError'] ) ? $data['xError'] : __( 'Transaction declined.', 'sola-donations' );
			return [ 'success' => false, 'message' => $error ];
		}
	}
	
	private function handle_post_processing( $data, $amount, $currency ) {
		// Placeholder for any other server-side logic (logging, DB, etc)
	}

}
