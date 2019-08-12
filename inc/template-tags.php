<?php
/**
 * Custom template tags for this theme.
 * Eventually, some of the functionality here could be replaced by core features.
 * @package Hive Lite
 */

if ( ! function_exists( 'hivelite_paging_nav' ) ) :
	/**
	 * Display navigation to next/previous set of posts when applicable.
	 */
	function hivelite_paging_nav() {
		global $wp_query, $wp_rewrite;
		// Don't print empty markup if there's only one page.
		if ( $wp_query->max_num_pages < 2 ) {
			return;
		}

		$paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
		$pagenum_link = html_entity_decode( get_pagenum_link() );
		$query_args   = array();
		$url_parts    = explode( '?', $pagenum_link );

		if ( isset( $url_parts[1] ) ) {
			wp_parse_str( $url_parts[1], $query_args );
		}

		$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
		$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

		$format  = $wp_rewrite->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
		$format .= $wp_rewrite->using_permalinks() ? user_trailingslashit( $wp_rewrite->pagination_base . '/%#%', 'paged' ) : '?paged=%#%'; ?>

		<nav class="pagination" role="navigation">
			<h1 class="screen-reader-text"><?php esc_html_e( 'Posts navigation', 'hive-lite' ); ?></h1>

			<div class="nav-links">

				<?php
				//output a disabled previous "link" if on the fist page
				if ( $paged == 1 ) {
					echo '<span class="prev page-numbers disabled">' . esc_html__( '« Previous', 'hive-lite' ) . '</span>';
				}

				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				 the_posts_pagination( array(
						'base'      => $pagenum_link,
						'format'    => $format,
						'total'     => $wp_query->max_num_pages,
						'current'   => $paged,
						'prev_next' => true,
						'prev_text' => esc_html__( '« Previous', 'hive-lite' ),
						'next_text' => esc_html__( 'Next »', 'hive-lite' ),
						'add_args'  => array_map( 'urlencode', $query_args ),
				) );

				//output a disabled next "link" if on the last page
				if ( $paged == $wp_query->max_num_pages ) {
					echo '<span class="next page-numbers disabled">' . esc_html__( 'Next »', 'hive-lite' );
				} ?>

			</div><!-- .nav-links -->

		</nav><!-- .navigation -->
	<?php
	}

endif;


if ( ! function_exists( 'hivelite_post_nav' ) ) :
	/**
	 * Display navigation to next/previous post when applicable.
	 */
	function hivelite_post_nav() {
		if ( is_single() || is_attachment() ) {
			// Don't print empty markup if there's nowhere to navigate.
			$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
			$next     = get_adjacent_post( false, '', false );

			if ( ! $next && ! $previous ) {
				return;
			}
			?>
			<nav class="navigation post-navigation" role="navigation">
				<h1 class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'hive-lite' ); ?></h1>

				<div class="nav-links">
					<?php
					//show the link to the HOME page only on single posts
					if ( is_single() ) {
						$static_posts_page = get_option( 'page_for_posts' );
						if ( $static_posts_page ) {
							$home_url = esc_url( get_permalink( $static_posts_page ) );
						} else {
							$home_url = home_url();
						}

						echo '<div class="nav-home"><a href="' . esc_url( $home_url ) . '"><i class="fa fa-th-large"></i></a></div>';
					}
					$prev_link = get_previous_post_link( '<div class="nav-previous">%link</div>', '<i class="fa fa-long-arrow-left prev-arrow"></i><span>%title</span>' );
					if ( ! empty( $prev_link ) ) {
						echo $prev_link; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					} else {
						//put a disabled "link"
						echo '<div class="nav-previous disabled"><i class="fa fa-long-arrow-left prev-arrow"></i></div>';
					}

					$next_link = get_next_post_link( '<div class="nav-next">%link</div>', '<span>%title</span><i class="fa fa-long-arrow-right next-arrow"></i>' );
					if ( ! empty( $next_link ) ) {
						echo $next_link; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					} else {
						//put a disabled "link"
						echo '<div class="nav-next disabled"><i class="fa fa-long-arrow-right next-arrow"></i></div>';
					} ?>
				</div>
				<!-- .nav-links -->
			</nav><!-- .navigation -->
		<?php
		}
	}

endif;

if ( ! function_exists( 'hivelite_posted_on' ) ) :

	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function hivelite_posted_on() {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';

		$time_string = sprintf( $time_string, esc_attr( get_the_date( 'c' ) ), esc_html( get_the_date() ), esc_attr( get_the_modified_date( 'c' ) ), esc_html( get_the_modified_date() ) );

		$posted_on = '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>';

		$output = '<span class="posted-on">' . $posted_on . '</span>';

		//only show the author on archives and home if there are more than one
		if ( is_single() || is_multi_author() ) {
			$byline =
				'<span class="author vcard">
					<a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' .
						esc_html( get_the_author() ) . '
					</a>
				</span>';

			$output = '<span class="posted-by"> ' . $byline . '</span>' . "\n" . $output;
		}

		echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}

endif;

/**
 * Returns true if a blog has more than 1 category.
 * @return bool
 */
function hivelite_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'hive_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories(
			array(
				'fields'     => 'ids',
				'hide_empty' => 1,
				// We only need to know if there is more than one category.
				'number'     => 2,
			)
		);

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'hive_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so hive_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so hive_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in hive_categorized_blog.
 */
function hivelite_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'hive_categories' );
}
add_action( 'edit_category', 'hivelite_category_transient_flusher' );
add_action( 'save_post', 'hivelite_category_transient_flusher' );

if ( ! function_exists( 'hivelite_get_rendered_content' ) ) :
	/**
	 * Return the rendered post content.
	 *
	 * This is the same as the_content() except for the fact that it doesn't display the content, but returns it.
	 * Do make sure not to use this function twice for a post inside the loop, because it would defeat the purpose.
	 *
	 * @param string $more_link_text Optional. Content for when there is more text.
	 * @param bool   $strip_teaser   Optional. Strip teaser content before the more text. Default is false.
	 * @return string
	 */
	function hivelite_get_rendered_content( $more_link_text = null, $strip_teaser = false) {
		$content = get_the_content( $more_link_text, $strip_teaser );

		/**
		 * Filters the post content.
		 *
		 * @since 0.71
		 *
		 * @param string $content Content of the current post.
		 */
		$content = apply_filters( 'the_content', $content );
		$content = str_replace( ']]>', ']]&gt;', $content );
		return $content;
	}
endif;

if ( ! function_exists( 'hivelite_first_content_character' ) ) :
	/**
	 * Returns the first UTF-8 character of the content
	 * returns empty string if nothing found
	 *
	 * @param string $content The content to extract the first character from.
	 * @return string
	 */
	function hivelite_first_content_character( $content = '' ) {
		//no need for this when a password is required
		if ( post_password_required() ) {
			return '';
		}

		// By default we have no first letter
		$first_letter = '';

		if ( empty( $content ) ) {
			// If we haven't been provided with a rendered content (with all shortcodes run, etc),
			// we need to get our own.
			$content = get_the_content();

			// remove [caption] shortcode
			// because if it is the first part of the content we don't need the caption
			$content = trim( preg_replace( "/\[caption.*\[\/caption\]/si", '', $content ) );

			//now apply the regular filters, without the captions
			$content = apply_filters( 'the_content', $content );
		}

		// Bail if we have no content to work with
		if ( empty( $content ) ) {
			return $first_letter;
		}

		// We need to make sure that we don't look at strings inside <figure>s - those are probably captions -
		// or embeds - Twitter (usually inside divs with some embed class)
		// This is why we want to remove the tags and their content
		// We are only interested in the beginning of the the content, not the whole
		// This is why we are using preg_replace, not preg_replace_all
		$content = preg_replace( "/<figure.*<\/figure>/siU", '', $content );
		$content = preg_replace( "/<div.*embed.*<\/div>/siU", '', $content );


		// Strip all the tags that are left and use what we are left with
		$content = wp_strip_all_tags( html_entity_decode( $content ) );

		// Find the first alphanumeric character - multibyte
		preg_match( '/[\p{Xan}]/u', $content, $results );

		if ( ! empty( $results ) ) {
			$first_letter = reset( $results );
		} else {
			// Lets try the old fashion way
			// Find the first alphanumeric character - non-multibyte
			preg_match( '/[a-zA-Z\d]/', $content, $results );

			if ( ! empty( $results ) ) {
				$first_letter = reset( $results );
			}
		};

		return $first_letter;
	}

endif;

if ( ! function_exists( 'hivelite_first_site_title_character' ) ) :
	/**
	 * Returns the first UTF-8 character of the site title
	 * returns empty string if nothing found
	 *
	 * @param bool $data_attribute
	 *
	 * @return string
	 */
	function hivelite_first_site_title_character() {
		$title = get_bloginfo('name');

		if ( empty($title) ) {
			return;
		}

		$first_letter = '';
		//find the first alphanumeric character - multibyte
		preg_match( '/[\p{Xan}]/u', $title, $results );

		if ( isset( $results ) && ! empty( $results[0] ) ) {
			$first_letter = $results[0];
		} else {
			//lets try the old fashion way
			//find the first alphanumeric character - non-multibyte
			preg_match( '/[a-zA-Z\d]/', $title, $results );

			if ( isset( $results ) && ! empty( $results[0] ) ) {
				$first_letter = $results[0];
			}
		};

		return $first_letter;
	}

endif;

if ( ! function_exists( 'hivelite_get_post_format_first_image' ) ) :

	function hivelite_get_post_format_first_image() {
		global $post;

		$output = preg_match( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches );

		if ( empty( $matches[0] ) ) {
			return '';
		}

		return $matches[0];
	}

endif;

if ( ! function_exists( 'hivelite_get_post_format_link_url' ) ) :
	/**
	 * Returns the URL to use for the link post format.
	 *
	 * First it tries to get the first URL in the content; if not found it uses the permalink instead
	 *
	 * @return string URL
	 */
	function hivelite_get_post_format_link_url() {
		$content = get_the_content();
		$has_url = get_url_in_content( $content );

		return ( $has_url ) ? $has_url : apply_filters( 'the_permalink', esc_url( get_permalink() ) );
	}

endif;

/**
 * Handles the output of the media for audio attachment posts. This should be used within The Loop.
 *
 * @return string
 */
function hive_audio_attachment() {
	return hive_hybrid_media_grabber( array( 'type' => 'audio', 'split_media' => true ) );
}
/**
 * Handles the output of the media for video attachment posts. This should be used within The Loop.
 *
 * @return string
 */
function hive_video_attachment() {
	return hive_hybrid_media_grabber( array( 'type' => 'video', 'split_media' => true ) );
}

if ( ! function_exists( 'hivelite_get_rendered_content' ) ) :
	/**
	 * Return the rendered post content.
	 *
	 * This is the same as the_content() except for the fact that it doesn't display the content, but returns it.
	 * Do make sure not to use this function twice for a post inside the loop, because it would defeat the purpose.
	 *
	 * @param string $more_link_text Optional. Content for when there is more text.
	 * @param bool   $strip_teaser   Optional. Strip teaser content before the more text. Default is false.
	 * @return string
	 */
	function hivelite_get_rendered_content( $more_link_text = null, $strip_teaser = false) {
		$content = get_the_content( $more_link_text, $strip_teaser );

		/**
		 * Filters the post content.
		 *
		 * @since 0.71
		 *
		 * @param string $content Content of the current post.
		 */
		$content = apply_filters( 'the_content', $content );
		$content = str_replace( ']]>', ']]&gt;', $content );
		return $content;
	}
endif;

if ( ! function_exists( 'wp_body_open' ) ) :
	/**
	 * Fire the wp_body_open action.
	 *
	 * Added for backwards compatibility to support pre 5.2.0 WordPress versions.
	 *
	 * @since Hive Lite 1.2.2
	 */
	function wp_body_open() {
		/**
		 * Triggered after the opening <body> tag.
		 *
		 * @since Hive Lite 1.2.2
		 */
		do_action( 'wp_body_open' );
	}
endif;
