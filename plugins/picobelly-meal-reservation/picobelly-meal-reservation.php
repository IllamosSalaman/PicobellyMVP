<?php
/*
 * Plugin Name: Picobelly Meal Reservation
 * Plugin URI:
 * Description: A WooCommerce plugin for Picobelly Meal Reservation functionality.
 * (1) As user, I want to reserve meals based on the date and time provided by the cook.
 * (2) As user, I want to directly go to the checkout to pay for the meal.
 * (3) As user, I want to see a custom version of the woocommerce direct checkout (not specified yet).
 * (4) As user, I want see payment methods from paypal and stripe.
 * Version: 1.0.0
 * Author: Picobelly Team
 * Author URI:
 * License: GPL v2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: picobelly-meal-reservation
 * Domain Path: /languages
 */

// Check to make sure WooCommerce is active.
if (in_array( 'woocommerce/woocommerce.php', apply_filters('active_plugins',
get_option('active_plugins')))) {
  // only run if there's no other class with this name
  if ( ! class_exists('PB_Meal_Reservation')){
      class PB_Meal_Reservation {
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
      $GLOBALS['pb_meal_reservation'] = new PB_Meal_Reservation();
  }
}

?>
