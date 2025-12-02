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
		
		// Enqueue Tailwind CSS (CDN for immediate usage)
		wp_enqueue_style( 'tailwind-css', 'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css', [], '2.2.19' );
		
		// Prepare Sola Styles for JS (Dark Mode)
		$sola_styles = [
			'fontFamily' => 'inherit',
			'fontSize' => '16px',
			'textColor' => '#ffffff', // White text for dark mode
			'placeholderColor' => '#94a3b8', // Slate-400
		];
		
		$widget_config = [
			'api_mode' => $settings['api_mode'],
			'currency' => $settings['currency'],
			'allow_user_currency' => $settings['allow_user_currency'],
			'recurring_enabled' => $settings['recurring_toggle'],
			'recurring_day' => $settings['recurring_payment_day'],
			'debug_mode' => true,
		];
		?>
		<div class="sola-donation-wrapper w-full max-w-md mx-auto bg-slate-900 rounded-3xl shadow-2xl overflow-hidden font-sans" 
			 data-sola-config="<?php echo esc_attr( json_encode( $widget_config ) ); ?>"
			 data-sola-styles="<?php echo esc_attr( json_encode( $sola_styles ) ); ?>">
			
			<!-- Header -->
			<div class="bg-slate-800/50 p-6 text-center border-b border-slate-700">
				<h2 class="text-2xl font-bold text-white"><?php echo esc_html( $settings['form_title'] ); ?></h2>
				<p class="text-slate-400 text-sm mt-1"><?php esc_html_e( 'Secure Donation', 'sola-donations' ); ?></p>
			</div>

			<div class="p-6 space-y-6">
				
				<!-- Frequency Toggle -->
				<?php if ( 'yes' === $settings['recurring_toggle'] ) : ?>
					<div class="flex bg-slate-800 rounded-xl p-1 border border-slate-700">
						<button type="button" class="sola-frequency-btn w-1/2 py-2 rounded-lg text-sm font-medium transition-all text-white bg-slate-700 shadow-sm" data-frequency="once">
							<?php esc_html_e( 'Give Once', 'sola-donations' ); ?>
						</button>
						<button type="button" class="sola-frequency-btn w-1/2 py-2 rounded-lg text-sm font-medium transition-all text-slate-400 hover:text-white" data-frequency="monthly">
							<?php esc_html_e( 'Monthly', 'sola-donations' ); ?>
						</button>
						<input type="hidden" name="is_recurring" id="sola_is_recurring" value="0">
					</div>
				<?php endif; ?>

				<!-- Amounts Grid -->
				<div class="grid grid-cols-3 gap-3">
					<?php if ( $settings['repeater_amounts'] ) : ?>
						<?php foreach ( $settings['repeater_amounts'] as $index => $item ) : ?>
							<button type="button" class="sola-amount-btn py-3 px-4 rounded-xl border-2 border-slate-700 text-slate-300 font-semibold hover:border-rose-400 hover:text-rose-400 transition-all <?php echo $index === 1 ? 'border-rose-400 bg-rose-400/10 text-rose-400 selected' : ''; ?>" data-amount="<?php echo esc_attr( $item['amount_value'] ); ?>">
								$<?php echo esc_html( $item['amount_value'] ); ?>
							</button>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>

				<!-- Custom Amount -->
				<?php if ( 'yes' === $settings['enable_custom_amount'] ) : ?>
					<div class="relative">
						<span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-semibold">$</span>
						<input type="number" class="sola-custom-amount w-full bg-slate-800 border-2 border-slate-700 rounded-xl pl-8 pr-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-rose-400 focus:ring-1 focus:ring-rose-400 transition-all" placeholder="<?php esc_attr_e( 'Enter custom amount', 'sola-donations' ); ?>">
					</div>
				<?php endif; ?>

				<!-- Currency Selection -->
				<?php if ( 'yes' === $settings['allow_user_currency'] ) : ?>
					<div class="relative">
						<label class="block text-xs font-medium text-slate-400 mb-1 uppercase tracking-wider"><?php esc_html_e( 'Currency', 'sola-donations' ); ?></label>
						<select id="sola_currency_select" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-rose-400 transition-all appearance-none">
							<option value="USD" <?php selected( $settings['currency'], 'USD' ); ?>>USD ($)</option>
							<option value="ILS" <?php selected( $settings['currency'], 'ILS' ); ?>>ILS (₪)</option>
							<option value="EUR" <?php selected( $settings['currency'], 'EUR' ); ?>>EUR (€)</option>
						</select>
						<div class="absolute right-4 top-[38px] pointer-events-none text-slate-400">
							<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
						</div>
					</div>
				<?php endif; ?>

				<!-- Personal Info -->
				<div class="space-y-4 pt-4 border-t border-slate-700">
					<h3 class="text-sm font-medium text-slate-400 uppercase tracking-wider"><?php esc_html_e( 'Personal Info', 'sola-donations' ); ?></h3>
					
					<div class="grid grid-cols-2 gap-4">
						<input type="text" name="first_name" placeholder="First Name" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-rose-400 transition-all" required>
						<input type="text" name="last_name" placeholder="Last Name" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-rose-400 transition-all" required>
					</div>
					
					<input type="email" name="email" placeholder="Email Address" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-rose-400 transition-all" required>
					
					<?php if ( 'yes' === $settings['show_phone'] ) : ?>
						<input type="tel" name="phone" placeholder="Phone Number" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-rose-400 transition-all">
					<?php endif; ?>
					
					<?php if ( 'yes' === $settings['show_address'] ) : ?>
						<input type="text" name="address" placeholder="Street Address" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-rose-400 transition-all">
						<div class="grid grid-cols-2 gap-4">
							<input type="text" name="city" placeholder="City" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-rose-400 transition-all">
							<input type="text" name="zip" placeholder="Zip Code" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-rose-400 transition-all">
						</div>
					<?php endif; ?>
				</div>

				<!-- Payment Info -->
				<div class="space-y-4 pt-4 border-t border-slate-700">
					<h3 class="text-sm font-medium text-slate-400 uppercase tracking-wider"><?php esc_html_e( 'Payment Details', 'sola-donations' ); ?></h3>
					
					<div class="space-y-3">
						<!-- Card Number -->
						<div class="relative">
							<div id="ifields_card_number" class="sola-ifield-container w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 h-[52px] focus-within:border-rose-400 transition-all"></div>
						</div>
						
						<div class="grid grid-cols-2 gap-4">
							<!-- Expiry -->
							<div class="relative">
								<div id="ifields_expiration_date" class="sola-ifield-container w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 h-[52px] focus-within:border-rose-400 transition-all"></div>
							</div>
							<!-- CVV -->
							<div class="relative">
								<div id="ifields_cvv" class="sola-ifield-container w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 h-[52px] focus-within:border-rose-400 transition-all"></div>
							</div>
						</div>
					</div>
				</div>

				<!-- Submit Button -->
				<button type="button" id="sola-submit-btn" class="w-full bg-rose-500 hover:bg-rose-600 text-white font-bold py-4 rounded-xl shadow-lg shadow-rose-500/20 transition-all transform hover:scale-[1.02] active:scale-[0.98]">
					<?php esc_html_e( 'Donate Now', 'sola-donations' ); ?>
				</button>
				
				<div id="sola-message-container" class="text-center text-sm font-medium"></div>
				
				<div class="text-center">
					<p class="text-xs text-slate-500 flex items-center justify-center gap-1">
						<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
						Secured by Sola Payments
					</p>
				</div>
			</div>
		</div>
		<?php
	}

}
