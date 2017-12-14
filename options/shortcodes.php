<?php

/**
 * Returns current year
 *
 * @uses [year]
 */
add_shortcode( 'year', 'crb_shortcode_year' );
function crb_shortcode_year() {
	return date( 'Y' );
}

/**
 * Recent Properties
 */
add_shortcode( 'recent-properties', 'crb_shortcode_recent_properties' );
function crb_shortcode_recent_properties( $atts, $content ) {
	$atts = shortcode_atts(
		array(
			'count' => '4',
		),
		$atts, 'recent-properties'
	);

	$count = 4;
	$atts['count'] = absint( $atts['count'] );
	if ( ! empty( $atts['count'] ) && is_numeric( $atts['count'] ) && $atts['count'] > 0 ) {
		$count = $atts['count'];
	}

	$properties_query = new WP_Query( array(
		'post_type'      => 'crb_property',
		'post_status'    => 'publish',
		'order'          => 'DESC',
		'orderby'        => 'date',
		'posts_per_page' => $count,
	) );

	ob_start();
	crb_render_fragment( 'fragments/property/slideshow', array( 'properties_query' => $properties_query ) );
	$html = ob_get_clean();

	return $html;
}

/**
 * Featured Properties
 */
add_shortcode( 'featured-properties', 'crb_shortcode_featured_properties' );
function crb_shortcode_featured_properties( $atts, $content, $shortcode ) {
	$atts = shortcode_atts(
		array(
			'id' => '',
		),
		$atts, $shortcode
	);

	$post_id = absint( $atts['id'] );

	$meta_count = carbon_get_post_meta( $post_id, 'crb_slideshow_count' );
	$meta_featured = carbon_get_post_meta( $post_id, 'crb_slideshow_featured_properties' );

	$args = array(
		'post_type'      => 'crb_property',
		'post_status'    => 'publish',
		'order'          => 'DESC',
		'orderby'        => 'date',
		'posts_per_page' => $count,
		'paged'          => 1,
	);

	if ( ! empty( $meta_count ) ) {
		$args['posts_per_page'] = absint( $meta_count );
	}

	if ( ! empty( $meta_featured ) ) {
		$args['post__in'] = wp_list_pluck( $meta_featured, 'id' );
		$args['order'] = 'ASC';
		$args['orderby'] = 'post__in';
		$args['posts_per_page'] = -1;
	}

	$properties_query = new WP_Query( $args );

	ob_start();
	crb_render_fragment( 'fragments/property/slideshow', array( 'properties_query' => $properties_query ) );
	$html = ob_get_clean();

	return $html;
}

/**
 * Filtered Properties
 */
add_shortcode( 'filtered-properties', 'crb_shortcode_filtered_properties' );
function crb_shortcode_filtered_properties( $atts, $content, $shortcode ) {
	$atts = shortcode_atts(
		array(
			'id' => '',
		),
		$atts, $shortcode
	);

	$post_id = absint( $atts['id'] );

	$filters = new \Crb\Properties_Filters();
	$parameters = $filters->get_slider_parameters( $post_id, 'crb_slideshow_filtered_' );
	$filters->set_params( $parameters );

	$args_obj = new \Crb\Properties_Query_Args();
	$args_obj->set_filters( $filters );
	$args = $args_obj->get();

	$properties_query = new WP_Query( $args );

	$args_obj->deregister_hooks();

	ob_start();
	crb_render_fragment( 'fragments/property/slideshow', array( 'properties_query' => $properties_query ) );
	$html = ob_get_clean();

	return $html;
}
