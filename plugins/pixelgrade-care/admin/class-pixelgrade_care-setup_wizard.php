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
class PixelgradeCare_SetupWizard {

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
		if ( false === apply_filters( 'pixcare_allow_setup_wizard_module', true ) ) {
			return;
		}

		$this->register_hooks();
	}

	/**
	 * Register the hooks related to this module.
	 */
	public function register_hooks() {
		add_action( 'current_screen', array( $this, 'add_tabs' ) );
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'setup_wizard' ) );
	}

	/**
	 * Add Contextual help tabs.
	 */
	public function add_tabs() {
		$screen = get_current_screen();

		$screen->add_help_tab( array(
			'id'      => 'pixelgrade_care_setup_wizard_tab',
			'title'   => __( 'Pixelgrade Care Setup', 'pixelgrade_care' ),
			'content' =>
				'<h2>' . __( 'Pixelgrade Care Setup', 'pixelgrade_care' ) . '</h2>' .
				'<p><a href="' . admin_url( 'index.php?page=pixelgrade_care-setup-wizard' ) . '" class="button button-primary">' . __( 'Setup Pixelgrade Care', 'pixelgrade_care' ) . '</a></p>'

		) );
	}

	public function add_admin_menu() {
		add_submenu_page( null, '', '', 'manage_options', 'pixelgrade_care-setup-wizard', null );
	}

	public function setup_wizard() {
		$allow_setup_wizard = $this->is_pixelgrade_care_setup_wizard() && current_user_can( 'manage_options' );
		if ( false === apply_filters( 'pixcare_allow_setup_wizard_module', $allow_setup_wizard ) ) {
			return;
		}

		wp_enqueue_style( 'galanogrotesquealt', '//pxgcdn.com/fonts/galanogrotesquealt/stylesheet.css' );
		wp_enqueue_style( 'galanoclassic', '//pxgcdn.com/fonts/galanoclassic/stylesheet.css' );

		if ( is_rtl() ) {
			wp_enqueue_style( 'pixelgrade_care_style', plugin_dir_url( $this->parent->file ) . 'admin/css/pixelgrade_care-admin-rtl.css', array(), $this->parent->get_version(), 'all' );
		} else {
			wp_enqueue_style( 'pixelgrade_care_style', plugin_dir_url( $this->parent->file ) . 'admin/css/pixelgrade_care-admin.css', array(), $this->parent->get_version(), 'all' );
		}


		wp_enqueue_script( 'pixelgrade_care-setup-wizard', plugin_dir_url( $this->parent->file ) . 'admin/js/setup_wizard.js', array(
			'jquery',
			'wp-util',
			'updates'
		), $this->parent->get_version(), true );

		// Analytics Code
		// Only enqueue the analytics if we are allowed to
		if ( is_admin() && true === apply_filters( 'pixcare_allow_data_collector_module', PixelgradeCare_Admin::get_option( 'allow_data_collect', false ) ) ) {
			wp_enqueue_script( 'pixelgrade_care-analytics', plugin_dir_url( $this->parent->file ) . 'admin/js/analytics.js', $this->parent->get_version(), true );
		}

		PixelgradeCare_Admin::localize_js_data( 'pixelgrade_care-setup-wizard' );

		update_option( 'pixelgrade_care_version', $this->parent->get_version() );
		// Delete redirect transient
		$this->delete_redirect_transient();

		ob_start();
		$this->setup_wizard_header();
		$this->setup_wizard_content();
		$this->setup_wizard_footer();
		exit;
	}

	/**
	 * Setup Wizard Header.
	 */
	public function setup_wizard_header() {
		global $title, $hook_suffix, $current_screen, $wp_locale, $pagenow,
		       $update_title, $total_update_count, $parent_file;

		if ( empty( $current_screen ) ) {
			set_current_screen();
		} ?>
		<!DOCTYPE html>
		<html <?php language_attributes(); ?>>
		<head>
			<meta name="viewport" content="width=device-width"/>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
			<title><?php _e( 'Pixelgrade Care &rsaquo; Setup Wizard', 'pixelgrade_care' ); ?></title>
			<script type="text/javascript">
				var ajaxurl = '<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>',
					pagenow = 'plugins';
			</script>
		</head>
		<body class="pixelgrade_care-setup wp-core-ui">

		<?php
	}

	/**
	 * Output the content for the current step.
	 */
	public function setup_wizard_content() { ?>
		<div class="pixelgrade_care-wrapper">
			<div id="pixelgrade_care_setup_wizard"></div>
			<div id="valdationError"></div>
		</div>
	<?php }

	public function setup_wizard_footer() { ?>
		<?php
		wp_print_scripts( 'pixelgrade_care_wizard' );
		wp_print_footer_scripts();
		wp_print_update_row_templates();
		wp_print_admin_notice_templates(); ?>
		</body>
		</html>
		<?php
	}

	/** === HELPERS=== */

	public function is_pixelgrade_care_setup_wizard() {
		if ( ! empty( $_GET['page'] ) && 'pixelgrade_care-setup-wizard' === $_GET['page'] ) {
			return true;
		}

		return false;
	}

	public function delete_redirect_transient() {
		$delete_transient = delete_site_transient( '_pixcare_activation_redirect' );

		return $delete_transient;
	}

	/**
	 * Main PixelgradeCareSetupWizard Instance
	 *
	 * Ensures only one instance of PixelgradeCareSetupWizard is loaded or can be loaded.
	 *
	 * @since  1.3.0
	 * @static
	 * @param  object $parent Main PixelgradeCare instance.
	 * @return object Main PixelgradeCareSetupWizard instance
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
