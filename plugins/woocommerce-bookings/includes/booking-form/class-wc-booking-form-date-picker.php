<?php
/**
 * Class dependencies
 */
if ( ! class_exists( 'WC_Booking_Form_Picker' ) ) {
	include_once( 'class-wc-booking-form-picker.php' );
}

/**
 * Date Picker class
 */
class WC_Booking_Form_Date_Picker extends WC_Booking_Form_Picker {

	private $field_type = 'date-picker';
	private $field_name = 'start_date';

	/**
	 * Constructor
	 * @param WC_Booking_Form $booking_form The booking form which called this picker
	 */
	public function __construct( $booking_form ) {
		$this->booking_form                    = $booking_form;
		$this->args                            = array();
		$this->args['type']                    = $this->field_type;
		$this->args['name']                    = $this->field_name;
		$this->args['min_date']                = $this->booking_form->product->get_min_date();
		$this->args['max_date']                = $this->booking_form->product->get_max_date();
		$this->args['default_availability']    = $this->booking_form->product->get_default_availability();
		$this->args['min_date_js']             = $this->get_min_date();
		$this->args['max_date_js']             = $this->get_max_date();
		$this->args['duration_type']           = $this->booking_form->product->get_duration_type();
		$this->args['duration_unit']           = $this->booking_form->product->get_duration_unit();
		$this->args['is_range_picker_enabled'] = $this->booking_form->product->is_range_picker_enabled();
		$this->args['display']                 = $this->booking_form->product->get_calendar_display_mode();
		$this->args['availability_rules']      = array();
		$this->args['availability_rules'][0]   = $this->booking_form->product->get_availability_rules();
		$this->args['label']                   = $this->get_field_label( __( 'Date', 'woocommerce-bookings' ) );
		$this->args['product_type']            = $this->booking_form->product->get_type();
		$this->args['restricted_days']         = $this->booking_form->product->has_restricted_days() ? $this->booking_form->product->get_restricted_days() : false;

		if ( $this->booking_form->product->has_resources() ) {
			foreach ( $this->booking_form->product->get_resources() as $resource ) {
				$this->args['availability_rules'][ $resource->ID ] = $this->booking_form->product->get_availability_rules( $resource->ID );
			}
		}

		$fully_booked_blocks = $this->find_fully_booked_blocks();
		$buffer_blocks       = $this->find_buffer_blocks( $fully_booked_blocks['fully_booked_days'] );

		$this->args = array_merge( $this->args, $fully_booked_blocks, $buffer_blocks );

		$this->args['default_date'] = date( 'Y-m-d', $this->get_default_date( $fully_booked_blocks, $buffer_blocks ) );
	}

	/**
	 * Attempts to find what date to default to in the date picker
	 * by looking at the fist available block. Otherwise, the current date is used.
	 *
	 * @param  array $fully_booked_blocks
	 * @param  array $buffer_blocks
	 * @return int Timestamp
	 */
	function get_default_date( $fully_booked_blocks, $buffer_blocks = array() ) {

		/**
		 * Filter woocommerce_bookings_override_form_default_date
		 *
		 * @since 1.9.8
		 * @param int $default_date unix time stamp.
		 * @param WC_Booking_Form_Picker $form_instance
		 */
		$default_date = apply_filters( 'woocommerce_bookings_override_form_default_date', null, $this );

		if ( $default_date ) {
			return $default_date;
		}

		$default_date = strtotime( 'midnight' );

		/**
		 * Filter wc_bookings_calendar_default_to_current_date. By default the calendar
		 * will show the current date first. If you would like it to display the first available date
		 * you can return false to this filter and then we'll search for the first available date,
		 * depending on the booked days calculation.
		 *
		 * @since 1.9.13
		 * @param bool
		 */

		if ( ! apply_filters( 'wc_bookings_calendar_default_to_current_date', true ) ) {

			$booked_dates = array_keys( array_merge( $fully_booked_blocks['fully_booked_days'], $fully_booked_blocks['unavailable_days'] ) );

			if ( isset( $buffer_blocks ) && isset( $buffer_blocks['buffer_days'] ) ) {
				$booked_dates = array_merge( $booked_dates, array_keys( $buffer_blocks['buffer_days'] ) );
			}

			if ( ! empty( $booked_dates ) ) {

				$default_date = $this->find_first_bookable_date( $booked_dates );

			} else {

				// Handles the case where a user can set all dates to be not-available by default
				// Also they add an availability rule where they are bookable at a future date in time

				$now      = strtotime( 'midnight', current_time( 'timestamp' ) );
				$min      = $this->booking_form->product->get_min_date();
				$max      = $this->booking_form->product->get_max_date();
				$min_date = strtotime( 'midnight' );

				if ( ! empty( $min ) ) {
					$min_date = strtotime( "+{$min['value']} {$min['unit']}", $now );
				}

				// handling months differently due to performance impact it has
				// get it in three months batches to ensure
				// we can exit when we find the first one without going through all 12 months
				for ( $i = 1 ; $i <= $max['value'] ; $i += 3 ) {

					// $min_date calculated above first.
					// only add months up to the max value
					$range_end_increment = ( $i + 3 ) > $max['value'] ? $max['value'] : ( $i + 3 );
					$max_date            = strtotime( "+ $range_end_increment month", $now );
					$blocks_in_range     = $this->booking_form->product->get_blocks_in_range( $min_date, $max_date );
					$last_element        = end( $blocks_in_range );

					reset( $blocks_in_range ); // restore the internal pointer.

					if ( $blocks_in_range[0] > $last_element ) {
						// in certain cases the starting date is at the end
						// product->get_available_blocks expects it to be at the beginning
						$blocks_in_range = array_reverse( $blocks_in_range );
					}

					$available_blocks = $this->booking_form->product->get_available_blocks( $blocks_in_range );

					if ( ! empty( $available_blocks[0] ) ) {
						$default_date = $available_blocks[0];
						break;
					} // else continue with loop until we get a default date where the calendar can start at.

					$min_date = strtotime( '+' . $i . ' month', $now );
				}

			}
		}

		return $default_date;
	}

	/**
	 * Find the first bookable date from an array of dates
	 * @param array $dates An array of dates to search
	 *
	 * @return The first bookable date
	 */
	private function find_first_bookable_date( $dates ) {

		// Converting dates into a timestamp because find_booked_day_blocks is
		// formatting dates without leading zeros which can cause max to return the
		// wrong date. We can remove this once leading zeroes are added to the date format.
		//
		// e.g. max( array( '2017-11-9', '2017-11-30' ) ) will return 2017-11-9 as the max date
		$dates = array_map( function( $item ) {
			return strtotime( $item );
		}, $dates );

		$current_date     = strtotime( 'midnight' );
		$last_booked_date = max( $dates );
		$bookable_date    = strtotime( '+1 day', $last_booked_date );

		while ( $current_date < $last_booked_date ) {
			if ( ! in_array( $current_date, $dates ) ) {
				$bookable_date = $current_date;
				break;
			}
			$current_date = strtotime( '+1 day', $current_date );
		}

		return $bookable_date;

	}

	/**
	 * Find days which are buffer days so they can be grayed out on the date picker
	 */
	protected function find_buffer_blocks( $fully_booked_days ) {
		$buffer_days = WC_Bookings_Controller::get_buffer_day_blocks_for_booked_days( $this->booking_form->product, $fully_booked_days );

		return array(
			'buffer_days' => $buffer_days,
		);
	}

	/**
	 * Finds days which are fully booked already so they can be blocked on the date picker
	 */
	protected function find_fully_booked_blocks() {
		$booked = WC_Bookings_Controller::find_booked_day_blocks( $this->booking_form->product->get_id() );

		return array(
			'partially_booked_days' => $booked['partially_booked_days'],
			'fully_booked_days'     => $booked['fully_booked_days'],
			'unavailable_days'      => $booked['unavailable_days'],
		);
	}
}
