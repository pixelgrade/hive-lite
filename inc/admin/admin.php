<?php
/**
 * Hive Lite Theme admin logic.
 *
 * @package Hive Lite
 */

function hive_lite_admin_setup() {

	/**
	 * Load and initialize Pixelgrade Care notice logic.
	 */
	require_once 'pixcare-notice/class-notice.php';
	HiveLite_PixelgradeCare_DownloadNotice::init();
}
add_action('after_setup_theme', 'hive_lite_admin_setup' );
