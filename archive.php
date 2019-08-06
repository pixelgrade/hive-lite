<?php
/**
 * The template for displaying archive pages.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 * @package Hive Lite
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<h1 class="page-title">
                        <?php the_archive_title(); ?>
					</h1>
					<?php
					// Show an optional term description.
					$term_description = term_description();

					if ( ! empty( $term_description ) ) {
						/* translators: %s: term description */
						the_archive_description( '<div class="taxonomy-description">', '</div>' );
					} ?>
				</header><!-- .page-header -->

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

		</main><!-- #main -->
	</section><!-- #primary -->

<?php get_footer();
