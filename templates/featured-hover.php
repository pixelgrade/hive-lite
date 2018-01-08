<?php
/**
 * The template for the featured image hover on archives.
 * @package Hive
 */

?>
<a class="hover" href="<?php the_permalink(); ?>">
	<span class="hover__bg"></span>

	<div class="flexbox">
		<div class="flexbox__item">
			<span class="hover__line  hover__line--top"></span>
			<b class="hover__letter"><?php echo esc_html( hive_first_site_title_character() ); ?></b>
			<b class="hover__letter-mask"><span><?php echo esc_html( hive_first_site_title_character() ); ?></span></b>
			<span class="hover__more"><?php _e( 'Read More', 'hive_txtd' ) ?></span>
			<span class="hover__line  hover__line--bottom"></span>
		</div>
	</div>
</a>