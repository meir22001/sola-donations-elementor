<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Sola_Settings {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	public function add_admin_menu() {
		add_options_page(
			'Sola Donations Settings',
			'Sola Donations',
			'manage_options',
			'sola_donations',
			array( $this, 'settings_page_html' )
		);
	}

	public function register_settings() {
		register_setting( 'sola_donations_options', 'sola_donations_settings' );

		add_settings_section(
			'sola_donations_general',
			'General Settings',
			null,
			'sola_donations'
		);

		add_settings_field(
			'sola_mode',
			'Mode',
			array( $this, 'mode_callback' ),
			'sola_donations',
			'sola_donations_general'
		);

		add_settings_field(
			'sola_live_key',
			'Live API Key (xKey)',
			array( $this, 'live_key_callback' ),
			'sola_donations',
			'sola_donations_general'
		);

		add_settings_field(
			'sola_sandbox_key',
			'Sandbox API Key (xKey)',
			array( $this, 'sandbox_key_callback' ),
			'sola_donations',
			'sola_donations_general'
		);

		add_settings_field(
			'sola_redirect_url',
			'Success Redirect URL',
			array( $this, 'redirect_url_callback' ),
			'sola_donations',
			'sola_donations_general'
		);
		
		add_settings_field(
			'sola_webhook_info',
			'Webhook Information',
			array( $this, 'webhook_info_callback' ),
			'sola_donations',
			'sola_donations_general'
		);
	}

	public function settings_page_html() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
				<?php
				settings_fields( 'sola_donations_options' );
				do_settings_sections( 'sola_donations' );
				submit_button( 'Save Settings' );
				?>
			</form>
		</div>
		<?php
	}

	public function mode_callback() {
		$options = get_option( 'sola_donations_settings' );
		$mode = isset( $options['mode'] ) ? $options['mode'] : 'sandbox';
		?>
		<select name="sola_donations_settings[mode]">
			<option value="sandbox" <?php selected( $mode, 'sandbox' ); ?>>Sandbox</option>
			<option value="live" <?php selected( $mode, 'live' ); ?>>Live</option>
		</select>
		<p class="description">Select "Sandbox" for testing with the Sandbox API Key.</p>
		<?php
	}

	public function live_key_callback() {
		$options = get_option( 'sola_donations_settings' );
		$value = isset( $options['live_key'] ) ? $options['live_key'] : '';
		echo '<input type="text" name="sola_donations_settings[live_key]" value="' . esc_attr( $value ) . '" class="regular-text">';
	}

	public function sandbox_key_callback() {
		$options = get_option( 'sola_donations_settings' );
		$value = isset( $options['sandbox_key'] ) ? $options['sandbox_key'] : '';
		echo '<input type="text" name="sola_donations_settings[sandbox_key]" value="' . esc_attr( $value ) . '" class="regular-text">';
	}

	public function redirect_url_callback() {
		$options = get_option( 'sola_donations_settings' );
		$value = isset( $options['redirect_url'] ) ? $options['redirect_url'] : '';
		echo '<input type="url" name="sola_donations_settings[redirect_url]" value="' . esc_attr( $value ) . '" class="regular-text" placeholder="https://example.com/thank-you">';
		echo '<p class="description">URL to redirect users to after a successful donation.</p>';
	}
	
	public function webhook_info_callback() {
		echo '<p class="description">Your Webhook URL for Sola Payments is: <code>' . esc_url( rest_url( 'sola/v1/webhook' ) ) . '</code></p>';
		echo '<p class="description">Please configure this in your Sola Payments dashboard if needed.</p>';
	}
}
