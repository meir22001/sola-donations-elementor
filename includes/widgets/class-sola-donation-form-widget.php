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

	public function get_name() {
		return 'sola_donation_form';
	}

	public function get_title() {
		return esc_html__( 'Sola Donation Form', 'sola-donations' );
	}

	public function get_icon() {
		return 'eicon-form-horizontal';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	public function get_script_depends() {
		return [ 'sola-donations-js' ];
	}

	public function get_style_depends() {
		return [ 'sola-donations-css' ];
	}

	protected function register_controls() {

		// --- CONTENT TAB ---

		// 1. Payment Settings
		$this->start_controls_section(
			'section_payment_settings',
			[
				'label' => esc_html__( 'Payment Settings', 'sola-donations' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'api_mode',
			[
				'label' => esc_html__( 'API Mode', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'sandbox',
				'options' => [
					'sandbox' => esc_html__( 'Sandbox', 'sola-donations' ),
					'live' => esc_html__( 'Live', 'sola-donations' ),
				],
				'description' => esc_html__( 'Ensure your API keys in plugin settings match this mode.', 'sola-donations' ),
			]
		);

		$this->add_control(
			'currency',
			[
				'label' => esc_html__( 'Default Currency', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'USD',
				'options' => [
					'USD' => 'USD',
					'ILS' => 'ILS',
					'EUR' => 'EUR',
				],
			]
		);

		$this->add_control(
			'allow_user_currency',
			[
				'label' => esc_html__( 'Allow User to Choose Currency', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'no',
			]
		);

		$this->add_control(
			'recurring_toggle',
			[
				'label' => esc_html__( 'Enable Recurring Donations', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
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
					'recurring_toggle' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		// 2. Preset Amounts
		$this->start_controls_section(
			'section_preset_amounts',
			[
				'label' => esc_html__( 'Preset Amounts', 'sola-donations' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'amount_value',
			[
				'label' => esc_html__( 'Amount', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 18,
				'min' => 1,
			]
		);

		$this->add_control(
			'repeater_amounts',
			[
				'label' => esc_html__( 'Donation Amounts', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[ 'amount_value' => 18 ],
					[ 'amount_value' => 36 ],
					[ 'amount_value' => 72 ],
					[ 'amount_value' => 180 ],
				],
				'title_field' => '{{{ amount_value }}}',
			]
		);

		$this->add_control(
			'enable_custom_amount',
			[
				'label' => esc_html__( 'Allow Custom Amount', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->end_controls_section();

		// 3. Donor Information Fields
		$this->start_controls_section(
			'section_donor_info',
			[
				'label' => esc_html__( 'Donor Information', 'sola-donations' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_name',
			[
				'label' => esc_html__( 'Show Name Fields', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_email',
			[
				'label' => esc_html__( 'Show Email', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_phone',
			[
				'label' => esc_html__( 'Show Phone', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_address',
			[
				'label' => esc_html__( 'Show Address Fields', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->end_controls_section();
		
		// --- STYLE TAB ---

		// 1. Form Container
		$this->start_controls_section(
			'style_container',
			[
				'label' => esc_html__( 'Form Container', 'sola-donations' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'container_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sola-donation-wrapper' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'container_border',
				'selector' => '{{WRAPPER}} .sola-donation-wrapper',
			]
		);

		$this->add_control(
			'container_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .sola-donation-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'container_box_shadow',
				'selector' => '{{WRAPPER}} .sola-donation-wrapper',
			]
		);

		$this->add_responsive_control(
			'container_padding',
			[
				'label' => esc_html__( 'Padding', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .sola-donation-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// 2. Typography & Labels
		$this->start_controls_section(
			'style_labels',
			[
				'label' => esc_html__( 'Typography & Labels', 'sola-donations' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'labels_typography',
				'selector' => '{{WRAPPER}} label',
			]
		);

		$this->add_control(
			'labels_color',
			[
				'label' => esc_html__( 'Label Color', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} label' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		// 3. Input Fields (Standard)
		$this->start_controls_section(
			'style_inputs',
			[
				'label' => esc_html__( 'Input Fields (Standard)', 'sola-donations' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'input_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} input[type="text"], {{WRAPPER}} input[type="email"], {{WRAPPER}} input[type="tel"], {{WRAPPER}} input[type="number"], {{WRAPPER}} select' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'input_text_color',
			[
				'label' => esc_html__( 'Text Color', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} input[type="text"], {{WRAPPER}} input[type="email"], {{WRAPPER}} input[type="tel"], {{WRAPPER}} input[type="number"], {{WRAPPER}} select' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'input_typography',
				'selector' => '{{WRAPPER}} input[type="text"], {{WRAPPER}} input[type="email"], {{WRAPPER}} input[type="tel"], {{WRAPPER}} input[type="number"], {{WRAPPER}} select',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'input_border',
				'selector' => '{{WRAPPER}} input[type="text"], {{WRAPPER}} input[type="email"], {{WRAPPER}} input[type="tel"], {{WRAPPER}} input[type="number"], {{WRAPPER}} select',
			]
		);

		$this->add_control(
			'input_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} input[type="text"], {{WRAPPER}} input[type="email"], {{WRAPPER}} input[type="tel"], {{WRAPPER}} input[type="number"], {{WRAPPER}} select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'input_padding',
			[
				'label' => esc_html__( 'Padding', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} input[type="text"], {{WRAPPER}} input[type="email"], {{WRAPPER}} input[type="tel"], {{WRAPPER}} input[type="number"], {{WRAPPER}} select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// 4. Sola iFields (Credit Card Frames)
		$this->start_controls_section(
			'style_ifields',
			[
				'label' => esc_html__( 'Sola iFields (Credit Card)', 'sola-donations' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'ifield_container_heading',
			[
				'label' => esc_html__( 'Container Styling', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'ifield_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .sola-ifield-container' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'ifield_border',
				'selector' => '{{WRAPPER}} .sola-ifield-container',
			]
		);

		$this->add_control(
			'ifield_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .sola-ifield-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'ifield_internal_heading',
			[
				'label' => esc_html__( 'Internal Text Styling (Passed to Sola)', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'ifield_text_color',
			[
				'label' => esc_html__( 'Text Color', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#333333',
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
		
		$this->add_control(
			'ifield_font_family',
			[
				'label' => esc_html__( 'Font Family', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::FONT,
				'default' => "'Helvetica Neue', Helvetica, Arial, sans-serif",
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

		$this->end_controls_section();

		// 5. Amount Buttons
		$this->start_controls_section(
			'style_amount_buttons',
			[
				'label' => esc_html__( 'Amount Buttons', 'sola-donations' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'amount_btn_typography',
				'selector' => '{{WRAPPER}} .sola-amount-btn',
			]
		);

		$this->start_controls_tabs( 'tabs_amount_btn_style' );

		$this->start_controls_tab(
			'tab_amount_btn_normal',
			[
				'label' => esc_html__( 'Normal', 'sola-donations' ),
			]
		);

		$this->add_control(
			'amount_btn_color',
			[
				'label' => esc_html__( 'Text Color', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sola-amount-btn' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'amount_btn_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sola-amount-btn' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'amount_btn_border',
				'selector' => '{{WRAPPER}} .sola-amount-btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_amount_btn_hover',
			[
				'label' => esc_html__( 'Hover', 'sola-donations' ),
			]
		);

		$this->add_control(
			'amount_btn_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sola-amount-btn:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'amount_btn_hover_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sola-amount-btn:hover' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'amount_btn_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sola-amount-btn:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_amount_btn_selected',
			[
				'label' => esc_html__( 'Selected', 'sola-donations' ),
			]
		);

		$this->add_control(
			'amount_btn_selected_color',
			[
				'label' => esc_html__( 'Text Color', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sola-amount-btn.selected' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'amount_btn_selected_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sola-amount-btn.selected' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'amount_btn_selected_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sola-amount-btn.selected' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'amount_btn_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .sola-amount-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'amount_btn_gap',
			[
				'label' => esc_html__( 'Gap', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .sola-amounts-grid' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// 6. Submit Button
		$this->start_controls_section(
			'style_submit_button',
			[
				'label' => esc_html__( 'Submit Button', 'sola-donations' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'submit_btn_typography',
				'selector' => '{{WRAPPER}} .sola-donation-btn',
			]
		);

		$this->start_controls_tabs( 'tabs_submit_btn_style' );

		$this->start_controls_tab(
			'tab_submit_btn_normal',
			[
				'label' => esc_html__( 'Normal', 'sola-donations' ),
			]
		);

		$this->add_control(
			'submit_btn_color',
			[
				'label' => esc_html__( 'Text Color', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sola-donation-btn' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'submit_btn_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sola-donation-btn' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_submit_btn_hover',
			[
				'label' => esc_html__( 'Hover', 'sola-donations' ),
			]
		);

		$this->add_control(
			'submit_btn_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sola-donation-btn:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'submit_btn_hover_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sola-donation-btn:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'submit_btn_border',
				'selector' => '{{WRAPPER}} .sola-donation-btn',
			]
		);

		$this->add_control(
			'submit_btn_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .sola-donation-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'submit_btn_padding',
			[
				'label' => esc_html__( 'Padding', 'sola-donations' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .sola-donation-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'submit_btn_box_shadow',
				'selector' => '{{WRAPPER}} .sola-donation-btn',
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		
		// Prepare Sola Styles for JS (passed to iFields)
		$sola_styles = [
			'fontFamily' => !empty($settings['ifield_font_family']) ? $settings['ifield_font_family'] : 'inherit',
			'fontSize' => !empty($settings['ifield_font_size']['size']) ? $settings['ifield_font_size']['size'] . $settings['ifield_font_size']['unit'] : '14px',
			'textColor' => $settings['ifield_text_color'],
			'placeholderColor' => $settings['ifield_placeholder_color'],
		];
		
		$widget_config = [
			'api_mode' => $settings['api_mode'],
			'currency' => $settings['currency'],
			'allow_user_currency' => $settings['allow_user_currency'],
			'recurring_enabled' => $settings['recurring_toggle'],
			'recurring_day' => $settings['recurring_payment_day'],
			'debug_mode' => true, // Force debug mode
		];
		?>
		<div class="sola-donation-wrapper" 
			 data-sola-config="<?php echo esc_attr( json_encode( $widget_config ) ); ?>"
			 data-sola-styles="<?php echo esc_attr( json_encode( $sola_styles ) ); ?>">
			
			<!-- Amounts Section -->
			<div class="sola-amounts-grid">
				<?php if ( $settings['repeater_amounts'] ) : ?>
					<?php foreach ( $settings['repeater_amounts'] as $item ) : ?>
						<button type="button" class="sola-amount-btn" data-amount="<?php echo esc_attr( $item['amount_value'] ); ?>">
							<?php echo esc_html( '$' . $item['amount_value'] ); ?>
						</button>
					<?php endforeach; ?>
				<?php endif; ?>
				
				<?php if ( 'yes' === $settings['enable_custom_amount'] ) : ?>
					<div class="sola-custom-amount-wrapper">
						<input type="number" class="sola-custom-amount" placeholder="<?php esc_attr_e( 'Other', 'sola-donations' ); ?>">
					</div>
				<?php endif; ?>
			</div>
			
			<!-- Currency Selection (Explicit Check) -->
			<?php if ( 'yes' === $settings['allow_user_currency'] ) : ?>
				<div class="sola-currency-wrapper">
					<label for="sola_currency_select"><?php esc_html_e( 'Currency', 'sola-donations' ); ?></label>
					<select id="sola_currency_select" class="sola-currency-select" name="donation_currency">
						<option value="USD" <?php selected( $settings['currency'], 'USD' ); ?>>USD ($)</option>
						<option value="ILS" <?php selected( $settings['currency'], 'ILS' ); ?>>ILS (₪)</option>
						<option value="EUR" <?php selected( $settings['currency'], 'EUR' ); ?>>EUR (€)</option>
					</select>
				</div>
			<?php endif; ?>

			<!-- Recurring Toggle -->
			<?php if ( 'yes' === $settings['recurring_toggle'] ) : ?>
				<div class="sola-recurring-toggle">
					<label>
						<input type="checkbox" name="is_recurring" value="1">
						<?php esc_html_e( 'Make this a monthly donation', 'sola-donations' ); ?>
					</label>
				</div>
			<?php endif; ?>
			
			<!-- Donor Information Fields -->
			<div class="sola-donor-info">
				<?php if ( 'yes' === $settings['show_name'] ) : ?>
					<div class="sola-form-row">
						<div class="sola-field-group">
							<label><?php esc_html_e( 'First Name', 'sola-donations' ); ?></label>
							<input type="text" name="first_name" required>
						</div>
						<div class="sola-field-group">
							<label><?php esc_html_e( 'Last Name', 'sola-donations' ); ?></label>
							<input type="text" name="last_name" required>
						</div>
					</div>
				<?php endif; ?>
				
				<?php if ( 'yes' === $settings['show_email'] ) : ?>
					<div class="sola-field-group">
						<label><?php esc_html_e( 'Email Address', 'sola-donations' ); ?></label>
						<input type="email" name="email" required>
					</div>
				<?php endif; ?>
				
				<?php if ( 'yes' === $settings['show_phone'] ) : ?>
					<div class="sola-field-group">
						<label><?php esc_html_e( 'Phone Number', 'sola-donations' ); ?></label>
						<input type="tel" name="phone">
					</div>
				<?php endif; ?>
				
				<?php if ( 'yes' === $settings['show_address'] ) : ?>
					<div class="sola-field-group">
						<label><?php esc_html_e( 'Street Address', 'sola-donations' ); ?></label>
						<input type="text" name="address">
					</div>
					<div class="sola-form-row">
						<div class="sola-field-group">
							<label><?php esc_html_e( 'City', 'sola-donations' ); ?></label>
							<input type="text" name="city">
						</div>
						<div class="sola-field-group">
							<label><?php esc_html_e( 'Zip Code', 'sola-donations' ); ?></label>
							<input type="text" name="zip">
						</div>
					</div>
				<?php endif; ?>
			</div>

			<!-- Payment Fields (iFields) - Explicit IDs -->
			<div id="sola-payment-fields-container">
				<div class="sola-field-group">
					<label><?php esc_html_e( 'Card Number', 'sola-donations' ); ?></label>
					<div id="sola-card-number" class="sola-ifield-container"></div>
				</div>
				<div class="sola-form-row">
					<div class="sola-field-group">
						<label><?php esc_html_e( 'Expiration', 'sola-donations' ); ?></label>
						<div id="sola-expiry" class="sola-ifield-container"></div>
					</div>
					<div class="sola-field-group">
						<label><?php esc_html_e( 'CVV', 'sola-donations' ); ?></label>
						<div id="sola-cvv" class="sola-ifield-container"></div>
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
