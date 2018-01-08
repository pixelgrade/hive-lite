<?php
/**
 * WordPress.com-specific functions and definitions.
 *
 * @package Hive
 * @since   Hive 1.0
 */

/**
 * Adds support for wp.com-specific theme functions.
 *
 * @global array $themecolors
 */
function hive_wpcom_setup() {
	global $themecolors;

	// Set theme colors for third party services.
	if ( ! isset( $themecolors ) ) {
		$themecolors = array(
			'bg'     => '171617',
			'border' => '000000',
			'text'   => '3d3e40',
			'link'   => '8c888c',
			'url'    => '8c888c',
		);
	}
}
add_action( 'after_setup_theme', 'hive_wpcom_setup' );

/**
 * Remove sharing from blog home
 *
 */
function hive_remove_share() {
	if ( ! is_home() ) {
		return;
	}

	remove_filter( 'post_flair', 'sharing_display', 20 );

	if ( class_exists( 'Jetpack_Likes' ) ) {
		remove_filter( 'post_flair', array( Jetpack_Likes::init(), 'post_likes' ), 30, 1 );
	}
}
add_action( 'loop_start', 'hive_remove_share' );