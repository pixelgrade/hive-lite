<?php
/**
 * The template for the featured image hover on archives.
 * @package Hive Lite
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<a class="hover" href="<?php the_permalink(); ?>">
	<span class="hover__bg"></span>

	<div class="flexbox">
		<div class="flexbox__item">
			<span class="hover__line  hover__line--top"></span>
			<b class="hover__letter"><?php echo esc_html( hivelite_first_site_title_character() ); ?></b>
			<b class="hover__letter-mask"><span><?php echo esc_html( hivelite_first_site_title_character() ); ?></span></b>
			<span class="hover__more"><?php esc_html_e( 'Read More', 'hive-lite' ) ?></span>
			<span class="hover__line  hover__line--bottom"></span>
		</div>
	</div>
</a>
