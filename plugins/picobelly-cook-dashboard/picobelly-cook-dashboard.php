<?php
/*
 * Plugin Name: Picobelly Cook Dashboard
 * Plugin URI:
 * Description: A WooCommerce plugin for Picobelly Cook Dashboard functionality. Mainly this plugin alters the Woo Commerce Frontend Manager plugin.
 * (1) As registered cook, I want to be able to add meals.
 * (2) As registered cook, I want to see who are the reservers of currently offered meal.
 * Version: 1.0.0
 * Author: Picobelly Team
 * Author URI:
 * License: GPL v2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: picobelly-cook-dashboard
 * Domain Path: /languages
 */

// Check to make sure WooCommerce is active.
if (in_array( 'woocommerce/woocommerce.php', apply_filters('active_plugins',
get_option('active_plugins')))) {
  // only run if there's no other class with this name
  if ( ! class_exists('PB_Cook_Dashboard')){
      class PB_Cook_Dashboard {
          public function __construct() {
              // print an admin notice to the screen
              add_action('admin_notices', array ( $this, 'my_admin_notice'));
          }

          //print an admin admin_notices
          public function my_admin_notice(){
              ?>
              <div class="notice notice-success is-dismissible">
                  <p><?php _e( 'Done!', 'sample-text-domain' ); ?></p>
              </div>
              <?php
          }
      }
      $GLOBALS['pb_cook_dashboard'] = new PB_Cook_Dashboard();
  }
}

?>
