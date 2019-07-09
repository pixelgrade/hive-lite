<?php
/**
 * The template for displaying the aside post format on archives.
 * @package Hive Lite 1.1.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-content">
		<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'hive-lite' ) );
		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'hive-lite' ),
			'after'  => '</div>',
		) ); ?>
	</div><!-- .entry-content -->

	<?php edit_post_link( esc_html__( 'Edit', 'hive-lite' ), '<div class="edit-link">', '</div>' ); ?>

	<?php get_template_part( 'content-footer' ); ?>


</article><!-- #post-## -->