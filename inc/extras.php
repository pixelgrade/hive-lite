<?php
/**
 * Custom functions that act independently of the theme templates
 * Eventually, some of the functionality here could be replaced by core features
 * @package Hive
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 *
 * @return array
 */
function hive_page_menu_args( $args ) {
	$args[ 'show_home' ] = true;

	return $args;
}

add_filter( 'wp_page_menu_args', 'hive_page_menu_args' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 *
 * @return array
 */
function hive_body_classes( $classes ) {
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
		if ( 1 === count( $sidebars_widgets['sidebar-1'] ) && false !== strpos( $sidebars_widgets['sidebar-1']['0'], 'eu_cookie_law' )) {
			// no class
		} else {
			$classes[ ] = 'has_sidebar';
		}
	}

	return $classes;
}

add_filter( 'body_class', 'hive_body_classes' );

/**
 * Extend the default WordPress post classes.
 *
 * @since Hive 1.0
 *
 * @param array $classes A list of existing post class values.
 * @return array The filtered post class list.
 */
function hive_post_classes( $classes ) {

	if ( is_archive() || is_home() || is_search() ) {
		$classes[] = 'grid__item';
	}

	return $classes;
}
add_filter( 'post_class', 'hive_post_classes' );


if ( version_compare( $GLOBALS['wp_version'], '4.1', '<' ) ) :
	/**
	 * Filters wp_title to print a neat <title> tag based on what is being viewed.
	 *
	 * @param string $title Default title text for current view.
	 * @param string $sep Optional separator.
	 * @return string The filtered title.
	 */
	function hive_wp_title( $title, $sep ) {
		if ( is_feed() ) {
			return $title;
		}

		global $page, $paged;

		// Add the blog name
		$title .= get_bloginfo( 'name', 'display' );

		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) ) {
			$title .= " $sep $site_description";
		}

		// Add a page number if necessary:
		if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
			$title .= " $sep " . sprintf( __( 'Page %s', 'hive_txtd' ), max( $paged, $page ) );
		}

		return $title;
	}
	add_filter( 'wp_title', 'hive_wp_title', 10, 2 );

	/**
	 * Title shim for sites older than WordPress 4.1.
	 *
	 * @link https://make.wordpress.org/core/2014/10/29/title-tags-in-4-1/
	 * @todo Remove this function when WordPress 4.3 is released.
	 */
	function hive_render_title() {
		?>
		<title><?php wp_title( '|', true, 'right' ); ?></title>
	<?php
	}
	add_action( 'wp_head', 'hive_render_title' );
endif;

/**
 * Sets the authordata global when viewing an author archive.
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 * It removes the need to call the_post() and rewind_posts() in an author
 * template to print information about the author.
 * @global WP_Query $wp_query WordPress Query object.
 * @return void
 */
function hive_setup_author() {
	global $wp_query;

	if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
		$GLOBALS[ 'authordata' ] = get_userdata( $wp_query->post->post_author );
	}
}

add_action( 'wp', 'hive_setup_author' );


/*
 * Individual comment layout
 */
function hive_comment( $comment, $args, $depth ) {
	static $comment_number;

	if ( ! isset( $comment_number ) ) {
		$comment_number = $args[ 'per_page' ] * ( $args[ 'page' ] - 1 ) + 1;
	} else {
		$comment_number ++;
	}

	$GLOBALS[ 'comment' ] = $comment; ?>
<li <?php comment_class(); ?>>
	<article id="comment-<?php comment_ID() ?>" class="comment-article  media">
		<span class="comment-number"><?php echo $comment_number ?></span>
		<?php
		//grab the avatar - by default the Mystery Man
		$avatar = get_avatar( $comment ); ?>

		<aside class="comment__avatar  media__img"><?php echo $avatar; ?></aside>

		<div class="media__body">
			<header class="comment__meta comment-author">
				<?php printf( '<span class="comment__author-name">%s</span>', get_comment_author_link() ) ?>
				<time class="comment__time" datetime="<?php comment_time( 'c' ); ?>">
					<a href="<?php echo esc_url( get_comment_link( get_comment_ID() ) ) ?>" class="comment__timestamp"><?php printf( __( 'on %s at %s', 'hive_txtd' ), get_comment_date(), get_comment_time() ); ?> </a>
				</time>
				<div class="comment__links">
					<?php
					//we need some space before Edit
					edit_comment_link( __( 'Edit', 'hive_txtd' ), '  ' );

					comment_reply_link( array_merge( $args, array(
						'depth'     => $depth,
						'max_depth' => $args[ 'max_depth' ]
					) ) );
					?>
				</div>
			</header>
			<!-- .comment-meta -->
			<?php if ( $comment->comment_approved == '0' ) : ?>
				<div class="alert info">
					<p><?php _e( 'Your comment is awaiting moderation.', 'hive_txtd' ) ?></p>
				</div>
			<?php endif; ?>
			<section class="comment__content comment">
				<?php comment_text() ?>
			</section>
		</div>
	</article>
	<!-- </li> is added by WordPress automatically -->
<?php
} // don't remove this bracket!

/**
 * Filter wp_link_pages to wrap current page in span.
 *
 * @param $link
 *
 * @return string
 */
function hive_link_pages( $link ) {
	if ( is_numeric( $link ) ) {
		return '<span class="current">' . $link . '</span>';
	}

	return $link;
}

add_filter( 'wp_link_pages_link', 'hive_link_pages' );


function hive_excerpt_length( $length ) {
	return 18;
}

add_filter( 'excerpt_length', 'hive_excerpt_length', 999 );

function hive_validate_gravatar( $email ) {
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
function hive_read_more_link( $link ) {
	return '<div class="more-link-wrapper">' . $link . '</div>';
}

add_filter( 'the_content_more_link', 'hive_read_more_link' );

/**
 * PHP's DOM classes are recursive but don't provide an implementation of
 * RecursiveIterator. This class provides a RecursiveIterator for looping over DOMNodeList
 *
 * taken from here: http://php.net/manual/en/class.domnodelist.php#109301
 */
class DOMNodeRecursiveIterator extends ArrayIterator implements RecursiveIterator {

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
function hive_auto_style_title( $title ) {

	if ( in_the_loop() ) {
		//we need to use the DOM because the title may have some markup in it due to user input or plugins messing with the title
		$dom = new DOMDocument( '1.0', 'utf-8' );
		//need to encode the utf-8 characters
		$dom->loadHTML( '<?xml encoding="UTF-8">' . '<body>' . $title . '</body>' ); //we wrap it ourselves so PHP doesn't add more markup wrapping
		$dom->encoding = 'UTF-8';

		$dit = new RecursiveIteratorIterator(
			new DOMNodeRecursiveIterator($dom->childNodes),
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
function hive_fonts_url() {
	$fonts_url = '';

	/* Translators: If there are characters in your language that are not
	* supported by Droid Serif, translate this to 'off'. Do not translate
	* into your own language.
	*/
	$droid_serif = _x( 'on', 'Droid Serif font: on or off', 'hive_txtd' );

	/* Translators: If there are characters in your language that are not
	* supported by Playfair Display, translate this to 'off'. Do not translate
	* into your own language.
	*/
	$playfair_display = _x( 'on', 'Playfair Display font: on or off', 'hive_txtd' );


	if ( 'off' !== $droid_serif || 'off' !== $playfair_display ) {
		$font_families = array();

		if ( 'off' !== $droid_serif ) {
			$font_families[] = 'Droid Serif:400,700,400italic';
		}

		if ( 'off' !== $playfair_display ) {
			$font_families[] = 'Playfair Display:400,700,900,400italic,700italic,900italic';
		}

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);

		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return $fonts_url;
}

/*
 * Due to the fact that we need a wrapper for center aligned images and for the ones with alignnone, we need to wrap the images without a caption
 * The images with captions already are wrapped by the figure tag
 */
function hive_wrap_images_in_figure( $content ) {
	$classes = array ('aligncenter', 'alignnone');

	foreach ($classes as $class) {

		//this regex basically tells this
		//match all the images that are not in captions and that have the X class
		//when an image is wrapped by an anchor tag, match that too
		$regex = '~\[caption[^\]]*\].*\[\/caption\]|((?:<a[^>]*>\s*)?<img.*class="[^"]*' . $class . '[^"]*[^>]*>(?:\s*<\/a>)?)~i';

		// Replace the matches
		$content = preg_replace_callback(
			$regex,
			// in the callback function, if Group 1 is empty,
			// set the replacement to the whole match,
			// i.e. don't replace
			// PS: I know this is a PHP 5.3 but it's too elegant to pass :)
			function ( $m ) use ($class) {

				if ( empty( $m[1] ) ) {
					return $m[0];
				}

				return '<span class="' . $class . '">' . $m[1] . '</span>';
			},
			$content );
	}

	return $content;
}

add_filter( 'the_content', 'hive_wrap_images_in_figure' );

/**
 * Add "Styles" drop-down
 */
add_filter( 'mce_buttons_2', 'hive_mce_editor_buttons' );
function hive_mce_editor_buttons( $buttons ) {
	array_unshift($buttons, 'styleselect' );
	return $buttons;
}

/**
 * Add styles/classes to the "Styles" drop-down
 */
add_filter( 'tiny_mce_before_init', 'hive_mce_before_init' );
function hive_mce_before_init( $settings ) {

	$style_formats =array(
		array( 'title' => __( 'Intro Text', 'hive_txtd' ), 'selector' => 'p', 'classes' => 'intro'),
		array( 'title' => __( 'Dropcap', 'hive_txtd' ), 'inline' => 'span', 'classes' => 'dropcap'),
		array( 'title' => __( 'Highlight', 'hive_txtd' ), 'inline' => 'span', 'classes' => 'highlight' ),
		array( 'title' => __( 'Two Columns', 'hive_txtd' ), 'selector' => 'p', 'classes' => 'twocolumn', 'wrapper' => true )
	);

	$settings['style_formats'] = json_encode( $style_formats );

	return $settings;
}

/**
 * Check the content blob for an audio, video, object, embed, or iframe tags.
 * This is a modified version of the current core one, in line with this
 * https://core.trac.wordpress.org/ticket/26675
 * This should end up in the core in version 4.2 or 4.3 hopefully
 *
 * @param string $content A string which might contain media data.
 * @param array $types array of media types: 'audio', 'video', 'object', 'embed', or 'iframe'
 * @return array A list of found HTML media embeds
 */
// @todo Remove this when the right get_media_embedded_in_content() ends up in the core, v4.2 hopefully
function hive_get_media_embedded_in_content( $content, $types = null ) {
	$html = array();

	$allowed_media_types = apply_filters( 'get_media_embedded_in_content_allowed', array( 'audio', 'video', 'object', 'embed', 'iframe' ) );

	if ( ! empty( $types ) ) {
		if ( ! is_array( $types ) ) {
			$types = array( $types );
		}

		$allowed_media_types = array_intersect( $allowed_media_types, $types );
	}

	$tags = implode( '|', $allowed_media_types );

	if ( preg_match_all( '#<(?P<tag>' . $tags . ')[^<]*?(?:>[\s\S]*?<\/(?P=tag)>|\s*\/>)#', $content, $matches ) ) {
		foreach ( $matches[0] as $match ) {
			$html[] = $match;
		}
	}

	return $html;
}


/**
 * A function that removes the post format classes from post_class()
 */
function remove_post_format_class ( $classes ) {
	$classes = array_diff ( $classes, array(
		'format-quote',
		'format-image',
		'format-aside',
		'format-gallery',
		'format-audio',
		'format-video',
		'format-link',
		'format-status',
		'format-chat',
		));
	return $classes;
};
add_filter( 'post_class', 'remove_post_format_class', 20 ); ?>