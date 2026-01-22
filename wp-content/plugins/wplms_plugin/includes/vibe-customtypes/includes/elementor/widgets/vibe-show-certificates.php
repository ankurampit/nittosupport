<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wplms_Vibe_Show_Certificates extends \Elementor\Widget_Base  // We'll use this just to avoid function name conflicts 
{


    public function get_name() {
		return 'vibe-show-certificates';
	}

	public function get_title() {
		return __( 'Vibe Show Certificates', 'wplms' );
	}

	public function get_icon() {
		return 'vicon vicon-id-badge';
	}

	public function get_categories() {
		return [ 'wplms' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Controls', 'wplms' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'size',
			[
				'label' => __( 'size', 'wplms' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'Enter size', 'wplms' ),
			]
		);

		$this->add_control(
			'column',
			[
				'label' => __('Enter column', 'wplms'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => '',
			]
		);

		$this->add_control(
			'user',
			[
				'label' => __('Enter user', 'wplms'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => '',
			]
		);

		$this->add_control(
			'course',
			[
				'label' => __('Enter course', 'wplms'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => '',
			]
		);

		$this->add_control(
			'number',
			[
				'label' =>__('Total Number', 'wplms'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 99,
				'step' => 1,
				'default' => 6,
			]
		);


		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		$shortcode = '[show_certificates 
	    number="'.(empty($settings['number'])?6:$settings['number']).'" 
        size="'.(empty($settings['size'])?2:$settings['size']).'" ,
        columns="'.(empty($settings['columns'])?3:$settings['columns']).'"
        course ="'.(empty($settings['course'])?0:$settings['course']).'"
        " ]';
		
		//echo $shortcode;

		echo do_shortcode($shortcode);
	}

}


