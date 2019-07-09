<?php
/**
 * The template for displaying the status post format on archives.
 * @package Hive Lite 1.1.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="media  media--rev">

		<div class="media__img">
			<?php if ( hivelite_validate_gravatar( get_the_author_meta( 'email' ) ) ) { ?>
				<div class="entry-avatar">
					<?php echo get_avatar( get_the_author_meta( 'email' ) ); ?>
				</div><!-- .entry-avatar -->
			<?php } ?>
		</div>

		<div class="media__body">
			<?php
			// Just the excerpt for search pages
			if ( is_search() ) { ?>
				<div class="entry-summary">
					<?php the_excerpt(); ?>
				</div><!-- .entry-summary -->
			<?php } else { ?>
				<div class="entry-content">
					<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'hive-lite' ) );

					wp_link_pages( array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'hive-lite' ),
						'after'  => '</div>',
					) ); ?>
				</div><!-- .entry-content -->
			<?php }
			echo get_the_author_link(); ?>
		</div>

	</div>

	<?php edit_post_link( esc_html__( 'Edit', 'hive-lite' ), '<div class="edit-link">', '</div>' ); ?>

	<?php get_template_part( 'content-footer' ); ?>

</article><!-- #post-## -->