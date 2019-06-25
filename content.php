<?php
/**
 * The template part responsible for displaying the post content.
 * @package Hive Lite
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="hover__handler">
		<header class="entry-header">
			<?php
			/* translators: %s: The post URL. */
			the_title( sprintf( '<a href="%s" class="entry-permalink" rel="bookmark"><h1 class="entry-title">', esc_url( get_permalink() ) ), '</h1></a>' ); ?>
		</header><!-- .entry-header -->

		<?php if ( has_post_thumbnail() ) { ?>
			<aside class="entry-thumbnail">
				<?php
				the_post_thumbnail( 'hive-masonry-image' );
				get_template_part( 'templates/featured-hover' );
				?>
			</aside>
		<?php } ?>
	</div>

	<div class="entry-content">
		<?php
		global $post;
		// Check the content for the more text
		$has_more = strpos( $post->post_content, '<!--more' );

		if ( $has_more ) {
			the_content( wp_kses_post( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'hive-lite' ) ) );
		} else {
			the_excerpt();
		}
		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'hive-lite' ),
			'after'  => '</div>',
		) ); ?>
	</div><!-- .entry-content -->

	<?php

	edit_post_link( esc_html__( 'Edit', 'hive-lite' ), '<div class="edit-link">', '</div>' );

	// Hide category and tag text for pages on Search
	if ( 'post' == get_post_type() ) { ?>
		<footer class="entry-footer">
			<div class="entry-meta">
				<?php hivelite_posted_on();

				/* translators: used between list items, there is a space after the comma */
				$categories_list = get_the_category_list( esc_html__( ', ', 'hive-lite' ) );
				if ( $categories_list && hivelite_categorized_blog() ) { ?>
					<span class="cat-links">
                        <?php echo $categories_list; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </span>
				<?php } ?>
			</div><!-- .entry-meta -->
		</footer><!-- .entry-footer -->
	<?php } ?>
</article><!-- #post-## -->
