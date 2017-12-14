<?php

register_taxonomy(
	'crb_property_type', # Taxonomy name
	array( 'crb_property' ), # Post Types
	array( # Arguments
		'labels'            => array(
			'name'              => __( 'Property Types', 'crb' ),
			'singular_name'     => __( 'Property Type', 'crb' ),
			'search_items'      => __( 'Search Property Types', 'crb' ),
			'all_items'         => __( 'All Property Types', 'crb' ),
			'parent_item'       => __( 'Parent Property Type', 'crb' ),
			'parent_item_colon' => __( 'Parent Property Type:', 'crb' ),
			'view_item'         => __( 'View Property Type', 'crb' ),
			'edit_item'         => __( 'Edit Property Type', 'crb' ),
			'update_item'       => __( 'Update Property Type', 'crb' ),
			'add_new_item'      => __( 'Add New Property Type', 'crb' ),
			'new_item_name'     => __( 'New Property Type Name', 'crb' ),
			'menu_name'         => __( 'Types', 'crb' ),
		),
		'hierarchical'      => true,
		'show_ui'           => false,
		'show_admin_column' => true,
		'query_var'         => true,
		'public'            => false,
		'rewrite'           => array( 'slug' => 'property-type' ),


		'show_ui'           => true,
		'public'            => true,
	)
);
