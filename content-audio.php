<?php
/**
 * The template for displaying the audio post format on archives.
 * @package Hive Lite 1.1.6
 */

//get the media objects from the content and bring up only the first one
$media = hive_audio_attachment(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
		<?php the_title( sprintf( '<a href="%s" class="entry-permalink" rel="bookmark"><h1 class="entry-title">', esc_url( get_permalink() ) ), '</h1></a>' ); ?>
    </header><!-- .entry-header -->

	<?php if ( ! empty( $media ) ) : ?>
        <div class="entry-media">
			<?php echo $media; ?>
        </div><!-- .entry-media -->
	<?php endif; ?>

    <div class="entry-summary">
		<?php

		the_excerpt();

		wp_link_pages( array(
			'before' => '<div class="page-links">' . __( 'Pages:', 'hive-lite' ),
			'after'  => '</div>',
		) ); ?>
    </div><!-- .entry-summary -->

	<?php edit_post_link( __( 'Edit', 'hive-lite' ), '<div class="edit-link">', '</div>' ); ?>

    <footer class="entry-footer">
        <div class="entry-meta">
			<?php hivelite_posted_on();

			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( __( ', ', 'hive-lite' ) );
			if ( $categories_list && hivelite_categorized_blog() ) { ?>
                <span class="cat-links">
                        <?php echo $categories_list; ?>
                    </span>
			<?php } // End if categories ?>
        </div><!-- .entry-meta -->
    </footer><!-- .entry-footer -->

</article><!-- #post-## -->