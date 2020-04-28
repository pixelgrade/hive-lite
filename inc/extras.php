<?php
/**
 * Custom functions that act independently of the theme templates
 * Eventually, some of the functionality here could be replaced by core features
 * @package Hive Lite
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 *
 * @return array
 */
function hivelite_page_menu_args( $args ) {
	$args[ 'show_home' ] = true;

	return $args;
}
add_filter( 'wp_page_menu_args', 'hivelite_page_menu_args' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 *
 * @return array
 */
function hivelite_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[ ] = 'group-blog';
	}

	if ( ( is_single() || is_page() ) && is_active_sidebar( 'sidebar-1' ) ) {
		$sidebars_widgets = wp_get_sidebars_widgets();

		/**
		 * https://premium-themes.forums.wordpress.com/topic/full-width-and-eu-cookie-law-banner
		 * This special case when there is only one `EU Cookie law` widget in sidebar
		 * This widget does not output html and the class `has_sidebar` should not be present with an empty sidebar
		 */
		if ( 1 < count( $sidebars_widgets['sidebar-1'] ) || ( ! empty( $sidebars_widgets['sidebar-1'] ) && false === strpos( reset( $sidebars_widgets['sidebar-1'] ), 'eu_cookie_law' ) ) ) {
			$classes[] = 'has_sidebar';
		}
	}

	return $classes;
}
add_filter( 'body_class', 'hivelite_body_classes' );

/**
 * Extend the default WordPress post classes.
 *
 * @since Hive 1.0
 *
 * @param array $classes A list of existing post class values.
 * @return array The filtered post class list.
 */
function hivelite_post_classes( $classes ) {

	if ( is_archive() || is_home() || is_search() ) {
		$classes[] = 'grid__item';
	}

	return $classes;
}
add_filter( 'post_class', 'hivelite_post_classes' );

/**
 * Filter wp_link_pages to wrap current page in span.
 *
 * @param $link
 *
 * @return string
 */
function hivelite_link_pages( $link ) {
	if ( is_numeric( $link ) ) {
		return '<span class="current">' . $link . '</span>';
	}

	return $link;
}
add_filter( 'wp_link_pages_link', 'hivelite_link_pages' );


function hivelite_excerpt_length( $length ) {
	return 18;
}
add_filter( 'excerpt_length', 'hivelite_excerpt_length', 999 );

function hivelite_validate_gravatar( $email ) {
	if ( !empty($email) ) {
		// Craft a potential url and test the response
		$email_hash = md5( strtolower( trim( $email ) ) );

		if ( is_ssl() ) {
			$host = 'https://secure.gravatar.com';
		} else {
			$host = sprintf( "http://%d.gravatar.com", ( hexdec( $email_hash[0] ) % 2 ) );
		}
		$uri     = $host . '/avatar/' . $email_hash . '?d=404';

		//make request and test response
		if ( 404 === wp_remote_retrieve_response_code( wp_remote_get( $uri ) ) ) {
			$has_valid_avatar = false;
		} else {
			$has_valid_avatar = true;
		}

		return $has_valid_avatar;
	}

	return false;
}

/**
 * Wrap more link
 */
function hivelite_read_more_link( $link ) {
	return '<div class="more-link-wrapper">' . $link . '</div>';
}
add_filter( 'the_content_more_link', 'hivelite_read_more_link' );

/**
 * PHP's DOM classes are recursive but don't provide an implementation of
 * RecursiveIterator. This class provides a RecursiveIterator for looping over DOMNodeList
 *
 * taken from here: http://php.net/manual/en/class.domnodelist.php#109301
 */
class HiveLite_DOMNodeRecursiveIterator extends ArrayIterator implements RecursiveIterator {

	public function __construct (DOMNodeList $node_list) {

		$nodes = array();
		foreach($node_list as $node) {
			$nodes[] = $node;
		}

		parent::__construct($nodes);

	}

	public function getRecursiveIterator(){
		return new RecursiveIteratorIterator($this, RecursiveIteratorIterator::SELF_FIRST);
	}

	public function hasChildren () {
		return $this->current()->hasChildNodes();
	}


	public function getChildren () {
		return new self($this->current()->childNodes);
	}

}

/**
 * Based on a set of rules we will try and introduce bold, italic and bold-italic sections in the title
 */
function hivelite_auto_style_title( $title ) {

	if ( in_the_loop() ) {
		//we need to use the DOM because the title may have some markup in it due to user input or plugins messing with the title
		$dom = new DOMDocument( '1.0', 'utf-8' );
		//need to encode the utf-8 characters
		$dom->loadHTML( '<?xml encoding="UTF-8">' . '<body>' . $title . '</body>' ); //we wrap it ourselves so PHP doesn't add more markup wrapping
		$dom->encoding = 'UTF-8';

		$dit = new RecursiveIteratorIterator(
			new HiveLite_DOMNodeRecursiveIterator($dom->childNodes),
			RecursiveIteratorIterator::SELF_FIRST);

		foreach( $dit as $node ) {
			if ($node->nodeType == XML_PI_NODE)
				$dom->removeChild($node); // remove hack

			if ($node->nodeName == '#text') {

				//Make UPPERCASE words BOLD - at least two characters
				$node->nodeValue = preg_replace( '/\b([\p{Lu}][\p{Lu}]+)\b/u', '<strong>$1</strong>', $node->nodeValue );

				//Make words followed by ! bold
				$node->nodeValue = preg_replace( '/\b(\w+\!)/u', '<strong>$1</strong>', $node->nodeValue );

				//Make everything in quotes italic
				// first the regular quotes
				$node->nodeValue = preg_replace( '/(\"[^\"]+\")/', '<em>$1</em>', $node->nodeValue );
				// then fancy quotes
				$node->nodeValue = preg_replace( '/(\&\#8220\;[^\"]+\&\#8221\;)/', '<em>$1</em>', $node->nodeValue );

				//Make everything between () italic
				$node->nodeValue = preg_replace( '/(\([^\(\)]+\))/', '<em>$1</em>', $node->nodeValue );

				//Make everything between : and ! or ? italic
				$node->nodeValue = preg_replace( '/(?<=\:)([^\:\!\?]+[\!|\?]\S*)/', '<em>$1</em>', $node->nodeValue );

				//Make everything between start and : bold
				$node->nodeValue = preg_replace( '/(\A[^\:]+\:)/', '<strong>$1</strong>', $node->nodeValue );

				//Make a title with only one ? in it, bold at the left and italic at the right
				$node->nodeValue = preg_replace( '/(\A[^\?\:\!]+\?)([^\?\:\!]+)\z/', '<strong>$1</strong><em>$2</em>', $node->nodeValue );
			}
		}

		# remove <!DOCTYPE
		$dom->removeChild( $dom->doctype );

		# remove <html></html>
		$dom->replaceChild( $dom->firstChild->firstChild, $dom->firstChild );

		//decode the specialchars because saveHTML() will do that for us :(
		$title = htmlspecialchars_decode( $dom->saveHTML() );

		#remove the <body> tags we've just added
		$title = preg_replace( '#<body.*?>(.*?)</body>#i', '\1', $title );
	}

	return wp_kses( $title, array(
		'strong' => array(),
		'em' => array(),
		'b' => array(),
		'i' => array(),
	) );
}

/**
 * Generate the Google Fonts URL
 *
 * Based on this article http://themeshaper.com/2014/08/13/how-to-add-google-fonts-to-wordpress-themes/
 */
function hivelite_fonts_url() {
	$fonts_url = '';

	/* Translators: If there are characters in your language that are not
	* supported by Noto Serif, translate this to 'off'. Do not translate
	* into your own language.
	*/
	$noto_serif = _x( 'on', 'Noto Serif font: on or off', 'hive-lite' );

	/* Translators: If there are characters in your language that are not
	* supported by Playfair Display, translate this to 'off'. Do not translate
	* into your own language.
	*/
	$playfair_display = _x( 'on', 'Playfair Display font: on or off', 'hive-lite' );


	if ( 'off' !== $noto_serif || 'off' !== $playfair_display ) {
		$font_families = array();

		if ( 'off' !== $noto_serif ) {
			$font_families[] = 'Noto Serif:400,700,400italic';
		}

		if ( 'off' !== $playfair_display ) {
			$font_families[] = 'Playfair Display:400,700,900,400italic,700italic,900italic';
		}

		$query_args = array(
			'family' => rawurlencode( implode( '|', $font_families ) ),
			'subset' => rawurlencode( 'latin,latin-ext' ),
		);

		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return $fonts_url;
}

/**
 * Add "Styles" drop-down
 */
function hivelite_mce_editor_buttons( $buttons ) {
	array_unshift($buttons, 'styleselect' );
	return $buttons;
}
add_filter( 'mce_buttons_2', 'hivelite_mce_editor_buttons' );

/**
 * Add styles/classes to the "Styles" drop-down
 */
function hivelite_mce_before_init( $settings ) {

	$style_formats =array(
		array( 'title' => esc_html__( 'Intro Text', 'hive-lite' ), 'selector' => 'p', 'classes' => 'intro'),
		array( 'title' => esc_html__( 'Dropcap', 'hive-lite' ), 'inline' => 'span', 'classes' => 'dropcap'),
		array( 'title' => esc_html__( 'Highlight', 'hive-lite' ), 'inline' => 'span', 'classes' => 'highlight' ),
		array( 'title' => esc_html__( 'Two Columns', 'hive-lite' ), 'selector' => 'p', 'classes' => 'twocolumn', 'wrapper' => true )
	);

	$settings['style_formats'] = json_encode( $style_formats );

	return $settings;
}
add_filter( 'tiny_mce_before_init', 'hivelite_mce_before_init' );

/**
 * Fix skip link focus in IE11.
 *
 * This does not enqueue the script because it is tiny and because it is only for IE11,
 * thus it does not warrant having an entire dedicated blocking script being loaded.
 *
 * @link https://git.io/vWdr2
 */
function hivelite_skip_link_focus_fix() {
	// The following is minified via `terser --compress --mangle -- js/skip-link-focus-fix.js`.
	?>
	<script>
		/(trident|msie)/i.test(navigator.userAgent)&&document.getElementById&&window.addEventListener&&window.addEventListener("hashchange",function(){var t,e=location.hash.substring(1);/^[A-z0-9_-]+$/.test(e)&&(t=document.getElementById(e))&&(/^(?:a|select|input|button|textarea)$/i.test(t.tagName)||(t.tabIndex=-1),t.focus())},!1);
	</script>
	<?php
}
// We will put this script inline since it is so small.
add_action( 'wp_print_footer_scripts', 'hivelite_skip_link_focus_fix' );
