<?php
/**
 * Listable Theme Customizer.
 *
 * @package Listable
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function listable_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}

add_action( 'customize_register', 'listable_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function listable_customize_preview_js() {
	wp_enqueue_script( 'listable_customizer_preview', get_template_directory_uri() . '/assets/js/admin/customizer_preview.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'listable_customize_preview_js' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function listable_customize_js() {
	wp_enqueue_script( 'listable_customizer', get_template_directory_uri() . '/assets/js/admin/customizer.js', array( 'wp-ajax-response' ), '20130508', true );

	wp_enqueue_style( 'listable-admin-edit-styles', get_template_directory_uri() . '/assets/css/admin/customizer.css' );
}
add_action( 'customize_controls_enqueue_scripts', 'listable_customize_js' );

/**
 * Add a shortcut in customizer for the front-page settings
 */
function listable_add_front_page_link_to_customizer_settings() {
	global $wp_admin_bar;

	if ( is_page_template( 'page-templates/front_page.php' ) ) {
		$href = admin_url( 'customize.php' );
		//square brackets in a URL is a mtfker right now in WordPress
		$href = add_query_arg( urlencode( 'autofocus[section]' ), 'sidebar-widgets-front_page_sections', $href );
		$wp_admin_bar->add_node( array(
			'id'     => 'customizer_front_page_sections',
			'parent' => false,
			'title'  => '🔵 ' . esc_html__( 'Customize Front Page Sections', 'listable' ),
			'href'   => $href,
		) );
	}
}
add_action( 'wp_before_admin_bar_render', 'listable_add_front_page_link_to_customizer_settings' );

/**
 * Add a shortcut in customizer for the single listings options
 */
function listable_add_single_listing_link_to_customizer_settings() {
	global $wp_admin_bar;

	if ( is_singular( 'job_listing' ) ) {
		$href = admin_url( 'customize.php' );
		//square brackets in a URL is a mtfker right now in WordPress
		$href = add_query_arg( urlencode( 'autofocus[panel]' ), 'widgets', $href );
		$href = add_query_arg( 'url', urlencode( get_permalink() ), $href );
		$wp_admin_bar->add_node( array(
			'id'     => 'customizer_front_page_sections',
			'parent' => false,
			'title'  => '🔶 ' . esc_html__( 'Customize Listings Layout', 'listable' ),
			'href'   => $href,
		) );
	}
}
add_action( 'wp_before_admin_bar_render', 'listable_add_single_listing_link_to_customizer_settings' );
