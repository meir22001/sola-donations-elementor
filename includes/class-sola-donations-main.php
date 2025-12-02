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
	}

	private function init_components() {
		$this->settings = new Sola_Donations_Settings();
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
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
