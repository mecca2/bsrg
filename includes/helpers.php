<?php

use Carbon_Fields\Field\Field;

/**
 * Filter the escaping of phone numbers in "tel:" links.
 */
add_filter( 'clean_url', 'crb_sanitize_phone_number', 10, 3 );
function crb_sanitize_phone_number( $good_protocol_url, $original_url, $_context ) {
	$maybe_tel_protocol = substr( $good_protocol_url, 0, 4 );

	if ( $maybe_tel_protocol === 'tel:' ) {
		$phone_number = substr( $good_protocol_url, 4 );
		$phone_number = preg_replace( '/[^0-9\-\_\+]*/', '', $phone_number );

		$good_protocol_url = $maybe_tel_protocol . $phone_number;
	}

	return $good_protocol_url;
}

// Return link target, depending of checkbox field
function crb_the_target( $field = '' ) {
	echo crb_return_target( $field );
}

function crb_return_target( $field = '' ) {
	$target = '';
	if ( ! empty( $field ) ) {
		$target = 'target="_blank"';
	}

	return $target;
}

// Finds the first page ID, using specific Template
function crb_get_id_from_template( $template_name ) {
	$gallery_template_pages = get_posts( array(
		'post_type' => 'page',
		'meta_key' => '_wp_page_template',
		'posts_per_page' => 1,
		'meta_value' => $template_name,
		'fields' => 'ids'
	) );

	if ( ! empty( $gallery_template_pages[0] ) ) {
		return $gallery_template_pages[0];
	}

	return 0;
}

// Clean URL from empty get parameters
// add_action( 'template_redirect', 'crb_clean_empty_get_parameters_from_properties' );
function crb_clean_empty_get_parameters_from_properties() {
	if ( ! is_page_template( 'templates/properties.php' ) ) {
		return;
	}

	// $current_parameters = parse_str( $_SERVER['QUERY_STRING'] );

	$current_url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

	try {
		$filters = new \Crb\Properties_Filters();
	} catch ( \Exception $e ) {
		echo '<pre>';
		print_r( $e->getError() );
		echo '</pre>';
		return;
	}

	$url_with_params = $filters->get_template_url_with_params();

	if ( $url_with_params != $current_url ) {
		wp_redirect( $url_with_params );
		exit;
	}
}

function crb_get_pagination_options( $custom_options = array() ) {
	$classes = 'paging-no-numbers';
	if ( ! empty( $custom_options['enable_numbers'] ) ) {
		$classes = 'paging-with-numbers';
	}

	$default_options = array(
		'wrapper_before' => '<div class="paging ' . $classes . '">',
		'wrapper_after' => '</div>',

		'prev_html'    => '<a href="{URL}" class="paging__prev"><i class="fa fa-angle-left" aria-hidden="true"></i></a>',
		'next_html'    => '<a href="{URL}" class="paging__next"><i class="fa fa-angle-right" aria-hidden="true"></i></a>',

		'first_html'        => '<a href="{URL}" class="paging__first"></a>',
		'last_html'         => '<a href="{URL}" class="paging__last"></a>',
		'limiter_html'      => '<li class="paging__spacer">...</li>',
		'current_page_html' => '<span class="paging__label">Page {CURRENT_PAGE} of {TOTAL_PAGES}</span>',

		'number_limit'               => 2,
		'large_page_number_limit'    => 1,
		'large_page_number_interval' => 1,
	);

	return wp_parse_args( $custom_options, $default_options );
}

function crb_column_render_slideshow_shortcode( $post_id = null, $title = '', $type = '' ) {
	if ( empty( $post_id ) && isset( $_GET ) && isset( $_GET['post'] ) ) {
		$post_id = $_GET['post'];
		$title = '<label>' . __( 'Shortcode', 'crb' ) . '</label>';
	}

	if ( empty( $type ) ) {
		$type = carbon_get_post_meta( $post_id, 'crb_slideshow_type' );
	}

	return sprintf( '%s<code>[%s-properties id="%s"]</code>', $title, $type, $post_id );
}

function crb_column_render_slideshow_shortcode_featured() {
	return crb_column_render_slideshow_shortcode( null, '', 'featured' );
}

function crb_column_render_slideshow_shortcode_filtered() {
	return crb_column_render_slideshow_shortcode( null, '', 'filtered' );
}
