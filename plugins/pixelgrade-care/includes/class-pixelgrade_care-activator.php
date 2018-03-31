<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Fired during plugin activation
 *
 * @link       https://pixelgrade.com
 * @since      1.0.0
 *
 * @package    PixelgradeCare
 * @subpackage PixelgradeCare/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    PixelgradeCare
 * @subpackage PixelgradeCare/includes
 * @author     Pixelgrade <email@example.com>
 */
class PixelgradeCareActivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		/** @var PixelgradeCare $local_plugin */
		$local_plugin = PixelgradeCare();

		if ( $local_plugin->is_wp_compatible() ) {
			$local_plugin->get_theme_config();
		}
	}
}
