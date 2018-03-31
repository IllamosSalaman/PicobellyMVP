<?php
/*
 * Plugin Name: Picobelly Meal Posting
 * Plugin URI:
 * Description: A WooCommerce plugin for Picobelly Meal Posting functionality.
 * (1) As registered cook, I press on 1 button to go to the vendors menu (Woo Commerce Frontend Manager plugin (WCFM))
 * (2) As registered cook, when I want to add a product, I want to see 1 preset option to add a meal including fields:
 * Food-pricing, Food-timing, Food-signup, Food-ingredients, Food-picture, Food-location, Food-chef.
 * (3) As registered cook, I can only post 1 meal online at a time.
 * (4) As registered cook, I want to be able to add a meal in the stripped down WCFM cook dashboard.
 * Version: 1.0.0
 * Author: Picobelly Team
 * Author URI:
 * License: GPL v2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: picobelly-meal-posting
 * Domain Path: /languages
 */

// Check to make sure WooCommerce is active.
if (in_array( 'woocommerce/woocommerce.php', apply_filters('active_plugins',
get_option('active_plugins')))) {
  // only run if there's no other class with this name
  if ( ! class_exists('PB_Meal_Posting')){
      class PB_Meal_Posting {
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
      $GLOBALS['pb_meal_posting'] = new PB_Meal_Posting();
  }
}

?>
