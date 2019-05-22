<?php
/**
 * The template for displaying search results pages.
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
					<h1 class="page-title  page-title--search"><?php
						/* translators: %s: The search query. */
						printf( esc_html__( 'Search Results for: %s', 'hive-lite' ), '<span>' . esc_html( get_search_query() ) . '</span>' ); ?></h1>
				</header><!-- .page-header -->

				<div id="posts" class="archive__grid  grid  masonry">
					<?php
					/* Start the Loop */
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
