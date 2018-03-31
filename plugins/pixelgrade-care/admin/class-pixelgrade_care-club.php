<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class PixelgradeCare_Club {

	/**
	 * The main plugin object (the parent).
	 * @var     PixelgradeCare
	 * @access  public
	 * @since     1.3.0
	 */
	public $parent = null;

	/**
	 * The only instance.
	 * @var     PixelgradeCare_Club
	 * @access  protected
	 * @since   1.3.0
	 */
	protected static $_instance = null;

	private $is_club;
	private $license_status;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.3.0
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
		if ( false === apply_filters( 'pixcare_allow_club_module', true ) ) {
			return;
		}

		$license_status = get_theme_mod( 'pixcare_license_status' );
		$license_type   = get_theme_mod( 'pixcare_license_type' );

		$this->is_club        = ! empty( $license_type ) && $license_type == 'shop_bundle' ? true : false;
		$this->license_status = ! empty( $license_status ) ? $license_status : false;

		$this->register_hooks();
	}

	/**
	 * Register the hooks related to this module.
	 */
	public function register_hooks() {
		// Enqueue the Pixelgrade Club scripts
		add_action( 'wp_footer', array( $this, 'club_enqueue_fe_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'club_enqueue_admin_scripts' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'club_enqueue_admin_scripts' ) );
	}

	function club_enqueue_fe_scripts() {
		// If the license type is Pixelgrade club and license is expired enqueue the restriction notice scripts
		// We should only show this to logged in users that can actually do something about it
		if ( is_user_logged_in() && current_user_can( 'manage_options' ) && ( $this->is_club  &&  ! in_array( $this->license_status, array(
				'valid',
				'active'
			) ) || ! $this->license_status ) && ! pixcare_is_devmode() ) {
			wp_enqueue_script( 'pixelgrade_care_club_fe', plugin_dir_url( $this->parent->file ) . 'admin/js/club/club-fe.js', array(), $this->parent->get_version(), true );
			wp_enqueue_style( 'pixelgrade_fonts', '//pxgcdn.com/fonts/galanogrotesquealt/stylesheet.css', array(), $this->parent->get_version(), 'all' );
			wp_enqueue_style( 'pixelgrade_care_club_fe_css', plugin_dir_url( $this->parent->file ) . 'admin/css/club/pixelgrade_care-club-fe.css', array(), $this->parent->get_version(), 'all' );

			$this->club_footer_content();

			$this->localize_club_js_data( 'pixelgrade_care_club_fe' );
		}
	}

	function club_enqueue_admin_scripts() {
		// We should only show this to logged in users that can actually do something about it
		if ( is_user_logged_in() && current_user_can( 'manage_options' ) && $this->is_club && ! in_array( $this->license_status, array(
				'valid',
				'active'
			) ) && ! pixcare_is_devmode() ) {
			// If club license is expired - restrict the add new page view.

			if ( isset( $_GET['post_type'] ) && ! empty( $_GET['post_type'] ) && 'page' === $_GET['post_type'] || is_customize_preview() ) {
				wp_enqueue_style( 'pixelgrade_care_club_restrict', plugin_dir_url( $this->parent->file ) . 'admin/css/club/pixelgrade_care-club-restrict.css', array(), $this->parent->get_version(), 'all' );
			}
		}
	}

	private function localize_club_js_data( $handle = 'pixelgrade_care_club_fe' ) {
		$pixelgrade_club = array(
			'is_club'        => $this->is_club,
			'license_status' => $this->license_status
		);

		wp_localize_script( $handle, 'pixelgrade_club', $pixelgrade_club );
	}

	/**
	 * Output the content for the current step.
	 */
	public function club_footer_content() {
		?>
		<div id="pixelgrade_care_club_section"></div>
		<?php
	}

	/**
	 * Main PixelgradeCare_Club Instance
	 *
	 * Ensures only one instance of PixelgradeCare_Club is loaded or can be loaded.
	 *
	 * @since  1.3.0
	 * @static
	 *
	 * @param  object $parent Main PixelgradeCare instance.
	 *
	 * @return object Main PixelgradeCare_Club instance
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
