<?php

register_post_type( 'crb_property', array(
	'labels' => array(
		'name' => __( 'Properties', 'crb' ),
		'singular_name' => __( 'Property', 'crb' ),
		'add_new' => __( 'Add New', 'crb' ),
		'add_new_item' => __( 'Add new Property', 'crb' ),
		'view_item' => __( 'View Property', 'crb' ),
		'edit_item' => __( 'Edit Property', 'crb' ),
		'new_item' => __( 'New Property', 'crb' ),
		'view_item' => __( 'View Property', 'crb' ),
		'search_items' => __( 'Search Properties', 'crb' ),
		'not_found' =>  __( 'No properties found', 'crb' ),
		'not_found_in_trash' => __( 'No properties found in trash', 'crb' ),
	),
	'public' => true,
	'exclude_from_search' => false,
	'show_ui' => true,
	'capability_type' => 'page',
	'hierarchical' => false,
	'rewrite' => array(
		'slug' => 'property',
		'with_front' => false,
	),
	'query_var' => true,
	'menu_icon' => 'dashicons-admin-home',
	'supports' => array( 'title', 'editor', 'thumbnail', 'author' ),
) );

register_post_type( 'crb_community', array(
	'labels' => array(
		'name' => __( 'Communities', 'crb' ),
		'singular_name' => __( 'Community', 'crb' ),
		'add_new' => __( 'Add New', 'crb' ),
		'add_new_item' => __( 'Add new Community', 'crb' ),
		'view_item' => __( 'View Community', 'crb' ),
		'edit_item' => __( 'Edit Community', 'crb' ),
		'new_item' => __( 'New Community', 'crb' ),
		'view_item' => __( 'View Community', 'crb' ),
		'search_items' => __( 'Search Communities', 'crb' ),
		'not_found' =>  __( 'No communities found', 'crb' ),
		'not_found_in_trash' => __( 'No communities found in trash', 'crb' ),
	),
	'public' => true,
	'exclude_from_search' => false,
	'show_ui' => true,
	'capability_type' => 'page',
	'hierarchical' => false,
	'rewrite' => array(
		'slug' => 'community',
		'with_front' => false,
	),
	'query_var' => true,
	'menu_icon' => 'dashicons-admin-multisite',
	'supports' => array( 'title', 'editor', 'page-attributes', 'thumbnail' ),
) );

register_post_type( 'crb_agent', array(
	'labels' => array(
		'name' => __( 'Agents', 'crb' ),
		'singular_name' => __( 'Agent', 'crb' ),
		'add_new' => __( 'Add New', 'crb' ),
		'add_new_item' => __( 'Add new Agent', 'crb' ),
		'view_item' => __( 'View Agent', 'crb' ),
		'edit_item' => __( 'Edit Agent', 'crb' ),
		'new_item' => __( 'New Agent', 'crb' ),
		'view_item' => __( 'View Agent', 'crb' ),
		'search_items' => __( 'Search Agents', 'crb' ),
		'not_found' =>  __( 'No agents found', 'crb' ),
		'not_found_in_trash' => __( 'No agents found in trash', 'crb' ),
	),
	'public' => true,
	'exclude_from_search' => false,
	'show_ui' => true,
	'capability_type' => 'page',
	'hierarchical' => false,
	'rewrite' => array(
		'slug' => 'agent',
		'with_front' => false,
	),
	'query_var' => true,
	'menu_icon' => 'dashicons-businessman',
	'supports' => array( 'title', 'editor', 'page-attributes', 'thumbnail' ),
) );

register_post_type( 'crb_testimonial', array(
	'labels' => array(
		'name' => __( 'Testimonials', 'crb' ),
		'singular_name' => __( 'Testimonial', 'crb' ),
		'add_new' => __( 'Add New', 'crb' ),
		'add_new_item' => __( 'Add new Testimonial', 'crb' ),
		'view_item' => __( 'View Testimonial', 'crb' ),
		'edit_item' => __( 'Edit Testimonial', 'crb' ),
		'new_item' => __( 'New Testimonial', 'crb' ),
		'view_item' => __( 'View Testimonial', 'crb' ),
		'search_items' => __( 'Search Testimonials', 'crb' ),
		'not_found' =>  __( 'No testimonials found', 'crb' ),
		'not_found_in_trash' => __( 'No testimonials found in trash', 'crb' ),
	),
	'public' => false,
	'exclude_from_search' => true,
	'show_ui' => true,
	'capability_type' => 'page',
	'hierarchical' => false,
	'rewrite' => array(
		'slug' => 'testimonial',
		'with_front' => false,
	),
	'query_var' => true,
	'menu_icon' => 'dashicons-testimonial',
	'supports' => array( 'title', 'editor', 'page-attributes' ),
) );

register_post_type( 'crb_slideshow', array(
	'labels' => array(
		'name' => __( 'Slideshows', 'crb' ),
		'singular_name' => __( 'Slideshow', 'crb' ),
		'add_new' => __( 'Add New', 'crb' ),
		'add_new_item' => __( 'Add new Slideshow', 'crb' ),
		'view_item' => __( 'View Slideshow', 'crb' ),
		'edit_item' => __( 'Edit Slideshow', 'crb' ),
		'new_item' => __( 'New Slideshow', 'crb' ),
		'view_item' => __( 'View Slideshow', 'crb' ),
		'search_items' => __( 'Search Slideshows', 'crb' ),
		'not_found' =>  __( 'No slideshows found', 'crb' ),
		'not_found_in_trash' => __( 'No slideshows found in trash', 'crb' ),
	),
	'public' => true,
	'exclude_from_search' => true,
	'show_ui' => true,
	'capability_type' => 'page',
	'hierarchical' => false,
	'rewrite' => array(
		'slug' => 'slideshow',
		'with_front' => false,
	),
	'query_var' => true,
	'menu_icon' => 'dashicons-images-alt2',
	'supports' => array( 'title' ),
) );
