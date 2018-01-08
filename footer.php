<?php
/**
 * The template for displaying the footer.
 * Contains the closing of the #content div and all content after
 * @package Hive
 */ ?>
</div><!-- .container -->

</div><!-- #content -->

<footer id="colophon" class="site-footer" role="contentinfo">
	<div class="container">
		<div class="grid">
			<div class="grid__item  site-info">
				<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'hive_txtd' ) ); ?>"><?php printf( __( 'Proudly powered by %s', 'hive_txtd' ), 'WordPress' ); ?></a>
				<span class="sep"> | </span>
				<?php printf( __( 'Theme: %1$s by %2$s.', 'hive_txtd' ), 'Hive', '<a href="http://pixelgrade.com" rel="designer">PixelGrade</a>' ); ?>
			</div><!-- .site-info -->

			<div class="grid__item  footer-navigation">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'container'      => '',
						'menu_class'     => 'nav  nav--footer',
						'items_wrap'         => '<nav><h5 class="screen-reader-text">'.__( 'Footer navigation', 'hive_txtd' ).'</h5><ul id="%1$s" class="%2$s">%3$s</ul></nav>',
					)
				); ?>
			</div>
		</div>
	</div><!-- .site-footer .container -->
</footer><!-- #colophon -->
</div><!-- #page -->

<?php get_template_part( 'templates/toolbar' );
wp_footer();?>

</body>
</html>