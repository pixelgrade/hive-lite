<?php
/**
 * The template for displaying the video post format on archives.
 * @package Hive Lite 1.1.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//get the media objects from the content and bring up only the first one
$media = hive_video_attachment(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>


		<header class="entry-header">
			<?php the_title( sprintf( '<a href="%s" class="entry-permalink" rel="bookmark"><h1 class="entry-title">', esc_url( get_permalink() ) ), '</h1></a>' ); ?>
		</header><!-- .entry-header -->

	<?php if ( ! empty( $media ) ) : ?>
		<div class="entry-media">
			<?php echo $media; ?>
		</div><!-- .entry-media -->
	<?php endif; ?>


	<?php if ( get_the_excerpt() ) { ?>
		<div class="entry-summary">
			<?php

			the_excerpt();

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'hive-lite' ),
				'after'  => '</div>',
			) ); ?>
		</div><!-- .entry-summary -->
	<?php } ?>

	<?php edit_post_link( esc_html__( 'Edit', 'hive-lite' ), '<div class="edit-link">', '</div>' ); ?>

	<?php get_template_part( 'content-footer' ); ?>

</article><!-- #post-## -->