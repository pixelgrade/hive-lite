<?php
/**
 * Hive Lite functions and definitions
 *
 * @package Hive Lite
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 940; /* pixels */
}

if ( ! function_exists( 'hivelite_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function hivelite_setup() {

		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 */
		load_theme_textdomain( 'hive-lite', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		 */
		add_theme_support( 'post-thumbnails' );
		add_image_size( 'hive-masonry-image', 450, 9999, false );
		add_image_size( 'hive-single-image', 1024, 9999, false );

		// This theme uses wp_nav_menu() in three locations.
		register_nav_menus( array(
			'primary'   => esc_html__( 'Primary Menu', 'hive-lite' ),
			'footer'    => esc_html__( 'Footer Menu', 'hive-lite' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption'
		) );

		/*
		 * Enable support for custom logo.
		 *
		 */
		add_theme_support( 'custom-logo', array(
			'width'       => 1360,
			'height'      => 600,
			'flex-height' => true,
			'header-text' => array(
				'site-title',
				'site-description-text',
			)
		) );

		add_image_size( 'hive-site-logo', 1360, 600, false );

		/*
		 * Enable support for Post Formats.
		 * See http://codex.wordpress.org/Post_Formats
		 */
		add_theme_support( 'post-formats', array(
			'aside',
			'gallery',
			'image',
			'audio',
			'video',
			'quote',
			'link',
			'status',
			'chat'
		) );

		/*
		 * Add editor custom style to make it look more like the frontend
		 * Also enqueue the custom Google Fonts also
		 */
		add_editor_style( array( 'editor-style.css', hivelite_fonts_url() ) );

		/*
		 * Enable support for Visible Edit Shortcuts in the Customizer Preview
		 *
		 * @link https://make.wordpress.org/core/2016/11/10/visible-edit-shortcuts-in-the-customizer-preview/
		 */
		add_theme_support( 'customize-selective-refresh-widgets' );

		/*
		 * Now some cleanup to remove features that we do not support
		 */
		remove_theme_support( 'custom-header' );

		/**
		 * Enable support for the Style Manager Customizer section (via Customify).
		 */
		add_theme_support( 'customizer_style_manager' );
	}
endif;
add_action( 'after_setup_theme', 'hivelite_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function hivelite_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'hive-lite' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'hivelite_widgets_init' );

/**
 * Filter the post titles
 *
 * Hooked to wp_loaded because we need to have access to theme mods
 */
function hivelite_filter_post_titles() {
	//make ultra mega nice post titles only if we are allowed to from Customizer > Theme Options
	if ( ! get_theme_mod( 'hive_disable_autostyle_titles' , false ) ) {
		add_filter( 'the_title', 'hivelite_auto_style_title' );
	}
}
add_action( 'loop_start', 'hivelite_filter_post_titles' );

/**
 * Enqueue scripts and styles.
 */
function hivelite_scripts_styles() {
	$theme = wp_get_theme( get_template() );

	//Main Stylesheet
	wp_enqueue_style( 'hive-style', get_template_directory_uri() . '/style.css', array(), $theme->get( 'Version' ) );
	wp_style_add_data( 'hive-style', 'rtl', 'replace' );

	//Default Fonts
	wp_enqueue_style( 'hive-fonts', hivelite_fonts_url(), array(), null );

	// Register Velocity.js plugin
	wp_register_script( 'velocity', get_template_directory_uri() . '/assets/js/velocity.js', array(), '1.1.3', true );

	// Enqueue Hive Custom Scripts
	wp_enqueue_script( 'hive-scripts', get_template_directory_uri() . '/assets/js/main.js', array( 'jquery', 'masonry', 'hoverIntent', 'velocity' ), $theme->get( 'Version' ), true );

	if( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'hivelite_scripts_styles' );

function hive_lite_gutenberg_styles() {
	wp_enqueue_style( 'hive-lite-gutenberg', get_theme_file_uri( '/gutenberg.css' ), false );
	wp_enqueue_style( 'hive-lite-fonts', hivelite_fonts_url() );
}
add_action( 'enqueue_block_editor_assets', 'hive_lite_gutenberg_styles' );


/**
 * Custom template tags for this theme.
 */
require_once trailingslashit( get_template_directory() ) . 'inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require_once trailingslashit( get_template_directory() ) . 'inc/extras.php';

/**
 * Load the Hybrid Media Grabber class
 */
require_once trailingslashit( get_template_directory() ) . 'inc/hive-hybrid-media-grabber.php';

/**
 * Customizer additions.
 */
require_once trailingslashit( get_template_directory() ) . 'inc/customizer.php';

/**
 * Load various plugin integrations.
 */
require_once trailingslashit( get_template_directory() ) . 'inc/integrations.php';

/**
 * Admin dashboard related logic.
 */
require_once trailingslashit( get_template_directory() ) . 'inc/admin.php';
