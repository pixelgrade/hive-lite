<?php
/**
 * Pixelgrade Care download notice class.
 *
 * @package Hive Lite
 */

class HiveLite_PixelgradeCare_DownloadNotice {

	protected $download_url;

	/**
	 * The only instance.
	 * @var     HiveLite_PixelgradeCare_DownloadNotice
	 * @access  protected
	 */
	protected static $_instance = null;

	public function __construct() {

		$protocol = 'http:';
		if ( is_ssl() ) {
			$protocol = 'https:';
		}
		// This URL will always deliver the latest version of the plugin.
		$this->download_url = $protocol . '//wupdates.com/api_wupl_version/JxbVe/2v5t1czd3vw4kmb5xqmyxj1kkwmnt9q0463lhj393r5yxtshdyg05jssgd4jglnfx7A2vdxtfdcf78r9r1sm217k4ht3r2g7pkdng5f6tgwyrk23wryA0pjxvs7gwhhb';

		$this->addHooks();
	}

	public function addHooks() {
        global $pagenow;

		if ( $this->shouldShow() ) {
			add_action( 'admin_notices', array( $this, 'outputThemesMarkup' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'outputCSS' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'outputJS' ) );
		}

		add_action( 'wp_ajax_pixcare_download_dismiss_admin_notice', array( $this, 'dismiss_notice' ) );
		add_action( 'switch_theme', array( $this, 'cleanup' ) );
	}

	public function shouldShow() {
		global $pagenow;

		// If Pixelgrade Care is already installed and activated, nothing to do.
		if ( class_exists( 'PixelgradeCare' ) ) {
			return false;
		}

		// We want to show it only on the themes.php page.
		if ( 'themes.php' !== $pagenow ) {
			return false;
		}

		// We also don't want to show it when viewing the TGMPA results or tables.
		if ( ! empty( $_GET['tgmpa-nonce'] ) ) {
			return false;
		}

		$timestamp = get_theme_mod( 'pixcare_download_notice_dismissed_timestamp', false );
		$times = get_theme_mod( 'pixcare_download_notice_dismissed_times', 0 );
		if ( ! empty( $timestamp ) && ( time() - absint( $timestamp ) < WEEK_IN_SECONDS * $times ) ) {
			return false;
		}

		// Only show it for people who can actually do something about it.
		if ( current_user_can('manage_options') ) {
			return true;
		}

		return false;
	}

	public function outputThemesMarkup() {
		$button_text = __( 'Download the Pixelgrade Care plugin for Free', '__theme_txtd' );
		?>
		<div class="pixcare-notice__container notice notice--huge is-dismissible" >

			<ul class="pxg-wizard">
				<li class="pxg-wizard__step pxg-wizard__step--done">
					<span class="pxg-wizard__label"><?php esc_html_e( 'Theme', '__theme_txtd' ); ?></span>
					<span class="pxg-wizard__progress"><b></b></span>
				</li>
				<li class="pxg-wizard__step pxg-wizard__step--current">
					<span class="pxg-wizard__label"><?php esc_html_e( 'Pixelgrade Care', '__theme_txtd' ); ?></span>
					<span class="pxg-wizard__progress"><b></b></span>
				</li>
				<li class="pxg-wizard__step">
					<span class="pxg-wizard__label"><?php esc_html_e( 'Site setup', '__theme_txtd' ); ?></span>
					<span class="pxg-wizard__progress"><b></b></span>
				</li>
				<li class="pxg-wizard__step">
					<span class="pxg-wizard__label"><?php esc_html_e( 'Ready!', '__theme_txtd' ); ?></span>
					<span class="pxg-wizard__progress"><b></b></span>
				</li>
			</ul>
			<form class="pixcare-notice-form"
			      action="<?php echo esc_url( admin_url( 'admin-ajax.php?action=pixcare_download_dismiss_admin_notice' ) ); ?>"
			      method="post">
				<noscript><input type="hidden" name="pixcare-notice-no-js" value="1" /></noscript>

				<div class="pixcare-notice__wrap pixcare-notice--download">
					<div class="pixcare-notice__media">
                        <div class="pixcare-notice__screenshot">
                            <?php
                            $theme = wp_get_theme();
                            if ( $theme->parent() ) {
                                $theme = $theme->parent();
                            }
                            $screenshot = $theme->get_screenshot();
                            if ( $screenshot ) { ?>
                                <img src="<?php echo esc_url( $screenshot ); ?>" alt="<?php esc_attr_e( 'Theme screenshot', '__theme_txtd' ); ?>">
                            <?php } ?>
                        </div>
					</div>
					<div class="pixcare-notice__body">
						<h1><?php
							/* translators: %s: The theme name. */
							echo wp_kses( sprintf( __( 'Thank you for installing %s!<br/>Let\'s make an experience out of it.', '__theme_txtd' ),  $theme->get( 'Name' ) ), wp_kses_allowed_html('post') ); ?></h1>
						<p><?php echo wp_kses( __( 'We\'ve created a special onboarding setup through our <strong>Pixelgrade Care plugin</strong>. It helps you get started and configure your upcoming website in style. Let\'s make it shine!', '__theme_txtd' ), wp_kses_allowed_html('post') ); ?></p>
						<ul>
							<li>
								<i></i><span><?php echo wp_kses( __('<strong>Recommended plugins</strong> to boost your site.', '__theme_txtd' ), wp_kses_allowed_html() ); ?></span>
							</li>
							<li>
								<i></i><span><?php echo wp_kses( __('<strong>Starter Content</strong> to make your site look like the demo.', '__theme_txtd' ), wp_kses_allowed_html() ); ?></span>
							</li>
							<li>
								<i></i><span><?php echo wp_kses( __('<strong>Premium Support</strong> to assist you all the way.', '__theme_txtd' ), wp_kses_allowed_html() ); ?></span>
							</li>
						</ul>
						<div class="message js-plugin-message"></div>
						<a class="pixcare-notice-button js-handle-pixcare" href="<?php echo esc_url( $this->download_url ); ?>">
                            <span class="pixcare-notice-button__text"><?php echo esc_html( $button_text ); ?></span>
                        </a>

						<noscript>
							<button type="submit" class="notice-dismiss"><span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', '__theme_txtd' ); ?></span></button>
						</noscript>
					</div>
				</div>

				<div class="pixcare-notice__wrap pixcare-notice--thankyou pixcare-notice--hidden">
					<div class="pixcare-notice__media">
						<div class="pixcare-notice__screenshot">
							<?php
							$thank_you_image = get_parent_theme_file_uri( $this->get_theme_relative_path( __DIR__ ) . 'thank-you.png' );
							?>
							<img src="<?php echo esc_url( $thank_you_image ); ?>"
							     alt="<?php esc_attr_e( 'Thank you for downloading', '__theme_txtd' ); ?>">

						</div>
					</div>
					<div class="pixcare-notice__body">
						<h1><?php echo wp_kses( __( 'Thanks for downloading Pixelgrade Care!<br/>Let\'s install it and make the most out of it.', '__theme_txtd' ), array( 'br' => array() ) ); ?></h1>
						<p><?php esc_html_e('Installing Pixelgrade Care works like any other WordPress plugin. Go to your Plugins page, upload, then activate:', '__theme_txtd' ); ?></p>
						<ol>
							<li>
								<i></i><span><?php echo wp_kses( __('Go to <strong>Plugins » Add New</strong> page and click on the <strong>Upload Plugin</strong> button', '__theme_txtd' ), wp_kses_allowed_html() ); ?></span>
							</li>
							<li>
								<i></i><span><?php echo wp_kses( __('<strong>Select the .zip plugin file</strong> you\'ve just downloaded to your computer', '__theme_txtd' ), wp_kses_allowed_html() ); ?></span>
							</li>
							<li>
								<i></i><span><?php echo wp_kses( __('Click on the <strong>Install Now</strong> button then <strong>Activate Plugin</strong> to start using it', '__theme_txtd' ), wp_kses_allowed_html() ); ?></span>
							</li>
						</ol>
						<div class="message js-plugin-message"></div>
						<a href="<?php echo esc_url( admin_url( 'plugin-install.php?tab=upload' ) ); ?>" class="pixcare-notice-button button--primary">
                            <span class="pixcare-notice-button__text"><?php esc_html_e( 'Go to Plugins page to install →', '__theme_txtd' ); ?></span>
                        </a>

						<noscript>
							<button type="submit" class="notice-dismiss"><span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', '__theme_txtd' ); ?></span></button>
						</noscript>
					</div>
				</div>
				<?php wp_nonce_field( 'pixcare_download_dismiss_admin_notice', 'nonce-pixcare_download-dismiss' ); ?>
			</form>
		</div>
	<?php
	}

	public function outputCSS() {
		wp_register_style( 'pixcare_notice_css', get_parent_theme_file_uri( $this->get_theme_relative_path( __DIR__ ) . 'notice.css' ) );
		wp_enqueue_style( 'pixcare_notice_css' );
	}

	public function outputJS() {
		wp_register_script( 'pixcare_notice_js', get_parent_theme_file_uri( $this->get_theme_relative_path( __DIR__ ) . 'notice.js' ), array( 'jquery') );
		wp_enqueue_script( 'pixcare_notice_js' );

		wp_localize_script( 'pixcare_notice_js', 'pixcareNotice', array(
			'ajaxurl' => esc_url( admin_url( 'admin-ajax.php' ) ),
		) );
	}

	/**
	 * Process ajax call to dismiss notice.
	 */
	public function dismiss_notice() {
		// Check nonce.
		check_ajax_referer( 'pixcare_download_dismiss_admin_notice', 'nonce_dismiss' );

		// Remember the dismissal (time).
		set_theme_mod( 'pixcare_download_notice_dismissed_timestamp', time() );
		set_theme_mod( 'pixcare_download_notice_dismissed_times', get_theme_mod( 'pixcare_download_notice_dismissed_times', 0 ) + 1 );

		// Redirect if this is not an ajax request.
		if ( isset( $_POST['pixcare-notice-no-js'] ) ) {

			// Go back to where we came from.
			wp_safe_redirect( wp_get_referer() );
			exit();
		}

		wp_die();
	}

	public function cleanup() {
		set_theme_mod( 'pixcare_download_notice_dismissed_timestamp', false );
		set_theme_mod( 'pixcare_download_notice_dismissed_times', 0 );
	}

	/**
	 * Get the relative theme path of a given absolute path. In case the given path is not absolute, it is returned as received.
	 *
	 * @param $path string An absolute path.
	 *
	 * @return string A path relative to the current theme directory, without ./ in front.
	 */
	protected function get_theme_relative_path( $path ) {
		if ( empty( $path ) ) {
			return '';
		}

		$path = str_replace( trailingslashit( wp_normalize_path( get_template_directory() ) ), '', wp_normalize_path( $path ) );

		return trailingslashit( $path );
	}

	public static function init() {
		return self::instance();
	}

	/**
	 * Main HiveLite_PixelgradeCare_DownloadNotice Instance
	 *
	 * Ensures only one instance of HiveLite_PixelgradeCare_DownloadNotice is loaded or can be loaded.
	 *
	 * @static
	 *
	 * @return HiveLite_PixelgradeCare_DownloadNotice Main HiveLite_PixelgradeCare_DownloadNotice instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	} // End instance().

	/**
	 * Cloning is forbidden.
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_html( __( 'Cheatin&#8217; huh?', '__theme_txtd' ) ), null );
	} // End __clone().

	/**
	 * Unserializing instances of this class is forbidden.
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html( __( 'Cheatin&#8217; huh?', '__theme_txtd' ) ), null );
	} // End __wakeup().
}
