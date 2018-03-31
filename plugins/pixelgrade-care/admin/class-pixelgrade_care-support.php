<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    PixelgradeCare
 * @subpackage PixelgradeCare/admin
 * @author     Pixelgrade <email@example.com>
 */
class PixelgradeCare_Support {

	/**
	 * The main plugin object (the parent).
	 * @var     PixelgradeCare
	 * @access  public
	 * @since     1.3.0
	 */
	public $parent = null;

	/**
	 * The only instance.
	 * @var     PixelgradeCare_Admin
	 * @access  protected
	 * @since   1.3.0
	 */
	protected static $_instance = null;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $parent ) {
		$this->parent = $parent;

		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Initialize this module.
	 */
	public function init() {
		// Allow others to disable this module
		if ( false === apply_filters( 'pixcare_allow_support_module', true ) ) {
			return;
		}

		$this->register_hooks();
	}

	/**
	 * Register the hooks related to this module.
	 */
	public function register_hooks() {
		add_action( 'admin_footer', array( $this, 'support_setup' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'support_setup' ) );

		// Handle special cases where we will not load the support module
		add_filter( 'pixcare_allow_support_module', array( $this, 'disable_module_in_special_cases' ) );
	}

	public function support_setup() {
		// We don't show the Theme Help button and overlay if the current user can't manage options or if we are in the network admin sections on a multisite installation.
		$allow_support = current_user_can( 'manage_options' ) && ! is_network_admin();
		if ( false === apply_filters( 'pixcare_allow_support_module', $allow_support ) ) {
			return;
		}

		wp_enqueue_style( 'galanogrotesquealt', '//pxgcdn.com/fonts/galanogrotesquealt/stylesheet.css' );

		wp_enqueue_style( 'galanoclassic', '//pxgcdn.com/fonts/galanoclassic/stylesheet.css' );

		if ( is_rtl() ) {
			wp_enqueue_style( 'pixelgrade_care_style', plugin_dir_url( $this->parent->file ) . 'admin/css/pixelgrade_care-admin-rtl.css', array(), $this->parent->get_version(), 'all' );
		} else {
			wp_enqueue_style( 'pixelgrade_care_style', plugin_dir_url( $this->parent->file ) . 'admin/css/pixelgrade_care-admin.css', array(), $this->parent->get_version(), 'all' );
		}

		wp_enqueue_script( 'pixelgrade_care-support', plugin_dir_url( $this->parent->file ) . 'admin/js/support.js', array(
			'jquery',
			'wp-util',
			'updates'
		), $this->parent->get_version(), true );

		if ( ! wp_script_is('pixelgrade_care-dashboard') ) {
			PixelgradeCare_Admin::localize_js_data( 'pixelgrade_care-support' );
		}

		$this->support_content();
	}

	/**
	 * Handle special cases where for better user experience we will no allow the support module.
	 *
	 * Cases like plugins that introduce buttons where our Theme Support button is (eg. Press This).
	 *
	 * @param bool $allow_support
	 *
	 * @return bool
	 */
	public function disable_module_in_special_cases( $allow_support ) {
		// We may not always have access to get_current_screen()
		if ( function_exists( 'get_current_screen' ) ) {
			$current_screen = get_current_screen();
		}

		if ( ! empty( $current_screen ) ) {
			// If we are on a Press This page, don't allow the module since the Save button is exactly in the same place
			if ( false !== strpos( $current_screen->parent_file, 'press-this.php' ) || 'press-this' === $current_screen->base ) {
				return false;
			}
		}

		return $allow_support;
	}

	/**
	 * Output the content for the current step.
	 */
	public function support_content() {
		if ( ! current_user_can( 'manage_options' ) || is_network_admin() ) {
			return;
		} ?>
		<div id="pixelgrade_care_support_section"></div>
	<?php
	}

	protected static function get_kb_cache_key() {
		return 'pixcare_support_' . PixelgradeCare_Admin::get_original_theme_slug() . '_kb';
	}

	/**
	 * Retrieve the KnowledgeBase data from the server.
	 *
	 * @return array|mixed
	 */
	public static function get_knowledgeBase_data() {
		// First try and get the cached data
		$data = get_site_transient( self::get_kb_cache_key() );
		// The transient isn't set or is expired; we need to fetch fresh data
		if ( false === $data ) {
			$data = array(
				'categories' => self::fetch_kb_categories(),
			);

			// Sanitize it
			$data = PixelgradeCare_Admin::sanitize_theme_mods_holding_content( $data, array() );

			// Cache the data in a transient for 12 hours
			set_site_transient( self::get_kb_cache_key(), $data, 12 * HOUR_IN_SECONDS );
		}
		return $data;
	}

	/**
	 * Delete the cached KnowledgeBase data from the server.
	 *
	 * @return bool
	 */
	public static function clear_knowledgeBase_data_cache() {
		return delete_site_transient( self::get_kb_cache_key() );
	}

	/**
	 * Retrieve the KnowledgeBase categories from the server.
	 *
	 * @return array
	 */
	public static function fetch_kb_categories() {
		// Get existing categories
		$request_args = array(
			'method' => PixelgradeCare_Admin::$externalApiEndpoints['pxm']['getHTKBCategories']['method'],
			'timeout' => 4,
			'sslverify' => false, // there is no need to verify the SSL certificate - this is not sensitive data
		);
		// Add the slug of the theme to the request args so we will only receive data for the current theme
		$slug = ( ! isset( PixelgradeCare_Admin::$theme_support['template'] ) || null === PixelgradeCare_Admin::$theme_support['template'] ) ? basename( get_template_directory() ) : PixelgradeCare_Admin::$theme_support['template'];
		$request_args['body']['kb_current_product_sku'] = $slug;
		$categories = wp_remote_request( PixelgradeCare_Admin::$externalApiEndpoints['pxm']['getHTKBCategories']['url'], $request_args );

		if ( is_wp_error( $categories ) ) {
			return array();
		}
		$response = json_decode( wp_remote_retrieve_body( $categories ), true );

		$parsed_categories = array();
		if ( isset($response['code'] ) && $response['code'] == 'success' && isset( $response['data'] ) ) {
			$parsed_categories = $response['data']['htkb_categories'];
		}
		return $parsed_categories;
	}

	/**
	 * Determine if the current user has an active theme license and is allowed to use the support section
	 *
	 * @todo Is this needed anymore?
	 */
	private function _has_active_license() {
		$pixcare_options = PixelgradeCare_Admin::get_options();

		if ( ! isset( $pixcare_options['state'] ) && ! isset( $pixcare_options['state']['licenses'] ) ) {
			return false;
		}

		if ( empty( $pixcare_options['state']['licenses'] ) ) {
			return false;
		}

		if ( empty( $pixcare_options['state']['licenses'][0]['license_hash'] ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Main PixelgradeCare_Support Instance
	 *
	 * Ensures only one instance of PixelgradeCare_Support is loaded or can be loaded.
	 *
	 * @since  1.3.0
	 * @static
	 * @param  object $parent Main PixelgradeCare instance.
	 * @return object Main PixelgradeCare_Support instance
	 */
	public static function instance( $parent ) {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $parent );
		}
		return self::$_instance;
	} // End instance().

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {

		_doing_it_wrong( __FUNCTION__, esc_html( __( 'Cheatin&#8217; huh?' ) ), esc_html( $this->parent->get_version() ) );
	} // End __clone().

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {

		_doing_it_wrong( __FUNCTION__, esc_html( __( 'Cheatin&#8217; huh?' ) ), esc_html( $this->parent->get_version() ) );
	} // End __wakeup().
}
