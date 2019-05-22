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
						<?php
						if ( is_category() ) : ?>
							<span class="screen-reader-text"><?php esc_html_e( 'Category Archive:', 'hive-lite' ); ?> </span> <?php single_cat_title();

						elseif ( is_tag() ) :
							single_tag_title();

						elseif ( is_author() ) :
							/* translators: %s: author name */
							printf( esc_html__( 'Author: %s', 'hive-lite' ), '<span class="vcard">' . get_the_author() . '</span>' );

						elseif ( is_day() ) :
							/* translators: %s: day */
							printf( esc_html__( 'Day: %s', 'hive-lite' ), '<span>' . get_the_date() . '</span>' );

						elseif ( is_month() ) :
							/* translators: %s: month name */
							printf( esc_html__( 'Month: %s', 'hive-lite' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'hive-lite' ) ) . '</span>' );

						elseif ( is_year() ) :
							/* translators: %s: year */
							printf( esc_html__( 'Year: %s', 'hive-lite' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'hive-lite' ) ) . '</span>' );

						else :
							esc_html_e( 'Archives', 'hive-lite' );

						endif; ?>
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

		</main>
		<!-- #main -->
	</section><!-- #primary -->

<?php get_footer();
