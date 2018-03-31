<?php
/**
 * Load various specific integrations.
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load Envato Hosted compatibility file.
 */
require plugin_dir_path( __FILE__ ) . '/integrations/envato-hosted.php';
