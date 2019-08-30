<?php
/**
 * The header for our theme.
 * Displays all of the <head> section and everything up till <div id="content">
 * @package Hive
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="hfeed site">

	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'hive-lite' ); ?></a>

	<header id="masthead" class="site-header" role="banner">

		<div class="container">
			<div class="site-branding">
				<?php the_custom_logo(); ?>

				<h1 class="site-title <?php echo esc_attr( get_theme_mod( 'hive_title_size', 'site-title--large' ) ); ?>">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<?php bloginfo( 'name' ); ?>
					</a>
				</h1>

				<div class="site-description">
					<span class="site-description-text"><?php bloginfo( 'description' ); ?></span>
				</div>
			</div>

			<nav id="site-navigation" class="main-navigation" role="navigation">
				<h5 class="screen-reader-text"><?php esc_html_e( 'Main navigation', 'hive-lite' ); ?></h5>
				<?php
				$header_menu_args = array(
					'theme_location' => 'primary',
					'container'      => '',
					'menu_class'     => 'nav  nav--main',
					'fallback_cb' => false,
					'echo' => false,
				);
				$header_menu = wp_nav_menu( $header_menu_args );

				if( false !== $header_menu ) : ?>
					<button class="navigation__trigger">
						<span class="c-burger c-burger--fade">
							<b class="c-burger__slice c-burger__slice--top"></b>
							<b class="c-burger__slice c-burger__slice--middle"></b>
							<b class="c-burger__slice c-burger__slice--bottom"></b>
						</span>
						<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'hive-lite' ); ?></span>
					</button>
				<?php
					echo $header_menu; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				endif; ?>
			</nav><!-- #site-navigation -->
		</div>

	</header><!-- #masthead -->

	<div id="content" class="site-content">

		<div class="container">
