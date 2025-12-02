<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Sola_Donations_Main {

	private static $_instance = null;
	public $settings;

	public static function get_instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct() {
		$this->includes();
		$this->init_components();
	}

	private function includes() {
		require_once( __DIR__ . '/admin/class-sola-donations-settings.php' );
		require_once( __DIR__ . '/class-sola-donations-ajax.php' );
	}

	private function init_components() {
		$this->settings = new Sola_Donations_Settings();
		new Sola_Donations_Ajax();
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'register_frontend_assets' ] );
	}

	public function register_frontend_assets() {
		// Sola iFields SDK
		wp_register_script( 'sola-ifields-sdk', 'https://cdn.cardknox.com/ifields/ifields.min.js', [], '2.0', true );

		// Custom Frontend JS
		wp_register_script( 'sola-donations-js', plugins_url( 'assets/js/sola-donations.js', dirname( __FILE__ ) ), [ 'jquery', 'sola-ifields-sdk' ], Sola_Donations_Elementor::VERSION, true );

		// Pass settings to JS
		$options = get_option( 'sola_donations_settings' );
		wp_localize_script( 'sola-donations-js', 'sola_vars', [
			'ifields_key' => isset( $options['ifields_key'] ) ? $options['ifields_key'] : '',
			'environment' => isset( $options['environment'] ) ? $options['environment'] : 'sandbox',
			'ajax_url'    => admin_url( 'admin-ajax.php' ),
			'nonce'       => wp_create_nonce( 'sola_donation_nonce' ),
		] );

		// Custom Frontend CSS
		wp_register_style( 'sola-donations-css', plugins_url( 'assets/css/sola-donations.css', dirname( __FILE__ ) ), [], Sola_Donations_Elementor::VERSION );
	}

	public function register_widgets( $widgets_manager ) {
		require_once( __DIR__ . '/widgets/class-sola-donation-form-widget.php' );
		$widgets_manager->register( new \Sola_Donation_Form_Widget() );
	}

	public function get_option( $key ) {
		$options = get_option( 'sola_donations_settings' );
		if ( isset( $options[ $key ] ) ) {
			return $options[ $key ];
		}
		return null;
	}

}
