<?php
/**
 * The template for displaying posts home.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 * @package Hive Lite
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header(); ?>

	<div id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<div id="posts" class="archive__grid  grid  masonry">
				<?php /* Start the Loop */
				while ( have_posts() ) : the_post();

					/* Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'content', get_post_format() );

				endwhile; ?>
			</div>

			<?php hivelite_paging_nav();
		else :

			get_template_part( 'content', 'none' );

		endif; ?>

	</div><!-- #main -->

<?php
//no sidebar on home please

get_footer();
