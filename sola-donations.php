<?php
/**
 * Plugin Name: Sola Donations
 * Description: A custom donation plugin integrating with Sola Payments.
 * Version: 1.0.0
 * Author: Antigravity
 * Text Domain: sola-donations
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SOLA_DONATIONS_VERSION', '1.0.0' );
define( 'SOLA_DONATIONS_PATH', plugin_dir_path( __FILE__ ) );
define( 'SOLA_DONATIONS_URL', plugin_dir_url( __FILE__ ) );

// Include classes
require_once SOLA_DONATIONS_PATH . 'includes/class-sola-settings.php';
require_once SOLA_DONATIONS_PATH . 'includes/class-sola-api.php';
require_once SOLA_DONATIONS_PATH . 'includes/class-sola-form.php';

/**
 * Initialize the plugin
 */
function sola_donations_init() {
	$settings = new Sola_Settings();
	$api      = new Sola_API();
	$form     = new Sola_Form( $api );
}
add_action( 'plugins_loaded', 'sola_donations_init' );
