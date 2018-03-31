<?php

/*
 * Plugin Name: Picobelly Meals
 * Plugin URI:
 * Description: This plugin displays a picobelly meals
 * Version: 1.0.0
 * Author: Wesley Lam
 * Author URI:
 * License: GPL v2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: picobelly-meals
 * Domain Path:
 */

// Exit if accessed directly.
if( !defined( 'ABSPATH' ) ) exit;

/**
* Register Meals post type.
*
*
*/

function pm_register_post_type() {

    $labels = array(
        'name'                  => _x( 'Meals', 'Post type general name', 'picobelly-meals' ),
        'singular_name'         => _x( 'Meal', 'Post type singular name', 'picobelly-meals' ),
        'menu_name'             => _x( 'Meals', 'Admin Menu text', 'picobelly-meals' ),
        'name_admin_bar'        => _x( 'Meals', 'Add New on Toolbar', 'picobelly-meals' ),
	);

    $args = array(
	    'labels'             => $labels,
	    'public'             => true,
	    'publicly_queryable' => true,
	    'show_ui'            => true,
	    'show_in_menu'       => true,
	    'query_var'          => true,
	    'rewrite'            => array( 'slug' => 'meals' ),
	    'capability_type'    => 'post',
	    'has_archive'        => true,
	    'hierarchical'       => false,
	    'menu_position'      => null,
	    'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
	    'menu_icon'		    	 => 'dashicons-carrot',
    );

	register_post_type( 'pm_meals', $args );

}
add_action( 'init', 'pm_register_post_type' );

/**
* Register Meal Type taxonomy.
*/
function pm_create_taxonomy() {

  $labels = array(
      'name'              => _x( 'Meal Types', 'taxonomy general name', 'picobelly-meals' ),
      'singular_name'     => _x( 'Meal Type', 'taxonomy singular name', 'picobelly-meals' ),
  );

  $args = array(
      'hierarchical'      => true,
      'labels'            => $labels,
      'show_ui'           => true,
      'show_admin_column' => true,
      'query_var'         => true,
      'rewrite'           => array( 'slug' => 'meal-type' ),
  );

  register_taxonomy('pm_meal_type', 'pm_meals', $args );
}
add_action('init', 'pm_create_taxonomy');
