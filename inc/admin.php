<?php
/**
 * Hive Lite admin logic.
 *
 * @package Hive Lite
 */

/**
 * Load Recommended plugins notification logic.
 */
require_once get_template_directory() . '/inc/admins/required-plugins.php';


function hivelite_admin_setup() {

	/**
	 * Load and initialize Pixelgrade Assistant notice logic.
	 * @link https://wordpress.org/plugins/pixelgrade-assistant/
	 */
	require_once 'pixelgrade-assistant-notice/class-notice.php';
	PixelgradeAssistant_Install_Notice::init();
}
add_action('after_setup_theme', 'hivelite_admin_setup' );
