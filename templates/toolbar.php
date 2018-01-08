<?php
/**
 * The template for the lateral toolbar.
 * @package Hive
 */ ?>
	<div class="toolbar">
		<div class="toolbar__head">
			<nav id="social-navigation" class="toolbar-navigation" role="navigation">
				<h5 class="screen-reader-text"><?php _e( 'Secondary navigation', 'hive_txtd' ); ?></h5>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'social',
						'container'      => '',
						'menu_class'     => 'nav  nav--social  nav--toolbar',
						'depth'          => - 1, //flatten if there is any hierarchy
						'fallback_cb'    => false,
					)
				);

				if ( ! get_theme_mod( 'hive_disable_search_in_toolbar', false ) ) { ?>
					<ul class="nav  nav--toolbar">
						<li class="nav__item--search"><a href="#"><?php _e( 'Search', 'hive_txtd' ); ?></a></li>
					</ul>
				<?php } ?>
			</nav>
			<!-- #social-navigation -->
		</div>
		<div class="toolbar__body">
			<?php hive_post_nav(); ?>
		</div>
	</div>
<?php
if ( ! get_theme_mod( 'hive_disable_search_in_toolbar', false ) ) { ?>
	<div class="overlay--search">
		<div class="overlay__wrapper">
			<?php get_search_form(); ?>
			<p><?php _e( 'Begin typing your search above and press return to search. Press Esc to cancel.', 'hive_txtd' ); ?></p>
		</div>
		<b class="overlay__close"></b>
	</div>
<?php } ?>