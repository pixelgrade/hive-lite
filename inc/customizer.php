<?php
	/**
	 * Hive Lite Theme Customizer
	 * @package Hive Lite
	 */


	/**
	 * Change some default texts and add our own custom settings
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	function hivelite_customize_register( $wp_customize ) {

		/*
		 * Change defaults
		 */

		// Add postMessage support for site title and tagline and title color.
		$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

		// Rename the label to "Display Site Title & Tagline" in order to make this option clearer.
		$wp_customize->get_control( 'display_header_text' )->label = esc_html__( 'Display Site Title &amp; Tagline', 'hive-lite' );

		// Add a pretty icon to Site Identity
		$wp_customize->get_section('title_tagline')->title = '&#x1f465; ' . esc_html__('Site Identity', 'hive-lite');

		// View Pro
		$wp_customize->add_section( 'hivelite_style_view_pro', array(
			'title'       => '' . esc_html__( 'View PRO Version', 'hive-lite' ),
			'priority'    => 2,
			'description' => sprintf(
				__( '<div class="upsell-container">
					<h2>Need More? Go PRO</h2>
					<p>Take it to the next level. See the features below:</p>
					<ul class="upsell-features">
                            <li>
                            	<h4>Personalize to Match Your Style</h4>
                            	<div class="description">Having different tastes and preferences might be tricky for users, but not with Hive onboard. It has an intuitive and catchy interface which allows you to change <strong>fonts, colors or layout sizes</strong> in a blink of an eye.</div>
                            </li>

                            <li>
                            	<h4>Featured Posts Slider</h4>
                            	<div class="description">Bring your best stories in the front of the world by adding them into the posts slider. It’s an extra opportunity to grab attention and to refresh some content that is still meaningful. Don’t miss any occasion to increase the value of your stories.</div>
                            </li>

                            <li>
                            	<h4>Custom Mega Menu</h4>
                            	<div class="description">Showcase the latest posts from a category under menu without losing precious time and money. Highlight those articles you feel proud about with no effort and let people know about your appetite for a topic or another.</div>
                            </li>

                            <li>
                            	<h4>Premium Customer Support</h4>
                            	<div class="description">You will benefit by priority support from a caring and devoted team, eager to help and to spread happiness. We work hard to provide a flawless experience for those who vote us with trust and choose to be our special clients.</div>
                            </li>
                            
                    </ul> %s </div>', 'hive-lite' ),
				sprintf( '<a href="%1$s" target="_blank" class="button button-primary">%2$s</a>', esc_url( hivelite_get_pro_link() ), esc_html__( 'View Hive PRO', 'hive-lite' ) )
			),
		) );

		$wp_customize->add_setting( 'hivelite_style_view_pro_desc', array(
			'default'           => '',
			'sanitize_callback' => 'hivelite_sanitize_checkbox',
		) );
		$wp_customize->add_control( 'hivelite_style_view_pro_desc', array(
			'section' => 'hivelite_style_view_pro',
			'type'    => 'hidden',
		) );


		// Style Presets
		$wp_customize->add_section( 'hivelite_style_presets', array(
			'title'       => '&#x1f3ad; ' . esc_html__( 'Style Presets', 'hive-lite' ),
			'priority'    => 29,
			'description' => sprintf(
				__( '<p>%s provides you hand-crafted style presets so that you never go out of trends and add some real value to the full package. You can instantly achieve a different visual approach and level up the users interest. </p><p> Our designer did his best to carefully match the colors and fonts so that you can easily refresh the overall style of your website.</p>', 'hive-lite' ),
				sprintf( '<a href="%1$s" target="_blank">%2$s</a>', esc_url( hivelite_get_pro_link() ), esc_html__( 'Hive Pro', 'hive-lite' ) )
			)
		) );

		$wp_customize->add_setting( 'hivelite_style_presets_desc', array(
			'default'           => '',
			'sanitize_callback' => 'hivelite_sanitize_checkbox',
		) );
		$wp_customize->add_control( 'hivelite_style_presets_desc', array(
			'section' => 'hivelite_style_presets',
			'type'    => 'hidden',
		) );


		// Colors
		$wp_customize->add_section( 'hivelite_colors', array(
			'title'       => '&#x1f3a8; ' . esc_html__( 'Colors', 'hive-lite' ),
			'priority'    => 30,
			'description' => sprintf(
				__( '<p>Play around with colors that fits your vision, your mood or both of them. You can smoothly make a design twist to quickly catch your wide preferences.</p><p>%s to switch colors and fonts in order to nurture your visual approach.</p>', 'hive-lite' ),
				sprintf( '<a href="%1$s" target="_blank">%2$s</a>', esc_url( hivelite_get_pro_link() ), esc_html__( 'Upgrade to Hive Pro', 'hive-lite' )
				)
			)
		) );

		$wp_customize->add_setting( 'hivelite_colors_desc', array(
			'default'           => '',
			'sanitize_callback' => 'hivelite_sanitize_checkbox',
		) );
		$wp_customize->add_control( 'hivelite_colors_desc', array(
			'section' => 'hivelite_colors',
			'type'    => 'hidden',
		) );

		// Fonts
		$wp_customize->add_section( 'hivelite_fonts', array(
			'title'       => '&#x1f4dd; ' . esc_html__( 'Fonts', 'hive-lite' ),
			'priority'    => 31,
			'description' => sprintf(
				__( '<p>Typography can make it or break it. %s gives you a generous playground to match your needs in terms of fonts and sizes.</p><p>You have full-access to 600+ Google Fonts to mingle with for fine-tuning your style.', 'hive-lite' ),
				sprintf( '<a href="%1$s" target="_blank">%2$s</a>', esc_url( hivelite_get_pro_link() ), esc_html__( 'Hive Pro', 'hive-lite' )
				)
			)
		) );


		$wp_customize->add_setting( 'hivelite_fonts_desc', array(
			'default'           => '',
			'sanitize_callback' => 'hivelite_sanitize_checkbox',
		) );
		$wp_customize->add_control( 'hivelite_fonts_desc', array(
			'section' => 'hivelite_fonts',
			'type'    => 'hidden',
		) );

	}

	add_action( 'customize_register', 'hivelite_customize_register', 15 );

	/**
	 * Sanitize the checkbox.
	 *
	 * @param boolean $input .
	 *
	 * @return boolean true if is 1 or '1', false if anything else
	 */
	function hivelite_sanitize_checkbox( $input ) {
		if ( 1 == $input ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Sanitize the Site Title Outline value.
	 *
	 * @param string $outline Outline thickness.
	 *
	 * @return string Filtered outline (0|1|2|3).
	 */
	function hivelite_sanitize_site_title_outline( $outline ) {
		if ( ! in_array( $outline, array( '0', '1.2', '3', '5', '10' ) ) ) {
			$outline = '3';
		}

		return $outline;
	}

	/**
	 * JavaScript that handles the Customizer AJAX logic
	 * This will be added in the preview part
	 */
	function hivelite_customizer_preview_assets() {
		wp_enqueue_script( 'hivelite_customizer_preview', get_template_directory_uri() . '/assets/js/customizer_preview.js', array( 'customize-preview' ), '1.0.4', true );
	}

	add_action( 'customize_preview_init', 'hivelite_customizer_preview_assets' );

	/**
	 * Assets that will be loaded for the customizer sidebar
	 */
	function hivelite_customizer_assets() {
		wp_enqueue_style( 'hivelite_customizer_style', get_template_directory_uri() . '/assets/css/admin/customizer.css', null, '1.0.4', false );

		wp_enqueue_script( 'hivelite_customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'jquery' ), '1.0.4', false );

		// uncomment this to put back your dismiss notice
		// update_user_meta( get_current_user_id(), 'hive_upgrade_dismissed_notice', 0 );
		if ( isset( $_GET['hive-upgrade-dismiss'] ) && check_admin_referer( 'hive-upgrade-dismiss-' . get_current_user_id() ) ) {
			update_user_meta( get_current_user_id(), 'hive_upgrade_dismissed_notice', 'forever' );
			return;
		}

		$dismiss_user = get_user_meta( get_current_user_id(), 'hive_upgrade_dismissed_notice', true );
		if ( $dismiss_user === 'forever' ) {
			return;
		} elseif ( empty( $dismiss_user ) || ( is_numeric( $dismiss_user ) && $dismiss_user < 2  ) ) {

			$value = $dismiss_user + 1;
			update_user_meta( get_current_user_id(), 'hive_upgrade_dismissed_notice', $value );
			return;
		}

		$localized_strings = array(
			'upsell_link'     => hivelite_get_pro_link(),
			'upsell_label'    => esc_html__( 'Upgrade to Hive Pro', 'hive-lite' ),
			'pro_badge_label' => esc_html__( 'Pro', 'hive-lite' ) . '<span class="star"></span>',
			'dismiss_link' => esc_url( wp_nonce_url( add_query_arg( 'hive-upgrade-dismiss', 'forever' ), 'hive-upgrade-dismiss-' . get_current_user_id() ) )
		);

		wp_localize_script( 'hivelite_customizer', 'hiveCustomizerObject', $localized_strings );
	}

	add_action( 'customize_controls_enqueue_scripts', 'hivelite_customizer_assets' );

	/**
	 * Generate a link to the Hive Lite info page.
	 */
	function hivelite_get_pro_link() {
		return 'https://pixelgrade.com/themes/blogging/hive-lite?utm_source=hive-lite-clients&utm_medium=customizer&utm_campaign=hive-lite#pro';
	}

	function hive_add_customify_options( $config ) {

		$config['sections'] = array();
		$config['panels'] = array();

		return $config;
	}
	add_filter( 'customify_filter_fields', 'hive_add_customify_options' );
?>