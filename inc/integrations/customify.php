<?php
/**
 * Add extra controls in the Customizer via our Customify plugin.
 *
 * @link https://wordpress.org/plugins/customify/
 *
 * @package Hive Lite
 */

add_filter( 'customify_filter_fields', 'hivelite_add_customify_options', 11, 1 );
add_filter( 'customify_filter_fields', 'hivelite_add_customify_style_manager_section', 12, 1 );

add_filter( 'customify_filter_fields', 'hivelite_fill_customify_options', 20 );

// Color Constants
define( 'HIVELITE_SM_COLOR_PRIMARY', '#ffeb00' );
define( 'HIVELITE_SM_COLOR_SECONDARY', '#cae00f' );
define( 'HIVELITE_SM_COLOR_TERTIARY', '#bbd916' );

define( 'HIVELITE_SM_DARK_PRIMARY', '#161a03' );
define( 'HIVELITE_SM_DARK_SECONDARY', '#2a2c29' );
define( 'HIVELITE_SM_DARK_TERTIARY', '#7e8073' );

define( 'HIVELITE_SM_LIGHT_PRIMARY', '#ffffff' );
define( 'HIVELITE_SM_LIGHT_SECONDARY', '#fcfcf5' );
define( 'HIVELITE_SM_LIGHT_TERTIARY', '#f4f7e6' );


function hivelite_add_customify_options( $options ) {
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
function hivelite_add_customify_style_manager_section( $options ) {
	// If the theme hasn't declared support for style manager, bail.
	if ( ! current_theme_supports( 'customizer_style_manager' ) ) {
		return $options;
	}

	if ( ! isset( $options['sections']['style_manager_section'] ) ) {
		$options['sections']['style_manager_section'] = array();
	}

	$new_config = array(
		'options' => array(

			// Color Palettes Assignment.
			'sm_color_primary' => array(
				'connected_fields' => array(
					'accent_color',
				),
				'default' => HIVELITE_SM_COLOR_PRIMARY,
			),
			'sm_color_secondary' => array(
				'default' => HIVELITE_SM_COLOR_SECONDARY,
			),
			'sm_color_tertiary' => array(
				'default' => HIVELITE_SM_COLOR_TERTIARY,
			),
			'sm_dark_primary' => array(
				'default' => HIVELITE_SM_DARK_PRIMARY,
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
				'default' => HIVELITE_SM_DARK_SECONDARY,
			),
			'sm_dark_tertiary' => array(
				'connected_fields' => array(
					'hive_blog_grid_secondary_meta_color',
					'body_link_color',
				),
				'default' => HIVELITE_SM_DARK_TERTIARY,
			),
			'sm_light_primary' => array(
				'connected_fields' => array(
					'body_background_color',
					'hive_footer_body_text_color',
					'hive_footer_links_color',
				),
				'default' => HIVELITE_SM_LIGHT_PRIMARY,
			),
			'sm_light_secondary' => array(
				'default' => HIVELITE_SM_LIGHT_SECONDARY,
			),
			'sm_light_tertiary' => array(
				'default' => HIVELITE_SM_LIGHT_TERTIARY,
			),
		),
	);

	// The section might be already defined, thus we merge, not replace the entire section config.
	if ( class_exists( 'Customify_Array' ) && method_exists( 'Customify_Array', 'array_merge_recursive_distinct' ) ) {
		$options['sections']['style_manager_section'] = Customify_Array::array_merge_recursive_distinct( $options['sections']['style_manager_section'], $new_config );
	} else {
		$options['sections']['style_manager_section'] = array_merge_recursive( $options['sections']['style_manager_section'], $new_config );
	}

	return $options;
}

/**
 * Fill the Customify config.
 *
 * @param array $options The whole Customify config.
 *
 * @return array The modified Customify config.
 */
function hivelite_fill_customify_options( $options ) {

	$new_config = array(

		'header_section'       => array(
			'title'   => '',
			'type'    => 'hidden',
			'options' => array(

				// [Section] COLORS
				'header_navigation_links_color'       => array(
					'type'    => 'hidden_control',
					'live'    => true,
					'default' => HIVELITE_SM_DARK_SECONDARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '.nav--main a',
						),
					),
				),
				'header_links_active_color'           => array(
					'type'    => 'hidden_control',
					'live'    => true,
					'default' => HIVELITE_SM_DARK_SECONDARY,
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
			'title'   => '',
			'type'    => 'hidden',
			'options' => array(

				// [Section] COLORS
				'border_color'                              => array(
					'type'    => 'hidden_control',
					'live'    => true,
					'default' => HIVELITE_SM_DARK_SECONDARY,
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
					'type'    => 'hidden_control',
					'live'    => true,
					'default' => HIVELITE_SM_DARK_SECONDARY,
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
							'callback_filter' => 'hivelite_color_opacity_adjust_cb'
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
							'callback_filter' => 'hivelite_color_opacity_darker_cb'
						),
						array(
							'selector' => '
								.site-description:after,
								li.comment .children li .comment-number,
								li.pingback .children li .comment-number,
								li.trackback .children li .comment-number',
							'property' => 'background-color',
							'callback_filter' => 'hivelite_color_opacity_darker_cb'
						),
						array(
							'selector' => '
								.nav--toolbar a:hover,
								blockquote:after,
								input,
								textarea',
							'property' => 'border-color',
							'callback_filter' => 'hivelite_color_opacity_adjust_cb'
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
							'callback_filter' => 'hivelite_color_opacity_darker_cb'
						),
						array(
							'selector' => '.comments-area',
							'property' => 'border-top-color',
							'callback_filter' => 'hivelite_color_opacity_adjust_cb'
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
								.pagination span.current,
								.c-burger__slice',
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
					),
				),
				'body_background_color'                                => array(
					'type'    => 'hidden_control',
					'live'    => true,
					'default' => HIVELITE_SM_LIGHT_PRIMARY,
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
					),
				),
				'body_link_color'                           => array(
					'type'    => 'hidden_control',
					'live'    => true,
					'default' => HIVELITE_SM_DARK_TERTIARY,
					'css'     => array(
						array(
							'selector' =>
								'a,
								.posted-on a, .posted-by a, .entry-title a,
								.page-title--search span',
							'property' => 'color',
						),
					),
				),
				'accent_color'                              => array(
					'type'    => 'hidden_control',
					'live'    => true,
					'default' => HIVELITE_SM_COLOR_PRIMARY,
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
							'selector'        => '.sticky[class], .sticky a:hover',
							'callback_filter' => 'hivelite_color_contrast',
						),
					),
				),

				// [Sub Section] Headings Color
				'main_content_heading_1_color'              => array(
					'type'    => 'hidden_control',
					'live'    => true,
					'default' => HIVELITE_SM_DARK_SECONDARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => 'h1, .dropcap',
						),
					),
				),
				'main_content_heading_2_color'              => array(
					'type'    => 'hidden_control',
					'live'    => true,
					'default' => HIVELITE_SM_DARK_SECONDARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => 'h2, blockquote',
						),
					),
				),
				'main_content_heading_3_color'              => array(
					'type'    => 'hidden_control',
					'live'    => true,
					'default' => HIVELITE_SM_DARK_SECONDARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => 'h3',
						),
					),
				),
				'main_content_heading_4_color'              => array(
					'type'    => 'hidden_control',
					'live'    => true,
					'default' => HIVELITE_SM_DARK_SECONDARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => 'h4',
						),
					),
				),
				'main_content_heading_5_color'              => array(
					'type'    => 'hidden_control',
					'live'    => true,
					'default' => HIVELITE_SM_DARK_SECONDARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => 'h5',
						),
					),
				),
			),
		),
		'footer_section'       => array(
			'title'   => '',
			'type'    => 'hidden',
			'options' => array(
				// [Section] COLORS
				'hive_footer_body_text_color'         => array(
					'type'    => 'hidden_control',
					'live'    => true,
					'default' => HIVELITE_SM_LIGHT_PRIMARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '
								.site-footer,
								#infinite-footer .blog-info,
								#infinite-footer .blog-credits',
							'callback_filter' => 'hivelite_color_opacity_adjust_cb'
						),
					),
				),
				'hive_footer_links_color'             => array(
					'type'    => 'hidden_control',
					'live'    => true,
					'default' => HIVELITE_SM_LIGHT_PRIMARY,
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
			'title'   => '',
			'type'    => 'hidden',
			'options' => array(
				// [Section] COLORS
				'hive_blog_grid_item_title_color'        => array(
					'type'    => 'hidden_control',
					'live'    => true,
					'default' => HIVELITE_SM_DARK_SECONDARY,
					'css'     => array(
						array(
							'property' => 'color',
						),
					),
				),
				'hive_blog_grid_primary_meta_color'      => array(
					'type'    => 'hidden_control',
					'live'    => true,
					'default' => HIVELITE_SM_DARK_SECONDARY,
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
					'type'    => 'hidden_control',
					'live'    => true,
					'default' => HIVELITE_SM_DARK_TERTIARY,
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
	);

	if ( class_exists( 'Customify_Array' ) && method_exists( 'Customify_Array', 'array_merge_recursive_distinct' ) ) {
		$options['sections'] = Customify_Array::array_merge_recursive_distinct( $options['sections'], $new_config );
	} else {
		$options['sections'] = array_merge_recursive( $options['sections'], $new_config );
	}

	return $options;
}

if ( ! function_exists( 'hivelite_color_contrast' ) ) {
	function hivelite_color_contrast( $hex, $selector, $property, $unit ) {

		// Get our color
		if( empty($hex) || ! preg_match('/^#[a-f0-9]{6}$/i', $hex)) {
			return '';
		}

		// Format the hex color string.
		$hex = str_replace( '#', '', $hex );

		if ( 3 == strlen( $hex ) ) {
			$hex = str_repeat( substr( $hex, 0, 1 ), 2 ) . str_repeat( substr( $hex, 1, 1 ), 2 ) . str_repeat( substr( $hex, 2, 1 ), 2 );
		}

		// Get decimal values.
		$r = hexdec( substr( $hex, 0, 2 ) );
		$g = hexdec( substr( $hex, 2, 2 ) );
		$b = hexdec( substr( $hex, 4, 2 ) );

		$uicolors = array( $r / 255, $g / 255, $b / 255 );

		$c = array_map( function( $col ) {
			if ( $col <= 0.03928 ) {
				return $col / 12.92;
			}
			return pow( ( $col + 0.055 ) / 1.055, 2.4 );
		}, $uicolors );

		$L = ( 0.2126 * $c[0] ) + ( 0.7152 * $c[1] ) + ( 0.0722 * $c[2] );

		// if it is not a dark color, just go for the default way
		$color = ( $L > 0.143 ) ? '#000' : '#FFF';

		$output = $selector . ' {
			color: ' . $color .';
        }';

		return $output;
	}
}

if ( ! function_exists('hivelite_color_contrast_customizer_preview') ) {
	function hivelite_color_contrast_customizer_preview() {
		$js = "
            function hivelite_color_contrast(value, selector, property, unit) {
            
                var css = '',
                    style = document.getElementById( 'hivelite_color_contrast_style_tag' ),
                    head = document.head || document.getElementsByTagName('head')[0];
                    
                var hex = value.substring( 1 );  // strip #
                var rgb = parseInt( hex, 16 );   // convert rrggbb to decimal
                var r = ( rgb >> 16 ) & 0xff;  // extract red
                var g = ( rgb >>  8 ) & 0xff;  // extract green
                var b = ( rgb >>  0 ) & 0xff;  // extract blue
                var uicolors = [r / 255, g / 255, b / 255];
                
                var c = uicolors.map( function(col) {
                    if ( col <= 0.03928 ) {
                        return col / 12.92;
                    }
                    return Math.pow( ( col + 0.055 ) / 1.055, 2.4 );
                } );
                
                var L = ( 0.2126 * c[0] ) + ( 0.7152 * c[1] ) + ( 0.0722 * c[2] );
                var color = ( L > 0.179 ) ? '#000' : '#FFF';
                
                css = selector + ' { ' +
                    property + ': ' + color + '; ' +
                '}'; 
                
                if ( style !== null ) {
                    style.innerHTML = css;
                } else {
                    style = document.createElement( 'style' );
                    style.setAttribute( 'id', 'hivelite_color_contrast_style_tag' );

                    style.type = 'text/css';
                    if ( style.styleSheet ) {
                        style.styleSheet.cssText = css;
                    } else {
                        style.appendChild( document.createTextNode( css ) );
                    }

                    head.appendChild( style );
                }
            }" . "\n";

		wp_add_inline_script( 'customify-previewer-scripts', $js );
	}
}
add_action( 'customize_preview_init', 'hivelite_color_contrast_customizer_preview', 20 );

if ( ! function_exists('hivelite_color_opacity_adjust_cb') ) {
	function hivelite_color_opacity_adjust_cb( $hex, $selector, $property, $unit ) {

		// Get our color
		if ( empty( $hex ) || ! preg_match( '/^#[a-f0-9]{6}$/i', $hex ) ) {
			return '';
		}

		// Format the hex color string.
		$hex = str_replace( '#', '', $hex );

		if ( 3 == strlen( $hex ) ) {
			$hex = str_repeat( substr( $hex, 0, 1 ), 2 ) . str_repeat( substr( $hex, 1, 1 ), 2 ) . str_repeat( substr( $hex, 2, 1 ), 2 );
		}

		// Get decimal values.
		$r = hexdec( substr( $hex, 0, 2 ) );
		$g = hexdec( substr( $hex, 2, 2 ) );
		$b = hexdec( substr( $hex, 4, 2 ) );

		$output = $selector . ' { ' . $property . ': rgba(' . $r . ',' . $g . ',' . $b . ', 0.3); }';

		return $output;
	}
}

if ( ! function_exists('hivelite_color_opacity_adjust_cb_customizer_preview') ) {

	function hivelite_color_opacity_adjust_cb_customizer_preview() {

		$js = "
	        function hexdec(hexString) {
				hexString = (hexString + '').replace(/[^a-f0-9]/gi, '');
				return parseInt(hexString, 16)
			}
			function hivelite_color_opacity_adjust_cb( value, selector, property, unit ) {

				var css = '',
					style = document.getElementById('hivelite_color_opacity_adjust_cb_style_tag_' + selector.replace(/\W/g, '') ),
					head = document.head || document.getElementsByTagName('head')[0],
					r = hexdec(value[1] + '' + value[2]),
					g = hexdec(value[3] + '' + value[4]),
					b = hexdec(value[5] + '' + value[6]);


				css += selector + ' { ' + property + ': rgba(' + r + ',' + g + ',' + b + ', 0.3); } ';

				if ( style !== null ) {
					style.innerHTML = css;
				} else {
					style = document.createElement('style');
					style.setAttribute('id', 'hivelite_color_opacity_adjust_cb_style_tag_' + selector.replace(/\W/g, '') );

					style.type = 'text/css';
					if ( style.styleSheet ) {
						style.styleSheet.cssText = css;
					} else {
						style.appendChild(document.createTextNode(css));
					}

					head.appendChild(style);
				}
			}" . "\n";

		wp_add_inline_script( 'customify-previewer-scripts', $js );
	}
}
add_action( 'customize_preview_init', 'hivelite_color_opacity_adjust_cb_customizer_preview' );

if ( ! function_exists('hivelite_color_opacity_darker_cb') ) {
	function hivelite_color_opacity_darker_cb( $hex, $selector, $property, $unit ) {

		// Get our color
		if ( empty( $hex ) || ! preg_match( '/^#[a-f0-9]{6}$/i', $hex ) ) {
			return '';
		}

		// Format the hex color string.
		$hex = str_replace( '#', '', $hex );

		if ( 3 == strlen( $hex ) ) {
			$hex = str_repeat( substr( $hex, 0, 1 ), 2 ) . str_repeat( substr( $hex, 1, 1 ), 2 ) . str_repeat( substr( $hex, 2, 1 ), 2 );
		}

		// Get decimal values.
		$r = hexdec( substr( $hex, 0, 2 ) );
		$g = hexdec( substr( $hex, 2, 2 ) );
		$b = hexdec( substr( $hex, 4, 2 ) );

		$output = $selector . ' { ' . $property . ': rgba(' . $r .',' . $g . ',' . $b .', 0.7); }' . "\n";

		return $output;
	}
}

if ( ! function_exists('hivelite_color_opacity_darker_cb_customizer_preview') ) {

	function hivelite_color_opacity_darker_cb_customizer_preview() {

		$js = "
	        function hexdec(hexString) {
				hexString = (hexString + '').replace(/[^a-f0-9]/gi, '');
				return parseInt(hexString, 16)
			}
			function hivelite_color_opacity_darker_cb( value, selector, property, unit ) {

				var css = '',
					style = document.getElementById('hivelite_color_opacity_darker_cb_style_tag_' + selector.replace(/\W/g, '') ),
					head = document.head || document.getElementsByTagName('head')[0],
					r = hexdec(value[1] + '' + value[2]),
					g = hexdec(value[3] + '' + value[4]),
					b = hexdec(value[5] + '' + value[6]);

				css += selector + ' { ' + property + ': rgba(' + r + ',' + g + ',' + b + ', 0.7); } ';

				if ( style !== null ) {
					style.innerHTML = css;
				} else {
					style = document.createElement('style');
					style.setAttribute('id', 'hivelite_color_opacity_darker_cb_style_tag_' + selector.replace(/\W/g, '') );

					style.type = 'text/css';
					if ( style.styleSheet ) {
						style.styleSheet.cssText = css;
					} else {
						style.appendChild(document.createTextNode(css));
					}

					head.appendChild(style);
				}
			}" . "\n";

		wp_add_inline_script( 'customify-previewer-scripts', $js );
	}
}
add_action( 'customize_preview_init', 'hivelite_color_opacity_darker_cb_customizer_preview' );

function hivelite_add_default_color_palette( $color_palettes ) {

	$color_palettes = array_merge(array(
		'default' => array(
			'label' => esc_html__( 'Theme Default', 'hive-lite' ),
			'preview' => array(
				'background_image_url' => get_template_directory_uri() . '/assets/images/hive-theme-palette.jpg',
			),
			'options' => array(
				'sm_color_primary' => HIVELITE_SM_COLOR_PRIMARY,
				'sm_color_secondary' => HIVELITE_SM_COLOR_SECONDARY,
				'sm_color_tertiary' => HIVELITE_SM_COLOR_TERTIARY,
				'sm_dark_primary' => HIVELITE_SM_DARK_PRIMARY,
				'sm_dark_secondary' => HIVELITE_SM_DARK_SECONDARY,
				'sm_dark_tertiary' => HIVELITE_SM_DARK_TERTIARY,
				'sm_light_primary' => HIVELITE_SM_LIGHT_PRIMARY,
				'sm_light_secondary' => HIVELITE_SM_LIGHT_SECONDARY,
				'sm_light_tertiary' => HIVELITE_SM_LIGHT_TERTIARY,
			),
		),
	), $color_palettes);

	return $color_palettes;
}
add_filter( 'customify_get_color_palettes', 'hivelite_add_default_color_palette', 10, 1 );

function hivelite_change_default_sm_coloration_level( $config ) {
	if ( ! empty( $config['sections']['style_manager_section']['options']['sm_palette_filter'] ) ) {
		$config['sections']['style_manager_section']['options']['sm_palette_filter']['default'] = 'original';
	}

	return $config;
}
add_filter( 'customify_filter_fields', 'hivelite_change_default_sm_coloration_level', 999, 1 );
