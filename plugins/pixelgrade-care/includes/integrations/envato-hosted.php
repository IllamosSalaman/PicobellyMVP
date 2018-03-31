<?php
/**
 * Handle the plugin's behavior when in a Envato Hosted environment.
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Determine if we are in a Envato Hosted environment.
 *
 * @return bool
 */
function pixcare_is_envato_hosted() {
	if ( defined('ENVATO_HOSTED_SITE' ) && false !== ENVATO_HOSTED_SITE ) {
		return true;
	}

	return false;
}

/**
 * Do not initialize the club module when in an Envato Hosted environment.
 *
 * @param bool $enqueue
 *
 * @return bool
 */
function pixcare_envato_hosted_disable_club_module( $enqueue ) {
	if ( pixcare_is_envato_hosted() ) {
		$enqueue = false;
	}

	return $enqueue;
}
add_filter( 'pixcare_allow_club_module', 'pixcare_envato_hosted_disable_club_module', 10, 1 );

/**
 * Do not show the Themes page when in an Envato Hosted environment.
 *
 * @param bool $allow
 *
 * @return bool
 */
function pixcare_envato_hosted_disable_themes_page( $allow ) {
	if ( pixcare_is_envato_hosted() ) {
		$allow = false;
	}

	return $allow;
}
add_filter( 'pixcare_allow_themes_page', 'pixcare_envato_hosted_disable_themes_page', 10, 1 );

/**
 * Do not initialize the data collector when in an Envato Hosted environment.
 *
 * @param bool $allow
 *
 * @return bool
 */
function pixcare_envato_hosted_disable_data_collector_module( $allow ) {
	if ( pixcare_is_envato_hosted() ) {
		$allow = false;
	}

	return $allow;
}
add_filter( 'pixcare_allow_data_collector_module', 'pixcare_envato_hosted_disable_data_collector_module', 10, 1 );

/**
 * Do not initialize the support module when in an Envato Hosted environment.
 *
 * @param bool $allow
 *
 * @return bool
 */
function pixcare_envato_hosted_disable_support_module( $allow ) {
	if ( pixcare_is_envato_hosted() ) {
		$allow = false;
	}

	return $allow;
}
add_filter( 'pixcare_allow_support_module', 'pixcare_envato_hosted_disable_support_module', 10, 1 );

/**
 * Change the pixcare localized array to fit our needs.
 *
 * @param array $localized_data
 * @param string $script_id
 *
 * @return array
 */
function pixcare_envato_hosted_configure_localized_data( $localized_data, $script_id ) {
	if ( pixcare_is_envato_hosted() ) {
		// We don't want the authenticator
		unset( $localized_data['themeConfig']['dashboard']['general']['blocks']['authenticator'] );
		// We need to show the starter content even if the user is not authenticated
		if ( isset( $localized_data['themeConfig']['dashboard']['general']['blocks']['starterContent']['inactive'] ) ) {
			$localized_data['themeConfig']['dashboard']['general']['blocks']['starterContent']['inactive'] = '';
		}

		// We don't want the system status tab
		unset( $localized_data['themeConfig']['dashboard']['system-status'] );

		// We don't want the authenticator in the setup wizard
		unset( $localized_data['themeConfig']['setupWizard']['activation'] );

		// Allow the starter content to go even if no active license exists
		if ( isset( $localized_data['themeConfig']['setupWizard']['support']['blocks']['support']['fields']['starterContent']['inactive'] ) ) {
			$localized_data['themeConfig']['setupWizard']['support']['blocks']['support']['fields']['starterContent']['inactive'] = '';
		}
	}

	return $localized_data;
}
add_filter( 'pixcare_localized_data', 'pixcare_envato_hosted_configure_localized_data', 10, 2 );

/**
 * Add custom inline CSS to help us smooth things over.
 */
function pixcare_envato_hosted_custom_css() {
	if ( PixelgradeCare_Admin::is_pixelgrade_care_dashboard() && pixcare_is_envato_hosted() ) {
		$local_plugin = PixelgradeCare();

		$custom_css = '
                #pixelgrade_care_dashboard .header-toolbar .header-toolbar__wing--left .theme__status,  
                #pixelgrade_care_dashboard .header-toolbar .header-toolbar__wing--right {
                        display: none;
                }';
		wp_add_inline_style( $local_plugin->get_plugin_name(), $custom_css );
	}
}
add_action( 'admin_enqueue_scripts', 'pixcare_envato_hosted_custom_css', 100 );

function pixcare_envato_hosted_prevent_notification_bubble( $show_bubble ) {
	if ( pixcare_is_envato_hosted() ) {
		$show_bubble = false;
		// We will only show the bubble for an update notification or theme files messing around
		// Show bubble if we have an update notification.
		$new_theme_version = get_theme_mod( 'pixcare_new_theme_version' );
		$theme_support     = PixelgradeCare_Admin::get_theme_support();
		if ( ! empty( $new_theme_version ) && ! empty( $theme_support['theme_version'] ) && version_compare( $theme_support['theme_version'], $new_theme_version, '<' ) ) {
			$show_bubble = true;
		}

		$theme_checks = PixelgradeCare_Admin::get_theme_checks();
		if ( $theme_checks['missing_wupdates_code'] || $theme_checks['has_tampered_wupdates_code'] || ! $theme_checks['has_original_name'] || ! $theme_checks['has_original_directory'] ) {
			$show_bubble = true;
		}
	}

	return $show_bubble;
}
add_filter( 'pixcare_show_menu_notification_bubble', 'pixcare_envato_hosted_prevent_notification_bubble', 10, 1 );
