<?php
/**
 * Hive functions and definitions
 *
 * @package Hive
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 940; /* pixels */
}

if ( ! function_exists( 'hive_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function hive_setup() {

		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 */
		load_theme_textdomain( 'hive_txtd', get_template_directory() . '/languages' );

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
			'primary'   => __( 'Primary Menu', 'hive_txtd' ),
			'footer'    => __( 'Footer Menu', 'hive_txtd' ),
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
		 * Add editor custom style to make it look more like the frontend
		 * Also enqueue the custom Google Fonts also
		 */
		add_editor_style( array( 'editor-style.css', hive_fonts_url() ) );

		/*
		 * Now some cleanup to remove features that we do not support
		 */
		remove_theme_support( 'custom-header' );
	}
endif; // hive_setup

add_action( 'after_setup_theme', 'hive_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function hive_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'hive_txtd' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}

add_action( 'widgets_init', 'hive_widgets_init' );

/**
 * Filter the post titles
 *
 * Hooked to wp_loaded because we need to have access to theme mods
 */
function hive_filter_post_titles() {
	//make ultra mega nice post titles only if we are allowed to from Customizer > Theme Options
	if ( ! get_theme_mod( 'hive_disable_autostyle_titles' , false ) ) {
		add_filter( 'the_title', 'hive_auto_style_title' );
	}
}

add_action( 'loop_start', 'hive_filter_post_titles' );

/**
 * Enqueue scripts and styles.
 */
function hive_scripts_styles() {

	//Customizer Stylesheet
	wp_enqueue_style( 'hivelite_customizer_style', get_template_directory_uri() . '/assets/css/admin/customizer.css', array(), '1.0.0', false );

	//Main Stylesheet
	wp_enqueue_style( 'hive-style', get_stylesheet_uri(), array() );

	//Default Fonts
	wp_enqueue_style( 'hive-fonts', hive_fonts_url(), array(), null );

	//Enqueue jQuery
	wp_enqueue_script( 'jquery' );

	//Enqueue Masonry
	wp_enqueue_script( 'masonry' );

	//Enqueue ImagesLoaded plugin
	wp_enqueue_script( 'hive-imagesloaded', get_stylesheet_directory_uri() . '/assets/js/imagesloaded.js', array(), '3.1.8', true );

	//Enqueue HoverIntent plugin
	wp_enqueue_script( 'hive-hoverintent', get_stylesheet_directory_uri() . '/assets/js/jquery.hoverIntent.js', array( 'jquery' ), '1.8.0', true );

	//Enqueue Velocity.js plugin
	wp_enqueue_script( 'hive-velocity', get_stylesheet_directory_uri() . '/assets/js/velocity.js', array(), '1.1.0', true );

	//Enqueue Hive Custom Scripts
	wp_enqueue_script( 'hive-scripts', get_stylesheet_directory_uri() . '/assets/js/main.js', array( 'jquery', 'masonry', 'hive-imagesloaded', 'hive-hoverintent', 'hive-velocity' ), '1.0.0', true );

	if( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

add_action( 'wp_enqueue_scripts', 'hive_scripts_styles' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load the Hybrid Media Grabber class
 */
require get_template_directory() . '/inc/hive-hybrid-media-grabber.php';

/**
 * Theme About page.
 */
require get_template_directory() . '/inc/admin/about-page.php'; ?>