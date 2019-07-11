<?php
/**
 * The template for displaying single link post format posts.
 * @package Hive Lite 1.1.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	/* translators: used between list items, there is a space after the comma */
	$category_list = get_the_category_list( __( ', ', 'hive-lite' ) );

	/* translators: used between list items, there is a space after the comma */
	$tag_list = get_the_tag_list( '', __( ', ', 'hive-lite' ) );

	if ( ! hivelite_categorized_blog() ) {
		// This blog only has 1 category so we just need to worry about tags in the meta text
		if ( '' != $tag_list ) {
			$meta_text = __( 'This entry was tagged %2$s. Bookmark the <a href="%3$s" rel="bookmark">permalink</a>.', 'hive-lite' );
		} else {
			$meta_text = __( 'Bookmark the <a href="%3$s" rel="bookmark">permalink</a>.', 'hive-lite' );
		}

	} else {
		// But this blog has loads of categories so we should probably display them here
		if ( '' != $tag_list ) {
			$meta_text = __( 'Posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" rel="bookmark">permalink</a>.', 'hive-lite' );
		} else {
			$meta_text = __( 'Posted in %1$s. Bookmark the <a href="%3$s" rel="bookmark">permalink</a>.', 'hive-lite' );
		}

	} // end check for categories on this blog ?>
	<header class="entry-header">
		<div class="entry-meta">
			<?php hivelite_posted_on(); ?>
			<span class="entry-format">
				<a href="<?php echo esc_url( get_post_format_link( 'link' ) ); ?>" title="<?php echo sprintf( esc_attr__( 'All %s posts', 'hive-lite' ), get_post_format_string( 'link' ) ); ?>">
					<?php echo get_post_format_string( 'link' ); ?>
				</a>
			</span>
			<?php if ( $category_list && hivelite_categorized_blog() ) { ?>
				<span class="cat-links">
					<?php echo $category_list; ?>
				</span>
			<?php } // End if categories ?>
		</div><!-- .entry-meta -->

		<?php if ( has_post_thumbnail() ) { ?>
			<div class="entry-featured  entry-thumbnail">
				<a href="<?php echo esc_url( hivelite_get_post_format_link_url() ); ?>" title="<?php echo get_the_title(); ?>" rel="bookmark">
					<?php the_post_thumbnail( 'hive-single-image' ) ?>
					<div class="article__featured-image-meta">
						<div class="flexbox">
							<div class="flexbox__item">
								<i class="fa fa-link"></i>
							</div>
						</div>
					</div>
				</a>
			</div>
		<?php } else {
			the_title( '<h1 class="entry-title"><a href="' . esc_url( hivelite_get_post_format_link_url() ) . '" rel="bookmark">', '</a></h1>' );
		} ?>
	</header><!-- .entry-header -->

	<footer class="entry-footer">
		<?php
		printf( $meta_text, $category_list, $tag_list, esc_url( get_permalink() ) );

		edit_post_link( __( 'Edit', 'hive-lite' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->