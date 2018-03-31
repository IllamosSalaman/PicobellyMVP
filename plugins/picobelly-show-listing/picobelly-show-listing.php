<?php
/*
 * Plugin Name: Picobelly Show Listing
 * Plugin URI:
 * Description: A WooCommerce plugin for Picobelly Show Listing functionality.
 * (1) As registered cook, I can only make 1 listing (happens at cook registration). (Remember, a listing is the cook him/herself.)
 * (2) As user, I only want to see a listing (cook) on the listview/mapview when the listing offers a meal.
 * (3) As user, I want to see meal information in the listing. This meal information changes when the cook offers a different meal.
 * (4) As user, I do not want to see listings on the listview/mapview which are fully booked (non definitive).
 * (5) As user, I do not want to see listings on the listview/mapview which are expired. Expired listings are shown in the "history" section of the listing.
 * (6) As user, I want to see the following on a listing:
 * User rating, history of previously cooked meals, user review on previously cooked meals, option to flag the listing when appropriate.
 * Version: 1.0.0
 * Author: Picobelly Team
 * Author URI:
 * License: GPL v2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: picobelly-show-listing
 * Domain Path: /languages
 */

// Check to make sure WooCommerce is active.
if (in_array( 'woocommerce/woocommerce.php', apply_filters('active_plugins',
get_option('active_plugins')))) {
  // only run if there's no other class with this name
  if ( ! class_exists('PB_Show_Listing')){
      class PB_Show_Listing {
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
      $GLOBALS['pb_show_listing'] = new PB_Show_Listing();
  }
}

?>
