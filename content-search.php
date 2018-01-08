<?php
/**
 * The template part for displaying results in search pages.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 * @package Hive
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>

		<div class="entry-meta">
			<?php hive_posted_on(); ?>
		</div><!-- .entry-meta -->

	</header><!-- .entry-header -->

	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->

	<footer class="entry-footer">
		<?php
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( __( ', ', 'hive_txtd' ) );
		if ( $categories_list && hive_categorized_blog() ) { ?>
			<span class="cat-links">
			<?php printf( __( 'Posted in %1$s', 'hive_txtd' ), $categories_list ); ?>
		</span>
		<?php } // End if categories

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', __( ', ', 'hive_txtd' ) );
		if ( $tags_list ) { ?>
			<span class="tags-links">
			<?php printf( __( 'Tagged %1$s', 'hive_txtd' ), $tags_list ); ?>
		</span>
		<?php
		} // End if $tags_list

		if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) { ?>
			<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'hive_txtd' ), __( '1 Comment', 'hive_txtd' ), __( '% Comments', 'hive_txtd' ) ); ?></span>
		<?php
		}

		edit_post_link( __( 'Edit', 'hive_txtd' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->