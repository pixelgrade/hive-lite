<?php
/**
 * The header for our theme.
 * Displays all of the <head> section and everything up till <div id="content">
 * @package Hive
 */
?><!DOCTYPE html>
<!--[if IE 9]>
<html class="ie9 lt-ie10" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html <?php language_attributes(); ?>> <!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, user-scalable=no">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<?php wp_head(); ?>

	<!--[if !IE]><!-->
	<script>
		if (/*@cc_on!@*/false) {
			document.documentElement.className += ' ie10';
		}
	</script>
	<!--<![endif]-->
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">

	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'hive_txtd' ); ?></a>

	<header id="masthead" class="site-header" role="banner">

		<div class="container">
			<div class="site-branding">
				<?php if ( function_exists( 'jetpack_the_site_logo' ) ) jetpack_the_site_logo(); ?>

				<h1 class="site-title <?php echo get_theme_mod( 'hive_title_size', 'site-title--large' ); ?>">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<?php bloginfo( 'name' ); ?>
					</a>
				</h1>

				<div class="site-description">
					<span class="site-description-text"><?php bloginfo( 'description' ); ?></span>
				</div>
			</div>

			<nav id="site-navigation" class="main-navigation" role="navigation">
				<h5 class="screen-reader-text"><?php _e( 'Main navigation', 'hive_txtd' ); ?></h5>
				<?php
				$menu_args = array(
					'theme_location' => 'primary',
					'container'      => '',
					'menu_class'     => 'nav  nav--main',
					'fallback_cb' => false,
					'echo' => false,
				);
				$menu = wp_nav_menu( $menu_args );

				if( false !== $menu ) : ?>
					<button class="navigation__trigger">
						<i class="fa fa-bars"></i><span class="screen-reader-text"><?php _e( 'Menu', 'hive_txtd' ); ?></span>
					</button>
				<?php
					echo $menu;
				endif; ?>
			</nav><!-- #site-navigation -->
		</div>

	</header><!-- #masthead -->

	<div id="content" class="site-content">

		<div class="container">