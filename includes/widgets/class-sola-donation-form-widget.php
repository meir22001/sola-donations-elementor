<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Sola Donation Form Widget.
 *
 * Elementor widget that inserts the donation form.
 *
 * @since 1.0.0
 */
class Sola_Donation_Form_Widget extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'sola_donation_form';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Sola Donation Form', 'sola-donations' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-form-horizontal';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'general' ];
	}

	/**
	 * Get script dependencies.
	 *
	 * Retrieve the list of script dependencies the widget requires.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [ 'sola-donations-js' ];
	}

	/**
	 * Get style dependencies.
	 *
	 * Retrieve the list of style dependencies the widget requires.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget styles dependencies.
	 */
	public function get_style_depends() {
		return [ 'sola-donations-css' ];
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'sola-donations' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'form_title',
			[
				'label' => esc_html__( 'Form Title', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Support Our Cause', 'sola-donations' ),
				'placeholder' => esc_html__( 'Type your title here', 'sola-donations' ),
			]
		);

		$this->add_control(
			'donation_amounts',
			[
				'label' => esc_html__( 'Predefined Amounts (comma separated)', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '18, 36, 72, 180',
				'description' => esc_html__( 'Enter amounts separated by commas (e.g. 10, 20, 50)', 'sola-donations' ),
			]
		);

		$this->add_control(
			'show_custom_amount',
			[
				'label' => esc_html__( 'Allow Custom Amount', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'sola-donations' ),
				'label_off' => esc_html__( 'No', 'sola-donations' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'enable_recurring',
			[
				'label' => esc_html__( 'Enable Recurring Donations', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'sola-donations' ),
				'label_off' => esc_html__( 'No', 'sola-donations' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Style', 'sola-donations' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'button_color',
			[
				'label' => esc_html__( 'Button Color', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sola-donation-btn' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .sola-amount-btn.selected' => 'background-color: {{VALUE}}; border-color: {{VALUE}}; color: #fff;',
				],
			]
		);

		$this->end_controls_section();

		// --- Payment Options Section ---
		$this->start_controls_section(
			'payment_options_section',
			[
				'label' => esc_html__( 'Payment Options', 'sola-donations' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'donation_type',
			[
				'label' => esc_html__( 'Donation Type', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'both',
				'options' => [
					'one_time' => esc_html__( 'One-Time Only', 'sola-donations' ),
					'recurring' => esc_html__( 'Recurring Only', 'sola-donations' ),
					'both' => esc_html__( 'Both (User Selects)', 'sola-donations' ),
				],
			]
		);

		$this->add_control(
			'recurring_payment_day',
			[
				'label' => esc_html__( 'Recurring Payment Day', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '1',
				'options' => array_combine( range( 1, 28 ), range( 1, 28 ) ),
				'condition' => [
					'donation_type!' => 'one_time',
				],
			]
		);

		$this->add_control(
			'charge_immediately',
			[
				'label' => esc_html__( 'Charge Immediately?', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'sola-donations' ),
				'label_off' => esc_html__( 'No', 'sola-donations' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'description' => esc_html__( 'If checked, charges now and sets next date based on Payment Day.', 'sola-donations' ),
				'condition' => [
					'donation_type!' => 'one_time',
				],
			]
		);

		$this->add_control(
			'currency',
			[
				'label' => esc_html__( 'Currency', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'USD',
				'options' => [
					'USD' => 'USD',
					'ILS' => 'ILS',
					'EUR' => 'EUR',
				],
			]
		);

		$this->end_controls_section();

		// --- iFields Styling Section ---
		$this->start_controls_section(
			'ifields_style_section',
			[
				'label' => esc_html__( 'Input Fields (Sola)', 'sola-donations' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'ifield_font_family',
			[
				'label' => esc_html__( 'Font Family', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::FONT,
				'default' => "'Helvetica Neue', Helvetica, Arial, sans-serif",
				'selector' => '{{WRAPPER}} .sola-ifield', // Not directly applied, but good for preview if we could
			]
		);

		$this->add_control(
			'ifield_font_size',
			[
				'label' => esc_html__( 'Font Size', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 30,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 14,
				],
			]
		);

		$this->add_control(
			'ifield_color',
			[
				'label' => esc_html__( 'Text Color', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#333333',
			]
		);

		$this->add_control(
			'ifield_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#ffffff',
			]
		);

		$this->add_control(
			'ifield_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#cccccc',
			]
		);

		$this->add_control(
			'ifield_placeholder_color',
			[
				'label' => esc_html__( 'Placeholder Color', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#999999',
			]
		);

		$this->end_controls_section();

		// --- Wallets Section ---
		$this->start_controls_section(
			'wallets_section',
			[
				'label' => esc_html__( 'Wallets (Google/Apple Pay)', 'sola-donations' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'enable_wallets',
			[
				'label' => esc_html__( 'Enable Google/Apple Pay', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'no',
			]
		);

		$this->end_controls_section();

		// --- Actions After Submit Section ---
		$this->start_controls_section(
			'actions_section',
			[
				'label' => esc_html__( 'Actions After Success', 'sola-donations' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'redirect_after_success',
			[
				'label' => esc_html__( 'Redirect', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'no',
			]
		);

		$this->add_control(
			'redirect_url',
			[
				'label' => esc_html__( 'Redirect URL', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::URL,
				'condition' => [
					'redirect_after_success' => 'yes',
				],
			]
		);

		$this->add_control(
			'enable_webhook',
			[
				'label' => esc_html__( 'Webhook', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'no',
			]
		);

		$this->add_control(
			'webhook_url',
			[
				'label' => esc_html__( 'Webhook URL', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'condition' => [
					'enable_webhook' => 'yes',
				],
			]
		);

		$this->add_control(
			'email_admin',
			[
				'label' => esc_html__( 'Email Admin', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'no',
			]
		);

		$this->add_control(
			'admin_email_address',
			[
				'label' => esc_html__( 'Admin Email', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => get_option( 'admin_email' ),
				'condition' => [
					'email_admin' => 'yes',
				],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render widget output on the frontend.
	 *
	 * Written in PHP and outputting to the DOM.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$amounts = array_map('trim', explode(',', $settings['donation_amounts']));
		
		// Prepare iFields Styles
		$ifields_styles = [
			'font-family' => $settings['ifield_font_family'],
			'font-size' => $settings['ifield_font_size']['size'] . $settings['ifield_font_size']['unit'],
			'color' => $settings['ifield_color'],
			'background-color' => $settings['ifield_bg_color'],
			'border-color' => $settings['ifield_border_color'],
			'placeholder-color' => $settings['ifield_placeholder_color'],
		];
		
		$widget_config = [
			'donation_type' => $settings['donation_type'],
			'recurring_day' => $settings['recurring_payment_day'],
			'charge_immediately' => $settings['charge_immediately'],
			'currency' => $settings['currency'],
			'ifields_styles' => $ifields_styles,
			'enable_wallets' => $settings['enable_wallets'],
			'redirect' => $settings['redirect_after_success'],
			'redirect_url' => $settings['redirect_url']['url'] ?? '',
			'webhook' => $settings['enable_webhook'],
			'webhook_url' => $settings['webhook_url'],
			'email_admin' => $settings['email_admin'],
			'admin_email' => $settings['admin_email_address'],
		];
		?>
		<div class="sola-donation-wrapper" data-sola-config="<?php echo esc_attr( json_encode( $widget_config ) ); ?>">
			<?php if ( ! empty( $settings['form_title'] ) ) : ?>
				<h3 class="sola-form-title"><?php echo esc_html( $settings['form_title'] ); ?></h3>
			<?php endif; ?>

			<div class="sola-amount-selector">
				<?php foreach ( $amounts as $amount ) : ?>
					<button type="button" class="sola-amount-btn" data-amount="<?php echo esc_attr( $amount ); ?>">
						<?php echo esc_html( '$' . $amount ); ?>
					</button>
				<?php endforeach; ?>
				
				<?php if ( 'yes' === $settings['show_custom_amount'] ) : ?>
					<div class="sola-custom-amount-wrapper">
						<span class="currency-symbol"><?php echo esc_html( $settings['currency'] === 'ILS' ? '₪' : ($settings['currency'] === 'EUR' ? '€' : '$') ); ?></span>
						<input type="number" class="sola-custom-amount" placeholder="<?php esc_attr_e( 'Other', 'sola-donations' ); ?>">
					</div>
				<?php endif; ?>
			</div>

			<?php if ( 'both' === $settings['donation_type'] ) : ?>
				<div class="sola-recurring-toggle">
					<label>
						<input type="checkbox" name="is_recurring" value="1">
						<?php esc_html_e( 'Make this a monthly donation', 'sola-donations' ); ?>
					</label>
				</div>
			<?php elseif ( 'recurring' === $settings['donation_type'] ) : ?>
				<input type="hidden" name="is_recurring" value="1">
				<p class="sola-recurring-notice"><?php esc_html_e( 'Monthly Donation', 'sola-donations' ); ?></p>
			<?php endif; ?>

			<!-- Payment Fields Placeholder -->
			<div id="sola-payment-fields-container">
				<div class="sola-field-group">
					<label><?php esc_html_e( 'Card Number', 'sola-donations' ); ?></label>
					<iframe id="sola-ifield-card-number" class="sola-ifield" src="" frameborder="0" scrolling="no"></iframe>
				</div>
				<div class="sola-field-row">
					<div class="sola-field-group">
						<label><?php esc_html_e( 'Expiration', 'sola-donations' ); ?></label>
						<iframe id="sola-ifield-exp" class="sola-ifield" src="" frameborder="0" scrolling="no"></iframe>
					</div>
					<div class="sola-field-group">
						<label><?php esc_html_e( 'CVV', 'sola-donations' ); ?></label>
						<iframe id="sola-ifield-cvv" class="sola-ifield" src="" frameborder="0" scrolling="no"></iframe>
					</div>
				</div>
			</div>
			
			<?php if ( 'yes' === $settings['enable_wallets'] ) : ?>
				<div id="sola-wallets-container">
					<!-- Wallet buttons will be injected here by Sola SDK or manual buttons -->
					<button type="button" id="sola-google-pay-btn" class="sola-wallet-btn sola-google-pay">Google Pay</button>
					<button type="button" id="sola-apple-pay-btn" class="sola-wallet-btn sola-apple-pay">Apple Pay</button>
				</div>
			<?php endif; ?>

			<button type="button" id="sola-submit-btn" class="sola-donation-btn">
				<?php esc_html_e( 'Donate Now', 'sola-donations' ); ?>
			</button>
			<div id="sola-message-container"></div>
		</div>
		<?php
	}

}
