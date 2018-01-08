<?php
/**
 * @package Hive
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="hover__handler">
		<header class="entry-header">
			<?php the_title( sprintf( '<a href="%s" class="entry-permalink" rel="bookmark"><h1 class="entry-title">', esc_url( get_permalink() ) ), '</h1></a>' ); ?>
		</header><!-- .entry-header -->

		<?php if ( has_post_thumbnail() ) { ?>
			<aside class="entry-thumbnail">
				<?php the_post_thumbnail( 'hive-masonry-image' ) ?>
				<?php get_template_part( 'templates/featured-hover' ); ?>
			</aside>
		<?php } ?>
	</div>

	<div class="entry-content">
		<?php
		global $post;
		// Check the content for the more text
		$has_more = strpos( $post->post_content, '<!--more' );

		if ( $has_more ) {
			the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'hive_txtd' ) );
		} else {
			the_excerpt();
		}
		wp_link_pages( array(
			'before' => '<div class="page-links">' . __( 'Pages:', 'hive_txtd' ),
			'after'  => '</div>',
		) ); ?>
	</div><!-- .entry-content -->

	<?php

	edit_post_link( __( 'Edit', 'hive_txtd' ), '<div class="edit-link">', '</div>' );

	// Hide category and tag text for pages on Search
	if ( 'post' == get_post_type() ) { ?>
		<footer class="entry-footer">
			<div class="entry-meta">
				<?php hive_posted_on();

				/* translators: used between list items, there is a space after the comma */
				$categories_list = get_the_category_list( __( ', ', 'hive_txtd' ) );
				if ( $categories_list && hive_categorized_blog() ) { ?>
					<span class="cat-links">
                        <?php echo $categories_list; ?>
                    </span>
				<?php } // End if categories ?>
			</div><!-- .entry-meta -->
		</footer><!-- .entry-footer -->
	<?php } // End if 'post' == get_post_type() ?>
</article><!-- #post-## -->