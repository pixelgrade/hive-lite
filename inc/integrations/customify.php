<?php
/**
 * Add extra controls in the Customizer
 *
 * @package Hive Lite
 */

/**
 * Hook into the Customify's fields and settings.
 *
 * The config can turn to be complex so is best to visit:
 * https://github.com/pixelgrade/customify
 *
 * @param array $options Contains the plugin's options array right before they are used, so edit with care
 *
 * @return array The returned options are required, if you don't need options return an empty array
 */
add_filter( 'customify_filter_fields', 'hive_add_customify_options', 11, 1 );
add_filter( 'customify_filter_fields', 'pixelgrade_add_customify_style_manager_section', 12, 1 );

add_filter( 'customify_filter_fields', 'hive_modify_customify_options', 20 );

// Color Constants
define( 'SM_COLOR_PRIMARY', '#ffeb00' );
define( 'SM_COLOR_SECONDARY', '#cae00f' );
define( 'SM_COLOR_TERTIARY', '#bbd916' );

define( 'SM_DARK_PRIMARY', '#161a03' );
define( 'SM_DARK_SECONDARY', '#2a2c29' );
define( 'SM_DARK_TERTIARY', '#7e8073' );

define( 'SM_LIGHT_PRIMARY', '#ffffff' );
define( 'SM_LIGHT_SECONDARY', '#fcfcf5' );
define( 'SM_LIGHT_TERTIARY', '#f4f7e6' );


function hive_add_customify_options( $options ) {
	$options['opt-name'] = 'hive_options';

	//start with a clean slate - no Customify default sections
	$options['sections'] = array();

	return $options;
}

/**
 * Add the Style Manager cross-theme Customizer section.
 *
 * @param array $options
 *
 * @return array
 */
function pixelgrade_add_customify_style_manager_section( $options ) {
	// If the theme hasn't declared support for style manager, bail.
	if ( ! current_theme_supports( 'customizer_style_manager' ) ) {
		return $options;
	}

	if ( ! isset( $options['sections']['style_manager_section'] ) ) {
		$options['sections']['style_manager_section'] = array();
	}

	// The section might be already defined, thus we merge, not replace the entire section config.
	$options['sections']['style_manager_section'] = array_replace_recursive( $options['sections']['style_manager_section'], array(
		'options' => array(

			// Color Palettes Assignment.
			'sm_color_primary' => array(
				'connected_fields' => array(
					'accent_color',
				),
				'default' => SM_COLOR_PRIMARY,
			),
			'sm_color_secondary' => array(
				'default' => SM_COLOR_SECONDARY,
			),
			'sm_color_tertiary' => array(
				'default' => SM_COLOR_TERTIARY,
			),
			'sm_dark_primary' => array(
				'default' => SM_DARK_PRIMARY,
			),
			'sm_dark_secondary' => array(
				'connected_fields' => array(
					'border_color',
					'header_links_active_color',

					'hive_blog_grid_primary_meta_color',
					'main_content_heading_1_color',
					'hive_blog_grid_item_title_color',

					'header_navigation_links_color',
					'main_content_heading_2_color',
					'main_content_heading_3_color',
					'main_content_heading_4_color',
					'main_content_heading_5_color',
					'body_color',
				),
				'default' => SM_DARK_SECONDARY,
			),
			'sm_dark_tertiary' => array(
				'connected_fields' => array(
					'hive_blog_grid_secondary_meta_color',
					'body_link_color',
				),
				'default' => SM_DARK_TERTIARY,
			),
			'sm_light_primary' => array(
				'connected_fields' => array(
					'body_background_color',
					'hive_footer_body_text_color',
					'hive_footer_links_color',
				),
				'default' => SM_LIGHT_PRIMARY,
			),
			'sm_light_secondary' => array(
				'default' => SM_LIGHT_SECONDARY,
			),
			'sm_light_tertiary' => array(
				'default' => SM_LIGHT_TERTIARY,
			),
		),
	) );

	return $options;
}

/**
 * Modify the Customify config.
 *
 * @param array $options The whole Customify config.
 *
 * @return array The modified Customify config.
 */
function hive_modify_customify_options( $options ) {

	$options['sections'] = array_replace_recursive( $options['sections'], array(

		'header_section'       => array(
			'title'   => esc_html__( 'Header', 'hive' ),
			'options' => array(
				// [Section] COLORS
				'header_title_colors_section'    => array(
					'type' => 'html',
					'html' => '<span id="section-title-header-colors" class="separator section label large">&#x1f3a8; ' . esc_html__( 'Colors', 'hive' ) . '</span>',
				),
				'header_navigation_links_color'       => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Navigation Links Color', 'hive' ),
					'live'    => true,
					'default' => SM_DARK_SECONDARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '.nav--main a',
						),
					),
				),
				'header_links_active_color'           => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Links Active Color', 'hive' ),
					'live'    => true,
					'default' => SM_DARK_SECONDARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '.nav--main > li:hover > a, .nav--main li.active > a',
						),
						array(
							'property' => 'background-color',
							'selector' => '.nav--main > li > a:before',
						),
					),
				),
			),
		),
		'main_content_section' => array(
			'title'   => esc_html__( 'Main Content', 'hive' ),
			'options' => array(
				// [Section] COLORS
				'main_content_title_colors_section'    => array(
					'type' => 'html',
					'html' => '<span id="section-title-header-colors" class="separator section label large">&#x1f3a8; ' . esc_html__( 'Colors', 'hive' ) . '</span>',
				),
				'border_color'                              => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Border Color', 'hive' ),
					'live'    => true,
					'default' => SM_DARK_SECONDARY,
					'css'     => array(
						array(
							'selector' => 'body:before, body:after',
							'media'    => 'screen and (min-width: 1000px)',
							'property' => 'background',
						),
						array(
							'selector' => 'div#infinite-footer, .site-footer',
							'property' => 'background-color',
						),
					),
				),
				'body_color'                                => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Body Text Color', 'hive' ),
					'live'    => true,
					'default' => SM_DARK_SECONDARY,
					'css'     => array(
						array(
							'selector' => '
								body,
								.entry-title a,
								.nav--toolbar a:before,
								.site-title a,
								.widget a,
								.comment__author-name a,
								a:hover',
							'property' => 'color',
						),
						array(
							'selector' => '
								.widget .post-date,
								.recentcomments,
								.single .entry-footer a,
								.page .entry-footer a,
								.comment__content',
							'property' => 'color',
							'callback_filter' => 'hive_color_opacity_adjust_cb'
						),
						array(
							'selector' => '
								.comment-number,
								.comments-area:after,
								.comment-number--dark,
								.comment-reply-title:before,
								.add-comment .add-comment__button,
								.comment__timestamp,

								.wp-caption-text,
								.single .entry-featured__caption,
								.page .entry-featured__caption,

								.comment-edit-link,
								.comment-reply-link,

								.single .entry-content:before,
								.page .entry-content:before',
							'property' => 'color',
							'callback_filter' => 'hive_color_opacity_darker_cb'
						),
						array(
							'selector' => '
								.site-description:after,
								li.comment .children li .comment-number,
								li.pingback .children li .comment-number,
								li.trackback .children li .comment-number',
							'property' => 'background-color',
							'callback_filter' => 'hive_color_opacity_darker_cb'
						),
						array(
							'selector' => '
								.nav--toolbar a:hover,
								blockquote:after,
								input,
								textarea',
							'property' => 'border-color',
							'callback_filter' => 'hive_color_opacity_adjust_cb'
						),
						array(
							'selector' => '
								.comment-number,
								.comments-area:after,
								.comment-number--dark,
								.comment-reply-title:before,
								.add-comment .add-comment__button,
								.comment-form-comment textarea,
								.comment-subscription-form textarea,
								.comment-form input,
								.comment-form textarea',
							'property' => 'border-color',
							'callback_filter' => 'hive_color_opacity_darker_cb'
						),
						array(
							'selector' => '.comments-area',
							'property' => 'border-top-color',
							'callback_filter' => 'hive_color_opacity_adjust_cb'
						),
						array(
							'selector' => '
								.btn,
								.btn:hover,
								.btn:active,
								.btn:focus,
								input[type="submit"],
								input[type="submit"]:hover,
								input[type="submit"]:active,
								input[type="submit"]:focus,
								div#infinite-handle button,
								div#infinite-handle button:hover,
								div#infinite-handle button:active,
								div#infinite-handle button:focus,

								.comment-number--dark[class],
								.comments-area:after,
								.comment-reply-title:before,
								.add-comment .add-comment__button,

								.archive__grid .entry-thumbnail .hover__bg,
								.pagination span.current',
							'property' => 'background-color',
						),
						array(
							'selector' => '.nav--main ul',
							'property' => 'background-color',
							'media' => 'only screen and (min-width: 1000px)'
						),
						array(
							'selector' => '.nav--main .menu-item-has-children > a:after',
							'property' => 'border-bottom-color',
						),
//						array(
//							'selector' => '
//								.edit-post-visual-editor[class],
//								.editor-post-title__block .editor-post-title__input[class],
//								.edit-post-visual-editor[class] a:hover',
//							'property' => 'color',
//							'editor' => true,
//						),
//						array(
//							'selector' => '.edit-post-visual-editor[class] blockquote:after',
//							'property' => 'border-color',
//							'editor' => true,
//						),
					),
				),
				'body_background_color'                                => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Body Background Color', 'hive' ),
					'live'    => true,
					'default' => SM_LIGHT_PRIMARY,
					'css'     => array(
						array(
							'selector' => '
								body,
								.entry-meta,
								.nav--main,
								.site-description-text,
								blockquote:before,
								.nav--toolbar a:hover,
								.overlay--search,

								select,
								textarea,
								input[type="text"],
								input[type="password"],
								input[type="datetime"],
								input[type="datetime-local"],
								input[type="date"],
								input[type="month"],
								input[type="time"],
								input[type="week"],
								input[type="number"],
								input[type="email"],
								input[type="url"],
								input[type="search"],
								input[type="tel"],
								input[type="color"],
								.form-control,

								.comment-number,
								.comments-area:after,
								.add-comment .add-comment__button,

								.archive__grid .entry-thumbnail .hover__line',
							'property' => 'background-color',
						),
						array(
							'selector' => '
								.btn,
								.btn:hover,
								.btn:active,
								.btn:focus,
								input[type="submit"],
								input[type="submit"]:hover,
								input[type="submit"]:active,
								input[type="submit"]:focus,
								div#infinite-handle button,
								div#infinite-handle button:hover,
								div#infinite-handle button:active,
								div#infinite-handle button:focus,

								.comment-number--dark,
								.comments-area:after,
								.comment-reply-title:before,
								.add-comment .add-comment__button,

								.archive__grid .entry-thumbnail .hover,
								.content-quote blockquote,
								.pagination span.current',
							'property' => 'color',
						),
						array(
							'selector' => '.nav--main ul a',
							'property' => 'color',
							'media' => 'only screen and (min-width: 1000px)',
						),
						array(
							'selector' => '
								.nav--main li ul,
								.nav--main ul > li ul',
							'property' => 'background-color',
							'media' => 'not screen and (min-width: 1000px)',
						),
//						array(
//							'selector' => '
//								.edit-post-visual-editor[class],
//								.edit-post-visual-editor[class] blockquote:before',
//							'property' => 'background-color',
//							'editor' => true,
//						),
//						array(
//							'selector' => '.edit-post-visual-editor[class] pre:before',
//							'property' => 'color',
//							'editor' => true,
//						),
					),
				),
				'body_link_color'                           => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Body Link Color', 'hive' ),
					'live'    => true,
					'default' => SM_DARK_TERTIARY,
					'css'     => array(
						array(
							'selector' => 'a',
							'property' => 'color',
						),
					),
				),
				'accent_color'                              => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Accent Color', 'hive' ),
					'live'    => true,
					'default' => SM_COLOR_PRIMARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' =>
								'blockquote a:hover,
								.format-quote .edit-link a:hover,
								.content-quote blockquote:before,
								.widget a:hover,
								.widget_blog_subscription input[type="submit"],
								.widget_blog_subscription a:hover,
								blockquote a:hover,
								blockquote:after,
								.content-quote blockquote:after',
						),
						array(
							'property' => 'outline-color',
							'selector' =>
								'select:focus,
								textarea:focus,
								input[type="text"]:focus,
								input[type="password"]:focus,
								input[type="datetime"]:focus,
								input[type="datetime-local"]:focus,
								input[type="date"]:focus,
								input[type="month"]:focus,
								input[type="time"]:focus,
								input[type="week"]:focus,
								input[type="number"]:focus,
								input[type="email"]:focus,
								input[type="url"]:focus,
								input[type="search"]:focus,
								input[type="tel"]:focus,
								input[type="color"]:focus,
								.form-control:focus',
						),
						array(
							'property' => 'border-color',
							'selector' => '.widget_blog_subscription input[type="submit"]',
						),
						array(
							'property' => 'background',
							'selector' =>
								'.highlight,
								.archive__grid .accent-box,
								.sticky:after,
								.content-quote blockquote:after,
								.sticky:not(.format-quote):after',
						),
						array(
							'property'        => 'color',
							'selector'        => '.sticky, .sticky a, .sticky .posted-on a, .sticky .entry-title',
							'callback_filter' => 'hive_sticky_accent_callback',
						),
					),
				),

				// [Sub Section] Headings Color
				'main_content_title_headings_color_section' => array(
					'type' => 'html',
					'html' => '<span class="separator sub-section label">' . esc_html__( 'Headings Color', 'hive' ) . '</span>',
				),
				'main_content_heading_1_color'              => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Headings 1', 'hive' ),
					'live'    => true,
					'default' => SM_DARK_SECONDARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => 'h1, .dropcap',
						),
//						array(
//							'selector' => '
//								.edit-post-visual-editor[class] h1,
//								.edit-post-visual-editor[class] .dropcap',
//							'property' => 'color',
//							'editor' => true,
//						),
					),
				),
				'main_content_heading_2_color'              => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Headings 2', 'hive' ),
					'live'    => true,
					'default' => SM_DARK_SECONDARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => 'h2, blockquote',
						),
//						array(
//							'property' => 'color',
//							'selector' => '
//								.edit-post-visual-editor[class] h2,
//								.edit-post-visual-editor[class] blockquote',
//							'editor' => true,
//						),
					),
				),
				'main_content_heading_3_color'              => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Headings 3', 'hive' ),
					'live'    => true,
					'default' => SM_DARK_SECONDARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => 'h3',
						),
//						array(
//							'property' => 'color',
//							'selector' => '.edit-post-visual-editor[class] h3',
//							'editor' => true,
//						),
					),
				),
				'main_content_heading_4_color'              => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Headings 4', 'hive' ),
					'live'    => true,
					'default' => SM_DARK_SECONDARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => 'h4',
						),
//						array(
//							'property' => 'color',
//							'selector' => '.edit-post-visual-editor h4',
//							'editor' => true,
//						),
					),
				),
				'main_content_heading_5_color'              => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Headings 5', 'hive' ),
					'live'    => true,
					'default' => SM_DARK_SECONDARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => 'h5',
						),
//						array(
//							'property' => 'color',
//							'selector' => '.edit-post-visual-editor h5',
//							'editor' => true
//						),[class]
					),
				),
			),
		),
		'footer_section'       => array(
			'title'   => esc_html__( 'Footer', 'hive' ),
			'options' => array(
				// [Section] COLORS
				'footer_title_colors_section'    => array(
					'type' => 'html',
					'html' => '<span id="section-title-header-colors" class="separator section label large">&#x1f3a8; ' . esc_html__( 'Colors', 'hive' ) . '</span>',
				),
				'hive_footer_body_text_color'         => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Text Color', 'hive' ),
					'live'    => true,
					'default' => SM_LIGHT_PRIMARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '
								.site-footer,
								#infinite-footer .blog-info,
								#infinite-footer .blog-credits',
							'callback_filter' => 'hive_color_opacity_adjust_cb'
						),
					),
				),
				'hive_footer_links_color'             => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Links Color', 'hive' ),
					'live'    => true,
					'default' => SM_LIGHT_PRIMARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '
								.site-footer a,
								#infinite-footer .blog-info a,
								#infinite-footer .blog-credits a',
						),
					),
				),
			),
		),
		'blog_grid_section'    => array(
			'title'   => esc_html__( 'Blog Grid Items', 'hive' ),
			'options' => array(
				// [Section] COLORS
				'blog_grid_title_colors_section'    => array(
					'type' => 'html',
					'html' => '<span id="section-title-header-colors" class="separator section label large">&#x1f3a8; ' . esc_html__( 'Colors', 'hive' ) . '</span>',
				),
				'hive_blog_grid_item_title_color'        => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Item Title Color', 'hive' ),
					'live'    => true,
					'default' => SM_DARK_SECONDARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '.archive__grid .entry-title',
						),
					),
				),
				'hive_blog_grid_primary_meta_color'      => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Primary Meta Color', 'hive' ),
					'live'    => true,
					'default' => SM_DARK_SECONDARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '.entry-meta__primary,
								 .entry-meta__secondary:before,
								.entry-meta__secondary:hover > *,
								.single .posted-on,
								.single .posted-on:before,
								.single .posted-by,
								.single .cat-links:before,
								.page .posted-on,
								.page .posted-on:before,
								.page .posted-by,
								.page .cat-links:before,
								.single .cat-links:hover,
								.page .cat-links:hover',
						),
					),
				),
				'hive_blog_grid_secondary_meta_color'    => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Secondary Meta Color', 'hive' ),
					'live'    => true,
					'default' => SM_DARK_TERTIARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '.entry-meta__secondary,
										.entry-meta__primary:hover > *,
										.single .cat-links, .page .cat-links,
										.single .cat-links,
										.page .cat-links,
										.single .posted-on:hover,
										.single .posted-by:hover,
										.page .posted-on:hover,
										.page .posted-by:hover',
						),
					),
				),
			),
		),
	) );

	return $options;
}

if ( ! function_exists('hive_is_color_light') ) {
	/**
	 * Returns whether or not given color is considered "light"
	 * @param string|Boolean $color
	 * @return boolean
	 */
	function hive_is_color_light( $color = '#ffffff' ) {
		// Get our color
		$color = ($color) ? $color : '#ffffff';
		// Calculate straight from rbg
		$r = hexdec($color[0].$color[1]);
		$g = hexdec($color[2].$color[3]);
		$b = hexdec($color[4].$color[5]);
		return (( $r*299 + $g*587 + $b*114 )/1000 > 90);
	}
}

if ( ! function_exists( 'hive_sticky_accent_callback' ) ) {
	function hive_sticky_accent_callback( $value, $selector, $property, $unit ) {
		$output = $selector . '{' . $property . ': ' . ( hive_is_color_light( $value ) ? '#000000' : '#ffffff' ) . '; }';
		return $output;
	}
}

if ( ! function_exists('hive_color_opacity_adjust_cb') ) {
	function hive_color_opacity_adjust_cb( $value, $selector, $property, $unit ) {

		// Get our color
		if ( empty( $value ) || ! preg_match( '/^#[a-f0-9]{6}$/i', $value ) ) {
			return '';
		}

		$r = hexdec( $value[1] . $value[2] );
		$g = hexdec( $value[3] . $value[4] );
		$b = hexdec( $value[5] . $value[6] );

		// if it is not a dark color, just go for the default way
		$output = $selector . ' { ' . $property . ': rgba(' . $r . ',' . $g . ',' . $b . ', 0.3); }';

		return $output;
	}
}

if ( ! function_exists('hive_color_opacity_adjust_cb_customizer_preview') ) {

	function hive_color_opacity_adjust_cb_customizer_preview() {

		$js = "
	        function hexdec(hexString) {
				hexString = (hexString + '').replace(/[^a-f0-9]/gi, '');
				return parseInt(hexString, 16)
			}
			function hive_color_opacity_adjust_cb( value, selector, property, unit ) {

				var css = '',
					style = document.getElementById('hive_color_opacity_adjust_cb_style_tag_' + selector.replace(/\W/g, '') ),
					head = document.head || document.getElementsByTagName('head')[0],
					r = hexdec(value[1] + '' + value[2]),
					g = hexdec(value[3] + '' + value[4]),
					b = hexdec(value[5] + '' + value[6]);


				css += selector + ' { ' + property + ': rgba(' + r + ',' + g + ',' + b + ', 0.3); } ';

				if ( style !== null ) {
					style.innerHTML = css;
				} else {
					style = document.createElement('style');
					style.setAttribute('id', 'hive_color_opacity_adjust_cb_style_tag_' + selector.replace(/\W/g, '') );

					style.type = 'text/css';
					if ( style.styleSheet ) {
						style.styleSheet.cssText = css;
					} else {
						style.appendChild(document.createTextNode(css));
					}

					head.appendChild(style);
				}
			}" . PHP_EOL;

		wp_add_inline_script( 'customify-previewer-scripts', $js );
	}
	add_action( 'customize_preview_init', 'hive_color_opacity_adjust_cb_customizer_preview' );
}

if ( ! function_exists('hive_color_opacity_darker_cb') ) {
	function hive_color_opacity_darker_cb( $value, $selector, $property, $unit ) {

		// Get our color
		if ( empty( $value ) || ! preg_match( '/^#[a-f0-9]{6}$/i', $value ) ) {
			return '';
		}

		$r = hexdec( $value[1] . $value[2] );
		$g = hexdec( $value[3] . $value[4] );
		$b = hexdec( $value[5] . $value[6] );

		// if it is not a dark color, just go for the default way
		$output = $selector . ' { ' . $property . ': rgba(' . $r .',' . $g . ',' . $b .', 0.7); }' . PHP_EOL;

		return $output;
	}
}

if ( ! function_exists('hive_color_opacity_darker_cb_customizer_preview') ) {

	function hive_color_opacity_darker_cb_customizer_preview() {

		$js = "
	        function hexdec(hexString) {
				hexString = (hexString + '').replace(/[^a-f0-9]/gi, '');
				return parseInt(hexString, 16)
			}
			function hive_color_opacity_darker_cb( value, selector, property, unit ) {

				var css = '',
					style = document.getElementById('hive_color_opacity_darker_cb_style_tag_' + selector.replace(/\W/g, '') ),
					head = document.head || document.getElementsByTagName('head')[0],
					r = hexdec(value[1] + '' + value[2]),
					g = hexdec(value[3] + '' + value[4]),
					b = hexdec(value[5] + '' + value[6]);

				css += selector + ' { ' + property + ': rgba(' + r + ',' + g + ',' + b + ', 0.7); } ';

				if ( style !== null ) {
					style.innerHTML = css;
				} else {
					style = document.createElement('style');
					style.setAttribute('id', 'hive_color_opacity_darker_cb_style_tag_' + selector.replace(/\W/g, '') );

					style.type = 'text/css';
					if ( style.styleSheet ) {
						style.styleSheet.cssText = css;
					} else {
						style.appendChild(document.createTextNode(css));
					}

					head.appendChild(style);
				}
			}" . PHP_EOL;

		wp_add_inline_script( 'customify-previewer-scripts', $js );
	}
	add_action( 'customize_preview_init', 'hive_color_opacity_darker_cb_customizer_preview' );
}

function hive_add_default_color_palette( $color_palettes ) {

	$color_palettes = array_merge(array(
		'default' => array(
			'label' => 'Theme Default',
			'preview' => array(
				'background_image_url' => 'https://cloud.pixelgrade.com/wp-content/uploads/2018/05/patch-theme-palette.jpg',
			),
			'options' => array(
				'sm_color_primary' => SM_COLOR_PRIMARY,
				'sm_color_secondary' => SM_COLOR_SECONDARY,
				'sm_color_tertiary' => SM_COLOR_TERTIARY,
				'sm_dark_primary' => SM_DARK_PRIMARY,
				'sm_dark_secondary' => SM_DARK_SECONDARY,
				'sm_dark_tertiary' => SM_DARK_TERTIARY,
				'sm_light_primary' => SM_LIGHT_PRIMARY,
				'sm_light_secondary' => SM_LIGHT_SECONDARY,
				'sm_light_tertiary' => SM_LIGHT_TERTIARY,
			),
		),
	), $color_palettes);

	return $color_palettes;
}
add_filter( 'customify_get_color_palettes', 'hive_add_default_color_palette' );
