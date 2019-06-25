<?php
/**
 * Template part for displaying single posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 * @package Hive Lite
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	/* translators: used between list items, there is a space after the comma */
	$category_list = get_the_category_list( esc_html__( ', ', 'hive-lite' ) );

	/* translators: used between list items, there is a space after the comma */
	$tag_list = get_the_tag_list( '', esc_html__( ', ', 'hive-lite' ) );

	if ( ! hivelite_categorized_blog() ) {
		// This blog only has 1 category so we just need to worry about tags in the meta text
		if ( '' != $tag_list ) {
			/* translators: %2$s: The tags list, %3$s The post URL. */
			$meta_text = __( 'This entry was tagged %2$s. Bookmark the <a href="%3$s" rel="bookmark">permalink</a>.', 'hive-lite' );
		} else {
			/* translators: %3$s The post URL. */
			$meta_text = __( 'Bookmark the <a href="%3$s" rel="bookmark">permalink</a>.', 'hive-lite' );
		}

	} else {
		// But this blog has loads of categories so we should probably display them here
		if ( '' != $tag_list ) {
			/* translators: %1$s: The categories list, %2$s: The tags list, %3$s The post URL. */
			$meta_text = __( 'Posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" rel="bookmark">permalink</a>.', 'hive-lite' );
		} else {
			/* translators: %1$s: The categories list, %3$s The post URL. */
			$meta_text = __( 'Posted in %1$s. Bookmark the <a href="%3$s" rel="bookmark">permalink</a>.', 'hive-lite' );
		}

	} // end check for categories on this blog
	?>

	<header class="entry-header">

		<div class="entry-meta">
			<?php hivelite_posted_on();

			if ( $category_list && hivelite_categorized_blog() ) { ?>
				<span class="cat-links">
					<?php echo $category_list; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</span>
			<?php } // End if categories ?>
		</div>
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

	</header><!-- .entry-header -->

	<?php if ( has_post_thumbnail() ) { ?>
		<div class="entry-featured  entry-thumbnail">
			<?php the_post_thumbnail( 'hive-single-image' ); ?>
			<?php
				$image_caption = get_post( get_post_thumbnail_id() )->post_excerpt;

				if( ! empty( $image_caption ) ) {
					echo '<span class="entry-featured__caption">' . wp_kses_post( $image_caption ) . '</span>';
				}
			?>
		</div>
	<?php } ?>

	<?php
	// We need to first get the rendered content, and then echo it.
	// This way we can process the rendered content without firing the 'the_content' filter multiple times.
	$content = hivelite_get_rendered_content();
	?>
	<div class="entry-content" <?php
	$first_letter = hivelite_first_content_character( $content );
	if ( ! empty( $first_letter ) ) {
		echo 'data-first_letter="' . esc_attr( $first_letter ) . '"';
	} ?>>
		<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div><!-- .entry-content -->

	<?php
	wp_link_pages( array(
		'before'           => '<div class="page-links  pagination">',
		'after'            => '</div>',
		'next_or_number'   => 'number',
		'nextpagelink'     => esc_html__( 'Next page', 'hive-lite' ),
		'previouspagelink' => esc_html__( 'Previous page', 'hive-lite' ),
		'pagelink'         => '%',
		'echo'             => 1,
	) ); ?>

	<footer class="entry-footer">
		<?php printf( wp_kses_post( $meta_text ), $category_list, $tag_list, esc_url( get_permalink() ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		edit_post_link( esc_html__( 'Edit', 'hive-lite' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
