<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Bookings WC ajax callbacks.
 */
class WC_Bookings_WC_Ajax {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'wc_ajax_wc_bookings_find_booked_day_blocks', array( $this, 'find_booked_day_blocks' ) );
	}

	/**
	 * This endpoint is supposed to replace the back-end logic in booking-form.
	 */
	public function find_booked_day_blocks() {
		check_ajax_referer( 'find-booked-day-blocks', 'security' );

		$post_id = absint( $_GET['post_id'] );

		if ( empty( $post_id ) ) {
			die();
		}

		$args     = array();
		$product  = new WC_Product_Booking( $post_id );

		// Initialize availability rules
		$args['availability_rules']    = array();
		$args['availability_rules'][0] = $product->get_availability_rules();

		if ( $product->has_resources() ) {
			foreach ( $product->get_resources() as $resource ) {
				$args['availability_rules'][ $resource->ID ] = $product->get_availability_rules( $resource->ID );
			}
		}

		// Initialize min_date and max_date (either requested or from bookable product default)
		$args['min_date'] = isset( $_GET['min_date'] ) ? $_GET['min_date'] : $product->get_min_date();
		$args['max_date'] = isset( $_GET['max_date'] ) ? $_GET['max_date'] : $product->get_max_date();

		// Initialize booked day blocks
		$booked = WC_Bookings_Controller::find_booked_day_blocks( $product->get_id(), $args['min_date'], $args['max_date'] );
		$args['partially_booked_days'] = $booked['partially_booked_days'];
		$args['fully_booked_days']     = $booked['fully_booked_days'];
		$args['unavailable_days']      = $booked['unavailable_days'];

		// Initialize buffer days
		$buffer_days = WC_Bookings_Controller::get_buffer_day_blocks_for_booked_days( $product, $args['fully_booked_days'] );
		$args['buffer_days'] = $buffer_days;

		// TODO: See which of these variables are really needed.
		//$args['type']                    = $this->field_type;
		//$args['name']                    = $this->field_name;
		$args['default_availability']    = $product->get_default_availability();
		//$args['min_date_js']             = $this->get_min_date();
		//$args['max_date_js']             = $this->get_max_date();
		$args['duration_type']           = $product->get_duration_type();
		$args['duration_unit']           = $product->get_duration_unit();
		$args['is_range_picker_enabled'] = $product->is_range_picker_enabled();
		$args['display']                 = $product->get_calendar_display_mode();
		//$args['label']                   = $this->get_field_label( __( 'Date', 'woocommerce-bookings' ) );
		//$args['default_date']            = date( 'Y-m-d', $this->get_default_date() );
		$args['product_type']            = $product->get_type();
		$args['restricted_days']         = $product->has_restricted_days() ? $product->get_restricted_days() : false;

		wp_send_json( $args );
	}
}

new WC_Bookings_WC_Ajax();
