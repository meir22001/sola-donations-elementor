<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Sola_Donations_Settings {

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
	}

	public function add_admin_menu() {
		add_menu_page(
			__( 'Sola Donations', 'sola-donations' ),
			__( 'Sola Donations', 'sola-donations' ),
			'manage_options',
			'sola-donations',
			[ $this, 'settings_page_html' ],
			'dashicons-heart',
			50
		);
	}

	public function register_settings() {
		register_setting( 'sola_donations_group', 'sola_donations_settings' );

		add_settings_section(
			'sola_donations_api_section',
			__( 'API Configuration', 'sola-donations' ),
			null,
			'sola-donations'
		);

		add_settings_field(
			'sola_xkey',
			__( 'xKey', 'sola-donations' ),
			[ $this, 'render_xkey_field' ],
			'sola-donations',
			'sola_donations_api_section'
		);

		add_settings_field(
			'sola_ifields_key',
			__( 'iFields Key', 'sola-donations' ),
			[ $this, 'render_ifields_key_field' ],
			'sola-donations',
			'sola_donations_api_section'
		);

		add_settings_field(
			'sola_environment',
			__( 'Environment', 'sola-donations' ),
			[ $this, 'render_environment_field' ],
			'sola-donations',
			'sola_donations_api_section'
		);
	}

	public function render_xkey_field() {
		$options = get_option( 'sola_donations_settings' );
		$value = isset( $options['xkey'] ) ? $options['xkey'] : '';
		?>
		<input type="password" name="sola_donations_settings[xkey]" value="<?php echo esc_attr( $value ); ?>" class="regular-text">
		<p class="description"><?php _e( 'Your Sola Payments (Cardknox) xKey.', 'sola-donations' ); ?></p>
		<?php
	}

	public function render_ifields_key_field() {
		$options = get_option( 'sola_donations_settings' );
		$value = isset( $options['ifields_key'] ) ? $options['ifields_key'] : '';
		?>
		<input type="text" name="sola_donations_settings[ifields_key]" value="<?php echo esc_attr( $value ); ?>" class="regular-text">
		<p class="description"><?php _e( 'Your Sola iFields Key for frontend tokenization.', 'sola-donations' ); ?></p>
		<?php
	}

	public function render_environment_field() {
		$options = get_option( 'sola_donations_settings' );
		$value = isset( $options['environment'] ) ? $options['environment'] : 'sandbox';
		?>
		<select name="sola_donations_settings[environment]">
			<option value="sandbox" <?php selected( $value, 'sandbox' ); ?>><?php _e( 'Sandbox', 'sola-donations' ); ?></option>
			<option value="live" <?php selected( $value, 'live' ); ?>><?php _e( 'Live', 'sola-donations' ); ?></option>
		</select>
		<p class="description"><?php _e( 'Select the environment to use.', 'sola-donations' ); ?></p>
		<?php
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
				settings_fields( 'sola_donations_group' );
				do_settings_sections( 'sola-donations' );
				submit_button( __( 'Save Settings', 'sola-donations' ) );
				?>
			</form>
		</div>
		<?php
	}

}
