<?php
/**
 * Hive Lite Theme Customizer
 * @package Hive Lite
 */


/**
 * Change some default texts and add our own custom settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function hivelite_customize_register( $wp_customize ) {

	/*
	 * Change defaults
	 */

	// Add postMessage support for site title and tagline and title color.
	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	// Rename the label to "Display Site Title & Tagline" in order to make this option clearer.
	$wp_customize->get_control( 'display_header_text' )->label = esc_html__( 'Display Site Title &amp; Tagline', 'hive-lite' );

	// View Pro
	$wp_customize->add_section( 'pro__section', array(
		'title'       => '' . esc_html__( 'View PRO Version', 'hive-lite' ),
		'priority'    => 2,
		'description' => sprintf(
			/* translators: %s: The view pro link. */
			__( '<div class="upsell-container">
					<h2>Need More? Go PRO</h2>
					<p>Take it to the next level. See the features below:</p>
					<ul class="upsell-features">
                            <li>
                            	<h4>Personalize to Match Your Style</h4>
                            	<div class="description">Having different tastes and preferences might be tricky for users, but not with Hive onboard. It has an intuitive and catchy interface which allows you to change <strong>fonts, colors or layout sizes</strong> in a blink of an eye.</div>
                            </li>

                            <li>
                            	<h4>Adaptive Layouts For Your Posts</h4>
                            	<div class="description">Whether your featured image is in portrait or landscape mode, Hive takes care of it by changing the post layout to provide the right fit.</div>
                            </li>

                            <li>
                            	<h4>Premium Customer Support</h4>
                            	<div class="description">You will benefit by priority support from a caring and devoted team, eager to help and to spread happiness. We work hard to provide a flawless experience for those who vote us with trust and choose to be our special clients.</div>
                            </li>

                    </ul> %s </div>', 'hive-lite' ),
			/* translators: %1$s: The view pro URL, %2$s: The view pro link text. */
			sprintf( '<a href="%1$s" target="_blank" class="button button-primary">%2$s</a>', esc_url( hivelite_get_pro_link() ), esc_html__( 'View Hive PRO', 'hive-lite' ) )
		),
	) );

	$wp_customize->add_setting( 'hivelite_style_view_pro_desc', array(
		'default'           => '',
		'sanitize_callback' => '__return_true',
	) );
	$wp_customize->add_control( 'hivelite_style_view_pro_desc', array(
		'section' => 'pro__section',
		'type'    => 'hidden',
	) );
}

add_action( 'customize_register', 'hivelite_customize_register', 15 );

/**
 * Assets that will be loaded for the customizer sidebar
 */
function hivelite_customizer_assets() {
	wp_enqueue_style( 'hivelite_customizer_style', get_template_directory_uri() . '/inc/admin/css/customizer.css', null, '1.1.3', false );
}
add_action( 'customize_controls_enqueue_scripts', 'hivelite_customizer_assets' );

/**
 * JavaScript that handles the Customizer AJAX logic
 * This will be added in the preview part
 */
function hivelite_customizer_preview_assets() {
	wp_enqueue_script( 'hivelite_customizer_preview', get_template_directory_uri() . '/assets/js/customizer-preview.js', array( 'customize-preview' ), '1.1.3', true );
}
add_action( 'customize_preview_init', 'hivelite_customizer_preview_assets' );

/**
 * Generate a link to the Hive Lite info page.
 */
function hivelite_get_pro_link() {
	return 'https://pixelgrade.com/themes/blogging/hive-lite?utm_source=hive-lite-clients&utm_medium=customizer&utm_campaign=hive-lite#pro';
}
