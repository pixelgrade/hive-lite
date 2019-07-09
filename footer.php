<?php
/**
 * The template for displaying the footer.
 * Contains the closing of the #content div and all content after
 * @package Hive Lite
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
</div><!-- .container -->

</div><!-- #content -->

<footer id="colophon" class="site-footer" role="contentinfo">
	<div class="container">
		<div class="grid">
			<div class="grid__item  site-info">
				<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'hive-lite' ) ); ?>"><?php
					/* translators: %s: WordPress */
					printf( esc_html__( 'Proudly powered by %s', 'hive-lite' ), 'WordPress' ); ?></a>
				<span class="sep"> | </span>
				<?php
				/* translators: %1$s: The theme name, %2$s: The theme author name. */
				printf( esc_html__( 'Theme: %1$s by %2$s.', 'hive-lite' ), 'Hive Lite', '<a href="https://pixelgrade.com/?utm_source=hive-lite-clients&utm_medium=footer&utm_campaign=hive-lite" title="' . esc_html__( 'The Pixelgrade Website', 'hive-lite' ) . '" rel="nofollow">Pixelgrade</a>' ); ?>
			</div><!-- .site-info -->

			<div class="grid__item  footer-navigation">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'container'      => '',
						'menu_class'     => 'nav  nav--footer',
						'items_wrap'         => '<nav><h5 class="screen-reader-text">' . esc_html__( 'Footer navigation', 'hive-lite' ) . '</h5><ul id="%1$s" class="%2$s">%3$s</ul></nav>',
					)
				); ?>
			</div>
		</div>
	</div><!-- .site-footer .container -->
</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
