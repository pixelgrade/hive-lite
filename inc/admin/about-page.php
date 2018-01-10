<?php
/**
 * Hive Lite Theme About Page logic.
 *
 * @package Hive Lite
 */

function hivelite_admin_setup() {
	/**
	 * Load the About page class
	 */
	require_once 'ti-about-page/class-ti-about-page.php';

	/*
	* About page instance
	*/
	$config = array(
		// Menu name under Appearance.
		'menu_name'               => esc_html__( 'About Hive Lite', 'hive-lite' ),
		// Page title.
		'page_name'               => esc_html__( 'About Hive Lite', 'hive-lite' ),
		// Main welcome title
		'welcome_title'         => sprintf( esc_html__( 'Welcome to %s! - Version ', 'hive-lite' ), 'Hive Lite' ),
		// Main welcome content
		'welcome_content'       => esc_html__( ' Hive Lite is a free magazine-style theme designed to empower fashion bloggers to write more beautiful stories into an eye-candy playground.', 'hive-lite' ),
		/**
		 * Tabs array.
		 *
		 * The key needs to be ONLY consisted from letters and underscores. If we want to define outside the class a function to render the tab,
		 * the will be the name of the function which will be used to render the tab content.
		 */
		'tabs'                    => array(
			'getting_started'  => esc_html__( 'Getting Started', 'hive-lite' ),
			'recommended_actions' => esc_html__( 'Recommended Actions', 'hive-lite' ),
			'recommended_plugins' => esc_html__( 'Useful Plugins','hive-lite' ),
			'support'       => esc_html__( 'Support', 'hive-lite' ),
			'changelog'        => esc_html__( 'Changelog', 'hive-lite' ),
			'free_pro'         => esc_html__( 'Free VS PRO', 'hive-lite' ),
		),
		// Support content tab.
		'support_content'      => array(
			'first' => array (
				'title' => esc_html__( 'Contact Support','hive-lite' ),
				'icon' => 'dashicons dashicons-sos',
				'text' => __( 'We want to make sure you have the best experience using Hive Lite. If you <strong>do not have a paid upgrade</strong>, please post your question in our community forums.','hive-lite' ),
				'button_label' => esc_html__( 'Contact Support','hive-lite' ),
				'button_link' => esc_url( 'https://wordpress.org/support/theme/hive-lite' ),
				'is_button' => true,
				'is_new_tab' => true
			),
			'second' => array(
				'title' => esc_html__( 'Documentation','hive-lite' ),
				'icon' => 'dashicons dashicons-book-alt',
				'text' => esc_html__( 'Need more details? Please check our full documentation for detailed information on how to use Hive Lite.','hive-lite' ),
				'button_label' => esc_html__( 'Read The Documentation','hive-lite' ),
				'button_link' => 'https://pixelgrade.com/hive-lite-documentation/',
				'is_button' => false,
				'is_new_tab' => true
			)
		),
		// Getting started tab
		'getting_started' => array(
			'first' => array(
				'title' => esc_html__( 'Go to Customizer','hive-lite' ),
				'text' => esc_html__( 'Using the WordPress Customizer you can easily customize every aspect of the theme.','hive-lite' ),
				'button_label' => esc_html__( 'Go to Customizer','hive-lite' ),
				'button_link' => esc_url( admin_url( 'customize.php' ) ),
				'is_button' => true,
				'recommended_actions' => false,
				'is_new_tab' => true
			),
			'second' => array (
				'title' => esc_html__( 'Recommended actions','hive-lite' ),
				'text' => esc_html__( 'We have compiled a list of steps for you, to take make sure the experience you will have using one of our products is very easy to follow.','hive-lite' ),
				'button_label' => esc_html__( 'Recommended actions','hive-lite' ),
				'button_link' => esc_url( admin_url( 'themes.php?page=hive-lite-welcome&tab=recommended_actions' ) ),
				'button_ok_label' => esc_html__( 'You are good to go!','hive-lite' ),
				'is_button' => false,
				'recommended_actions' => true,
				'is_new_tab' => false
			),
			'third' => array(
				'title' => esc_html__( 'Read the documentation','hive-lite' ),
				'text' => esc_html__( 'Need more details? Please check our full documentation for detailed information on how to use Hive Lite.','hive-lite' ),
				'button_label' => esc_html__( 'Documentation','hive-lite' ),
				'button_link' => 'https://pixelgrade.com/hive-lite-documentation/',
				'is_button' => false,
				'recommended_actions' => false,
				'is_new_tab' => true
			)
		),
		// Free vs pro array.
		'free_pro'                => array(
			'free_theme_name'     => 'Hive Lite',
			'pro_theme_name'      => 'Hive PRO',
			'pro_theme_link'      => 'https://pixelgrade.com/themes/hive-lite/?utm_source=hive-lite-clients&utm_medium=about-page&utm_campaign=hive-lite#pro',
			'get_pro_theme_label' => sprintf( __( 'View %s', 'hive-lite' ), 'Hive Pro' ),
			'features'            => array(
				array(
					'title'       => esc_html__( 'Exquisite Design', 'hive-lite' ),
					'description' => esc_html__( 'Design is a great way to share appealing stories. Hive helps you to become a better storyteller into the digital world. Thanks to a very human approach in terms of interaction, a gentle and eye-candy typography and stylish details, you can definitely reach the right audience.', 'hive-lite' ),
					'is_in_lite'  => 'true',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => esc_html__( 'Mobile-Ready and Responsive for All Devices', 'hive-lite' ),
					'description' => esc_html__( 'One of the perks of living these days is the tremendous chance to stay connected with everything you love without boundaries. That’s why SIlk is mobile-ready and facilitates your users to easily enjoy your content, no matter the device they like to use on a daily basis.', 'hive-lite' ),
					'is_in_lite'  => 'true',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => esc_html__( 'Social Integration', 'hive-lite' ),
					'description' => esc_html__( 'Let your voice be heard by the right people. Aim to build a strong community around your fashion blog and start a smart dialogue with those who admire your work. Facebook, Twitter, Instagram, you name it, but be aware that all can boost your content and increase awareness.', 'hive-lite' ),
					'is_in_lite'  => 'true',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => esc_html__( 'Personalize to Match Your Style', 'hive-lite' ),
					'description' => esc_html__( 'Having different tastes and preferences might be tricky for users, but not with Hive onboard. It has an intuitive and catchy interface which allows you to change fonts, colors or layout sizes in a blink of an eye.', 'hive-lite' ),
					'is_in_lite'  => 'false',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => esc_html__( 'Featured Posts Slider', 'hive-lite' ),
					'description' => esc_html__( 'Showcase the latest posts from a category under menu without losing precious time and money. Highlight those articles you feel proud about with no effort and let people know about your appetite for a topic or another.', 'hive-lite' ),
					'is_in_lite'  => 'false',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => __( 'Support Best-In-Business', 'hive-lite' ),
					'description' => __( 'You will benefit by priority support from a caring and devoted team, eager to help and to spread happiness. We work hard to provide a flawless experience for those who vote us with trust and choose to be our special clients.','hive-lite' ),
					'is_in_lite'  => 'false',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => esc_html__( 'Comprehensive Help Guide', 'hive-lite' ),
					'description' => esc_html__( 'Extensive documentation that will help you get your site up quickly and seamlessly.', 'hive-lite' ),
					'is_in_lite'  => 'false',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => esc_html__( 'No credit footer link', 'hive-lite' ),
					'description' => esc_html__( 'Remove "Theme: Hive Lite by Pixelgrade" copyright from the footer area.', 'hive-lite' ),
					'is_in_lite'  => 'false',
					'is_in_pro'   => 'true',
				)
			),
		),
		// Plugins array.
		'recommended_plugins'        => array(
			'already_activated_message' => esc_html__( 'Already activated', 'hive-lite' ),
			'version_label' => esc_html__( 'Version: ', 'hive-lite' ),
			'install_label' => esc_html__( 'Install and Activate', 'hive-lite' ),
			'activate_label' => esc_html__( 'Activate', 'hive-lite' ),
			'deactivate_label' => esc_html__( 'Deactivate', 'hive-lite' ),
			'content'                   => array(
				array(
					'slug' => 'jetpack'
				),
				array(
					'slug' => 'wordpress-seo'
				),
//				array(
//					'slug' => 'gridable'
//				)
			),
		),
		// Required actions array.
		'recommended_actions'        => array(
			'install_label' => esc_html__( 'Install and Activate', 'hive-lite' ),
			'activate_label' => esc_html__( 'Activate', 'hive-lite' ),
			'deactivate_label' => esc_html__( 'Deactivate', 'hive-lite' ),
			'content'            => array(
				'jetpack' => array(
					'title'       => 'Jetpack',
					'description' => __( 'It is highly recommended that you install Jetpack so you can enable the <b>Portfolio</b> content type for adding and managing your projects. Plus, Jetpack provides a whole host of other useful things for you site.', 'hive-lite' ),
					'check'       => defined( 'JETPACK__VERSION' ),
					'plugin_slug' => 'jetpack',
					'id' => 'jetpack'
				),
			),
		),
	);
	TI_About_Page::init( $config );
}
add_action('after_setup_theme', 'hivelite_admin_setup');
