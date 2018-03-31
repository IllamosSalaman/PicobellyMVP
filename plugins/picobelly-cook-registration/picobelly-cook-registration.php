<?php
/*
 * Plugin Name: Picobelly Cook Registration
 * Plugin URI:
 * Description: A WooCommerce plugin for Picobelly Cook Registration functionality.
 * (1) As eater user, I do not want to wait for the admin's approval to become a cook.
 * (2) As user, I want the Vendor slug to be changed to "Cook".
 * (3) Only as eater user, I am able to navigate to cook registration.
 * (4) As user, when I register as cook it also creates a cook's listing without waiting for the admin to approve a listing.
 * (5) As registered cook, I want acces to the vendor's menu (Woo Commerce Frontend Manager plugin)
 * Version: 1.0.0
 * Author: Picobelly Team
 * Author URI:
 * License: GPL v2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: picobelly-cook-registration
 * Domain Path: /languages
 */

// Check to make sure WooCommerce is active.
if (in_array( 'woocommerce/woocommerce.php', apply_filters('active_plugins',
get_option('active_plugins')))) {
  // only run if there's no other class with this name
  if ( ! class_exists('PB_Cook_Registration')){
      class PB_Cook_Registration {
          public function __construct() {
          add_action( 'woocommerce_created_customer', 'wc_create_vendor_on_registration', 10, 2 );
          }
          // Create a vendor on account registration.
          //
          // @param int $customer_id
          // @param array $new_customer_data
          // @return boid

          function wc_create_vendor_on_registration( $customer_id, $new_customer_data ) {
            $username = $new_customer_data['user_login'];
            $email = $new_customer_data['user_email'];
            // Ensure vendor name is unique
            if ( term_exists( $username, 'shop_vendor' ) ) {
                $append = 1;
                $o_username = $username;
                while ( term_exists( $username, 'shop_vendor' ) ) {
                  $username = $o_username . $append;
                  $append ++;
                }
              }
              // Create the new vendor
              $return = wp_insert_term(
                $username,
                'shop_vendor',
                array(
                  'description' => sprintf( __( 'The vendor %s', 'localization-domain' ), $username ),
                  'slug' => sanitize_title( $username )
                )
              );
              if ( is_wp_error( $return ) ) {
                wc_add_notice( __( '<strong>ERROR</strong>: Unable to create the vendor account for this user. Please contact the administrator to register your account.', 'localization-domain' ), 'error' );
              } else {
                // Update vendor data
                $vendor_data['paypal_email'] = $email; // The email used for the account will be used for the payments
                $vendor_data['commission'] = '50'; // The commission is 50% for each order
                $vendor_data['admins'][] = $customer_id; // The registered account is also the admin of the vendor
                update_option( 'shop_vendor_' . $return['term_id'], $vendor_data );
              }
            }
      }
      $GLOBALS['pb_cook_registration'] = new PB_Cook_Registration();
  }
}

?>
