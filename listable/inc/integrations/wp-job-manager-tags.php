<?php
/**
 * Custom functions that deal with the integration of WP Job Manager Job Tags.
 * See: https://wpjobmanager.com/add-ons/job-tags/
 *
 * @package Listable
 */

/* -------- WIDGETS -------- */

function listable_register_widget_areas_wpjm_tags() {
	register_widget( 'Listing_Tags_Widget' );

	// if we have this widget, remove the plugin's output
	global $job_manager_tags;
	if ( $job_manager_tags !== null ) {
		remove_filter( 'the_job_description', array( $job_manager_tags, 'display_tags' ), 10 );
	}
}

add_action( 'widgets_init', 'listable_register_widget_areas_wpjm_tags' );

class Listing_Tags_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
				'listing_tags', // Base ID
				'&#x1F536; ' . esc_html__( 'Listing', 'listable' ) . ' &raquo; ' . esc_html__( 'Tags', 'listable' ), // Name
				array( 'description' => esc_html__( 'A list of tags or amenities.', 'listable' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		global $post;
		echo $args['before_widget'];

		$tags = wp_get_object_terms( $post->ID, 'job_listing_tag' );

		if ( $tags ) : ?>

			<ul class="listing-tag-list">

				<?php foreach ( $tags as $tag ) :
					$tag_link = esc_url( get_term_link( $tag ) );
					$tag_image = listable_get_term_icon_url( $tag->term_id );

					$tag_output = '';
					$tag_output .= '<li><a href="' . $tag_link . '" class="listing-tag">';
					if ( $tag_image ) :
						$tag_output .= '<span class="tag__icon"><img src="' . $tag_image . '" alt=""/></span>';
					endif;
					$tag_output .= '<span class="tag__text">' . $tag->name . '</span></a></li>';

					if ( ! apply_filters( 'enable_job_tag_archives', get_option( 'job_manager_enable_tag_archive' ) ) )
						$tag_output = strip_tags( $tag_output, '<li><span><img>' );

					echo $tag_output;

				endforeach; ?>

			</ul><!-- .listing-tag-list -->

		<?php endif;

		echo $args['after_widget'];
	}

	public function form( $instance ) {
		echo '<p>' . $this->widget_options['description'] . '</p>';
	}
} // class Listing_Tags_Widget