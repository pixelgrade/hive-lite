<?php
/**
 * Hive Theme Customizer
 * @package Hive
 */


/**
 * Change some default texts and add our own custom settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function hive_customize_register ( $wp_customize ) {

	/*
	 * Change defaults
	 */

	// Add postMessage support for site title and tagline and title color.
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';

	// Rename the label to "Display Site Title & Tagline" in order to make this option clearer.
	$wp_customize->get_control( 'display_header_text' )->label = __( 'Display Site Title &amp; Tagline', 'hive_txtd' );

	/*
	 * Add custom settings
	 */

	$wp_customize->add_section( 'hive_theme_options', array(
		'title'             => __( 'Hive Options', 'hive_txtd' ),
		'priority'          => 30,
	) );

	$wp_customize->add_setting( 'hive_disable_autostyle_titles', array(
		'default'           => '',
		'sanitize_callback' => 'hive_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'hive_disable_autostyle_titles', array(
		'label'             => __( 'Disable auto-style post titles', 'hive_txtd' ),
		'section'           => 'hive_theme_options',
		'type'              => 'checkbox',
	) );

	$wp_customize->add_setting( 'hive_disable_search_in_toolbar', array(
		'default'           => '',
		'sanitize_callback' => 'hive_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'hive_disable_search_in_toolbar', array(
		'label'             => __( 'Hide search button in toolbar', 'hive_txtd' ),
		'section'           => 'hive_theme_options',
		'type'              => 'checkbox',
	) );

	$wp_customize->add_setting( 'hive_title_size', array(
		'default'           => 'site-title--medium',
		'transport'         => 'postMessage', //we will use JS to update the class in the Customizer
		'sanitize_callback' => 'hive_sanitize_title_size',
	) );

	$wp_customize->add_control( 'hive_title_size',
		array(
			'type'      => 'select',
			'label'     => __( 'Site Title Size', 'hive_txtd' ),
			'section'   => 'title_tagline',
			'choices'   => array(
				'site-title--small'     => __( 'Small', 'hive_txtd' ),
				'site-title--medium'    => __( 'Medium', 'hive_txtd' ),
				'site-title--large'     => __( 'Large', 'hive_txtd' ),
			),
		)
	);
}
add_action( 'customize_register', 'hive_customize_register' );

function hive_sanitize_title_size( $input ) {
	$valid = array(
		'site-title--small',
		'site-title--medium',
		'site-title--large',
	);

	if ( array_search( $input, $valid ) !== false ) {
		return $input;
	} else {
		return 'site-title--large';
	}
}

/**
 * Sanitize the checkbox.
 *
 * @param boolean $input.
 * @return boolean true if is 1 or '1', false if anything else
 */
function hive_sanitize_checkbox( $input ) {
	if ( 1 == $input ) {
		return true;
	} else {
		return false;
	}
}

/**
 * JavaScript that handles the Customizer AJAX logic
 */
function hive_customizer_js() {
	wp_enqueue_script( 'hive_customizer', get_stylesheet_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), '1.0.0', true );
}
add_action( 'customize_preview_init', 'hive_customizer_js' ); ?>