<?php

/**
 * Manage the AJAX responce for the Search Autocomplete
 */
add_action( 'wp_ajax_crb_search_autocomplete', 'crb_search_autocomplete' );
add_action( 'wp_ajax_nopriv_crb_search_autocomplete', 'crb_search_autocomplete' );
function crb_search_autocomplete() {
	$data = array();

	$search = crb_request_param( 'search' );
	$posts = get_posts( array(
		'post_type' => 'crb_property',
		'posts_per_page' => 8,
		'meta_query' => array(
			array(
				'key' => '_crb_property_street',
				'value' => $search,
				'compare' => 'LIKE',
			),
		),
	) );

	foreach ( $posts as $post ) {
		$data[] = array(
			'label' => get_post_meta( $post->ID, '_crb_property_street', true ),
			'value' => get_permalink( $post ),
		);
	}

	echo json_encode( $data );
	exit;
}
