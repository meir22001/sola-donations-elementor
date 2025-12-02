<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Sola_Form {

	private $api;

	public function __construct( $api ) {
		$this->api = $api;
		add_shortcode( 'sola_donation_form', array( $this, 'render_form' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'rest_api_init', array( $this, 'register_rest_route' ) );
	}

	public function enqueue_assets() {
		wp_register_style( 'sola-donations-css', SOLA_DONATIONS_URL . 'assets/css/sola-donations.css', array(), SOLA_DONATIONS_VERSION );
		wp_register_script( 'sola-donations-js', SOLA_DONATIONS_URL . 'assets/js/sola-donations.js', array(), SOLA_DONATIONS_VERSION, true );

		// Localize script for AJAX
		wp_localize_script( 'sola-donations-js', 'solaData', array(
			'apiUrl' => rest_url( 'sola/v1/donate' ),
			'nonce'  => wp_create_nonce( 'wp_rest' ),
			'redirectUrl' => get_option( 'sola_donations_settings' )['redirect_url'] ?? home_url()
		) );
	}

	public function render_form( $atts ) {
		wp_enqueue_style( 'sola-donations-css' );
		wp_enqueue_script( 'sola-donations-js' );

		ob_start();
		include SOLA_DONATIONS_PATH . 'templates/form-template.php';
		return ob_get_clean();
	}

	public function register_rest_route() {
		register_rest_route( 'sola/v1', '/donate', array(
			'methods' => 'POST',
			'callback' => array( $this, 'handle_donation' ),
			'permission_callback' => '__return_true'
		) );
	}

	public function handle_donation( $request ) {
		$params = $request->get_json_params();
		
		// Basic validation
		if ( empty( $params['amount'] ) || empty( $params['ccNumber'] ) ) {
			return new WP_Error( 'missing_params', 'Missing required fields', array( 'status' => 400 ) );
		}

		$result = $this->api->process_donation( $params );

		if ( $result['success'] ) {
			return rest_ensure_response( $result );
		} else {
			return new WP_Error( 'processing_failed', $result['message'], array( 'status' => 400 ) );
		}
	}
}
