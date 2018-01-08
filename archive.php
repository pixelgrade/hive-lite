<?php
/**
 * The template for displaying archive pages.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 * @package Hive
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<h1 class="page-title">
						<?php
						if ( is_category() ) : ?>
							<span class="screen-reader-text"><?php _e( 'Category Archive:', 'hive_txtd' ); ?> </span> <?php single_cat_title();

						elseif ( is_tag() ) :
							single_tag_title();

						elseif ( is_author() ) :
							printf( __( 'Author: %s', 'hive_txtd' ), '<span class="vcard">' . get_the_author() . '</span>' );

						elseif ( is_day() ) :
							printf( __( 'Day: %s', 'hive_txtd' ), '<span>' . get_the_date() . '</span>' );

						elseif ( is_month() ) :
							printf( __( 'Month: %s', 'hive_txtd' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'hive_txtd' ) ) . '</span>' );

						elseif ( is_year() ) :
							printf( __( 'Year: %s', 'hive_txtd' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'hive_txtd' ) ) . '</span>' );

						else :
							_e( 'Archives', 'hive_txtd' );

						endif; ?>
					</h1>
					<?php
					// Show an optional term description.
					$term_description = term_description();

					if ( ! empty( $term_description ) ) {
						printf( '<div class="taxonomy-description">%s</div>', $term_description );
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

				<?php hive_paging_nav();

			else :

				get_template_part( 'content', 'none' );

			endif; ?>

		</main>
		<!-- #main -->
	</section><!-- #primary -->

<?php get_footer(); ?>