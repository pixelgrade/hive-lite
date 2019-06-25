<?php
/**
 * The template part for displaying results in search pages.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 * @package Hive Lite
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php
		/* translators: %s: The post URL. */
		the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>

		<div class="entry-meta">
			<?php hivelite_posted_on(); ?>
		</div><!-- .entry-meta -->

	</header><!-- .entry-header -->

	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->

	<footer class="entry-footer">
		<?php
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ', ', 'hive-lite' ) );
		if ( $categories_list && hivelite_categorized_blog() ) { ?>
			<span class="cat-links">
			<?php
				/* translators: %s: list of categories */
				printf( esc_html__( 'Posted in %s', 'hive-lite' ), $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
		</span>
		<?php } // End if categories

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', esc_html__( ', ', 'hive-lite' ) );
		if ( $tags_list ) { ?>
			<span class="tags-links">
			<?php
			/* translators: %s: list of tags. */
			printf( esc_html__( 'Tagged %s', 'hive-lite' ), $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
		</span>
		<?php
		} // End if $tags_list

		if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) { ?>
			<span class="comments-link"><?php
				/* translators: %s: The numberf of comments. */
				comments_popup_link( esc_html__( 'Leave a comment', 'hive-lite' ), esc_html__( '1 Comment', 'hive-lite' ), esc_html__( '% Comments', 'hive-lite' ) ); ?></span>
		<?php
		}

		edit_post_link( esc_html__( 'Edit', 'hive-lite' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
