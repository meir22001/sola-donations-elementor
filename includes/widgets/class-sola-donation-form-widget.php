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
		?>
		<div class="sola-donation-wrapper">
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
						<span class="currency-symbol">$</span>
						<input type="number" class="sola-custom-amount" placeholder="<?php esc_attr_e( 'Other', 'sola-donations' ); ?>">
					</div>
				<?php endif; ?>
			</div>

			<?php if ( 'yes' === $settings['enable_recurring'] ) : ?>
				<div class="sola-recurring-toggle">
					<label>
						<input type="checkbox" name="is_recurring" value="1">
						<?php esc_html_e( 'Make this a monthly donation', 'sola-donations' ); ?>
					</label>
				</div>
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

			<button type="button" id="sola-submit-btn" class="sola-donation-btn">
				<?php esc_html_e( 'Donate Now', 'sola-donations' ); ?>
			</button>
			<div id="sola-message-container"></div>
		</div>
		<?php
	}

}
