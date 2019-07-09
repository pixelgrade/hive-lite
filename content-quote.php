<?php
/**
 * The template for displaying the quote post format on archives.
 * @package Hive Lite 1.1.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-permalink">
		<?php
		//let's see if we have a featured image
		$post_thumbnail_html = '';
		if ( has_post_thumbnail() ) {
			$post_thumbnail     = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'hive-masonry-image' );
			if (isset($post_thumbnail[ 0 ])) {
				$post_thumbnail_html = '<div class="entry-thumbnail  quote-wrapper" style="background-image: url(' . esc_url( $post_thumbnail[ 0 ] ) . ');" ></div>';
			}
		} ?>

		<div class="entry-content">
			<?php echo $post_thumbnail_html; ?>
			<div class="content-quote">
				<div class="flexbox">
					<div class="flexbox__item">
						<?php

						$content = get_the_content(
							wp_kses(
								__( 'Continue reading <span class="meta-nav">&rarr;</span>', 'hive-lite' ),
								array(
									'span' => array(
										'class' => array(),
									),
								)
							));

						//test if there is a </blockquote> tag in here
						if ( strpos($content,'</blockquote>') !== false ) {
							echo $content;
						} else {
							//we will wrap the whole content in blockquote since this is definitely intended as a quote
							echo '<blockquote>' . $content . '</blockquote>';
						} ?>
					</div>
				</div>
			</div>
		</div>

	</div>

	<?php edit_post_link( esc_html__( 'Edit', 'hive-lite' ), '<div class="edit-link">', '</div>' ); ?>

</article><!-- #post-## -->
