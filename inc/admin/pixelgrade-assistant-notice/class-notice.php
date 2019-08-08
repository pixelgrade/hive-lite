<?php
/**
 * Pixelgrade Assistant install/activate notice class.
 *
 * @link https://wordpress.org/plugins/pixelgrade-assistant/
 *
 * @package Hive Lite
 */

class PixelgradeAssistant_Install_Notice {

	/**
	 * The only instance.
	 * @var     PixelgradeAssistant_Install_Notice
	 * @access  protected
	 */
	protected static $_instance = null;

	public function __construct() {
		$this->addHooks();
	}

	public function addHooks() {

		if ( $this->shouldShow() ) {
			add_action( 'admin_notices', array( $this, 'outputMarkup' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'outputCSS' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'outputJS' ) );
		}

		add_action( 'wp_ajax_pixassist_install_dismiss_admin_notice', array( $this, 'dismiss_notice' ) );
		add_action( 'switch_theme', array( $this, 'cleanup' ) );
	}

	public function shouldShow() {
		global $pagenow;

		// If Pixelgrade Assistant or Pixelgrade Care is already installed and activated, nothing to do.
		if ( class_exists( 'PixelgradeAssistant' ) || class_exists( 'PixelgradeCare' ) ) {
			return false;
		}

		// We want to show it only on the themes.php page and in the dashboard.
		if ( ! in_array( $pagenow, array( 'themes.php', 'index.php' ) ) ) {
			return false;
		}

		// We also don't want to show it when viewing the TGMPA results or tables.
		if ( ! empty( $_GET['tgmpa-nonce'] ) ) {
			return false;
		}

		$dismissed = get_theme_mod( 'pixassist_install_notice_dismissed', false );
		// Earlier than a 7 days, we will not show again.
		if ( ! empty( $dismissed ) && ( time() - absint( $dismissed ) < DAY_IN_SECONDS * 7 ) ) {
			return false;
		}

		if ( current_user_can('manage_options') ) {
			return true;
		}

		return false;
	}

	public function outputMarkup() {
		$button_text = esc_html__( 'Install the Pixelgrade Assistant&reg; plugin', '__theme_txtd' );
		// Pixelgrade Assistant plugin installed, but not activated.
		if ( ! class_exists( 'PixelgradeAssistant' ) && file_exists( WP_PLUGIN_DIR . '/pixelgrade-assistant/pixelgrade-assistant.php' ) ) {
			$button_text = esc_html__( 'Activate the Pixelgrade Assistant&reg; plugin', '__theme_txtd' );
		} ?>
		<div class="notice notice-info is-dismissible pixelgrade-notice pixassist-notice" >
			<form class="pixassist-notice-form"
			      action="<?php echo admin_url( 'admin-ajax.php?action=pixassist_install_dismiss_admin_notice' ); ?>"
			      method="post">
				<noscript><input type="hidden" name="pixassist-notice-no-js" value="1"/></noscript>

				<?php
				$theme = wp_get_theme();
				$parent = $theme->parent();
				if ( $parent ) {
					$theme = $parent;
				}
				$screenshot = $theme->get_screenshot();
				if ( $screenshot ) { ?>
					<img class="pixassist-notice__screenshot" src="<?php echo $screenshot; ?>" width="1200" height="900" alt="Theme screenshot">
				<?php } ?>
				<div class="pixassist-notice__body">
					<h2><?php echo wp_kses( sprintf( __( 'Thanks for installing %s! Are you looking for a better experience to setup your site?', '__theme_txtd' ),  $theme->get( 'Name' ) ), wp_kses_allowed_html('post') ); ?></h2>
					<p><?php echo wp_kses( __('We\'ve prepared a special onboarding setup through our <strong>Pixelgrade Assistant plugin.</strong> It helps you get started and configure your upcoming website in style. Plus, you can <strong>search the theme documentation and open support tickets.</strong> Let\'s make it shine!', '__theme_txtd' ), wp_kses_allowed_html('post') ); ?></p>

					<p class="message js-plugin-message"></p>
					<a class="pixassist-notice-button js-handle-pixassist button button-primary" href="#"><span class="pixassist-notice-button__text"><?php echo $button_text ?></span></a>
					<button type="submit" class="button dismiss"><span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', '__theme_txtd' ); ?></span><?php esc_html_e( 'No thanks, I\'ll be fine', '__theme_txtd' ); ?></button>
				</div>
				<?php wp_nonce_field( 'pixassist_install_dismiss_admin_notice', 'nonce-pixassist_install-dismiss' ); ?>
			</form>
		</div>
	<?php
	}

	public function outputCSS() {
		wp_register_style( 'pixassist_notice_css', $this->get_parent_theme_file_uri( $this->get_theme_relative_path( __DIR__ ) . 'notice.css' ), false );
		wp_enqueue_style( 'pixassist_notice_css' );
	}

	public function outputJS() {
		wp_register_script( 'pixassist_notice_js', $this->get_parent_theme_file_uri( $this->get_theme_relative_path( __DIR__ ) . 'notice.js' ), array( 'jquery') );
		wp_enqueue_script( 'pixassist_notice_js' );

		$install_url = wp_nonce_url(
			add_query_arg(
				array(
					'plugin'        => urlencode( 'pixelgrade-assistant' ),
					'tgmpa-install' => 'install-plugin',
				),
				admin_url( 'themes.php?page=install-required-plugins' )
			),
			'tgmpa-install',
			'tgmpa-nonce'
		);
		// &amp; is not something that wp.ajax can actually handle
		$install_url = str_replace( 'amp;', '', $install_url );

		$activate_url = wp_nonce_url(
			add_query_arg(
				array(
					'plugin'        => urlencode( 'pixelgrade-assistant' ),
					'tgmpa-activate' => 'activate-plugin',
				),
				admin_url( 'themes.php?page=install-required-plugins' )
			),
			'tgmpa-activate',
			'tgmpa-nonce'
		);
		// &amp; is not something that wp.ajax can actually handle
		$activate_url = str_replace( 'amp;', '', $activate_url );

		$plugin_status = 'missing';
		// Pixelgrade Assistant plugin installed, but not activated.
		if ( class_exists( 'PixelgradeAssistant' ) ) {
			$plugin_status = 'active';
		} elseif ( file_exists( WP_PLUGIN_DIR . '/pixelgrade-assistant/pixelgrade-assistant.php' ) ) {
			$plugin_status = 'installed';
		}

		wp_localize_script( 'pixassist_notice_js', 'pixassistNotice', array(
			'ajaxurl' => esc_url( admin_url( 'admin-ajax.php' ) ),
			'installUrl' => esc_url_raw( $install_url ),
			'activateUrl' => esc_url_raw( $activate_url ),
			'themesPluginsUrl' => esc_url( admin_url( 'themes.php?page=install-required-plugins' ) ),
			'pixassistSetupUrl' => esc_url( admin_url( 'index.php?page=pixelgrade_assistant-setup-wizard' ) ),
			'status' => $plugin_status,
			'i18n' => array(
				'btnInstall' => esc_html__( 'Install the Pixelgrade Assistant&reg; plugin', '__theme_txtd' ),
				'btnInstalling' => esc_html__( 'Installing the Pixelgrade Assistant&reg; plugin...', '__theme_txtd' ),
				'btnActivate' => esc_html__( 'Activate the Pixelgrade Assistant&reg; plugin', '__theme_txtd' ),
				'btnActivating' => esc_html__( 'Activating the Pixelgrade Assistant&reg; plugin...', '__theme_txtd' ),
				'btnGoToSetup' => esc_html__( 'Click to start the Site Setup →', '__theme_txtd' ),
				'btnError' => esc_html__( 'Please refresh the page 🙏 and try again...', '__theme_txtd' ),
				'installedSuccessfully' => esc_html__( 'Plugin installed successfully.', '__theme_txtd' ),
				'activatedSuccessfully' => esc_html__( 'Plugin activated successfully.', '__theme_txtd' ),
				'clickStartTheSiteSetup' => esc_html__( 'Click to start the site setup provided by Pixelgrade Assistant&reg;.', '__theme_txtd' ),
				'folderAlreadyExists' => esc_html__( 'Plugin destination folder already exists.', '__theme_txtd' ),
				'error' => esc_html__( 'We are truly sorry 😢 Something went wrong and we couldn\'t make sense of it and continue with the plugin setup.', '__theme_txtd' ),
			),
		) );
	}

	/**
	 * Process ajax call to dismiss notice.
	 */
	public function dismiss_notice() {
		// Check nonce.
		check_ajax_referer( 'pixassist_install_dismiss_admin_notice', 'nonce-pixassist_install-dismiss' );

		// Remember the dismissal (time).
		set_theme_mod( 'pixassist_install_notice_dismissed', time());

		// Redirect if this is not an ajax request.
		if ( isset( $_POST['pixassist-notice-no-js'] ) ) {

			// Go back to where we came from.
			wp_safe_redirect( wp_get_referer() );
			exit();
		}

		wp_die();
	}

	public function cleanup() {
		// If the theme is about to be deactivated, we want to clear the notice dismissal so next time it is active, it will show.
		set_theme_mod( 'pixassist_install_notice_dismissed', false );
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

		$path = str_replace( trailingslashit( get_template_directory() ), '', $path );

		return trailingslashit( $path );
	}

	/**
	 * Retrieves the URL of a file in the parent theme.
	 *
	 * It will use the new function in WP 4.7, but will fallback to the old way of doing things otherwise.
	 *
	 * @param string $file Optional. File to return the URL for in the template directory.
	 * @return string The URL of the file.
	 */
	protected function get_parent_theme_file_uri( $file = '' ) {
		if ( function_exists( 'get_parent_theme_file_uri' ) ) {
			return get_parent_theme_file_uri( $file );
		} else {
			$file = ltrim( $file, '/' );

			if ( empty( $file ) ) {
				$url = get_template_directory_uri();
			} else {
				$url = get_template_directory_uri() . '/' . $file;
			}

			/**
			 * Filters the URL to a file in the parent theme.
			 *
			 * @since 4.7.0
			 *
			 * @param string $url The file URL.
			 * @param string $file The requested file to search for.
			 */
			return apply_filters( 'parent_theme_file_uri', $url, $file );
		}
	}

	public static function init() {
		return self::instance();
	}

	/**
	 * Main PixelgradeAssistant_Install_Notice Instance
	 *
	 * Ensures only one instance of PixelgradeAssistant_Install_Notice is loaded or can be loaded.
	 *
	 * @static
	 *
	 * @return PixelgradeAssistant_Install_Notice Main PixelgradeAssistant_Install_Notice instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Cloning is forbidden.
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'You should not do that!', '__theme_txtd' ), null );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'You should not do that!', '__theme_txtd' ), null );
	}
}
