<?php
/**
 * The template for displaying the link post format on archives.
 * @package Hive Lite 1.1.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if ( has_post_thumbnail() ) { ?>
		<aside class="entry-thumbnail">
			<a href="<?php echo esc_url( hivelite_get_post_format_link_url() ); ?>" title="<?php echo get_the_title(); ?>" rel="bookmark">
				<?php the_post_thumbnail( 'hive-masonry-image' ) ?>
				<div class="article__featured-image-meta">
					<div class="flexbox">
						<div class="flexbox__item">
							<i class="fa fa-link"></i>
						</div>
					</div>
				</div>
			</a>
		</aside>
	<?php } else { ?>
		<header class="entry-header">
			<?php the_title( sprintf( '<a href="%s" class="entry-permalink" rel="bookmark"><h1 class="entry-title">', esc_url( hivelite_get_post_format_link_url() ) ), '</h1></a>' ); ?>
		</header><!-- .entry-header -->
	<?php
	}

	edit_post_link( esc_html__( 'Edit', 'hive-lite' ), '<div class="edit-link">', '</div>' ); ?>

	<?php get_template_part( 'content-footer' ); ?>

</article><!-- #post-## -->