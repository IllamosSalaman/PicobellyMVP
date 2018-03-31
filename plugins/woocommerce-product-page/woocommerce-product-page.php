<?php
/*
 * Plugin Name: WooCommerce Product Page
 * Plugin URI:
 * Description: Customizing the front end of Woocommerce product pages
 * Version: 1.0.0
 * Author: Wesley Lam
 * Author URI:
 * License: GPL v2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: woocommerce-product-pages
 * Domain Path: /languages
 */

// Check to make sure WooCommerce is active.
if (in_array( 'woocommerce/woocommerce.php', apply_filters('active_plugins',
get_option('active_plugins')))) {
  // only run if there's no other class with this name
  if ( ! class_exists('WC_Product_Page')){
      class WC_Product_Page {
          public function __construct() {
              add_action('init', array( $this,
              'change_my_product_page'));
              add_filter('woocommerce_product_tabs', array($this,
              'assembly_instruction_tab'));
          //    add_filter('the_title', array($this,
          //    'shorten_product_title'), 20, 2);
          }
          //move the price beneath the product description
          public function change_my_product_page(){
              remove_action('woocommerce_single_product_summary',
              'woocommerce_template_single_price', 10);
              add_action('woocommerce_single_product_summary',
              'woocommerce_template_single_price', 25);
          }

      //    public function shorten_product_title( $the_title, $id ){
      //      if(is_shop() && get_post_type($id) == 'product'){
      //          $the_title = wp_trim_words($the_title, 3);
      //      }
      //      return $the_title;
      //    }

          public function assembly_instruction_tab( $tabs){
              // add a new get_html_translation_table
              $tabs['assembly_tab'] = array(
                  'title'     => __('Assembly Instructions',
                  'woocommerce-product-pages'),
                  'priority'  => '50',
                  'callback'  => array ($this,
                  'assembly_instruction_tab_content')
              );
              return $tabs;
          }

          public function assembly_instruction_tab_content(){
              // because you are in the product page,
              // you can use the global product variable
              // It is similar to the WP POST variable but,
              // global product variable has things usefull for products
              global $product;

              // get the instructions from the database
              $instructions_url =
              // -> means . in java.
              $product->__get('_assembly_instructions');

              // The assembly instructions tab content
              echo '<h2>Assembly Instructions</h2>';
              ?>
              <p>Download the <a href="<?php echo $instructions_url;
              ?>">assembly instructions</a>.</p>
              <?php

          }

      }
      $GLOBALS['wc_product_page'] = new WC_Product_Page();
  }
}

?>
