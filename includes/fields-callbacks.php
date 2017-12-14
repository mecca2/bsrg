<?php

use Carbon_Fields\Field\Field;

function crb_get_contacts_fields( $prefix = '' ) {
	return array(
		Field::make( 'complex', $prefix . 'contacts', __( 'Contacts', 'crb' ) )
			->add_fields( array(
				Field::make( 'select', 'type', __( 'Type', 'crb' ) )
					->set_required( true )
					->set_width( 17 )
					->set_options( array(
						'custom' => __( 'Custom' , 'crb' ),
						'phone'  => __( 'Phone'  , 'crb' ),
						'email'  => __( 'Email'  , 'crb' ),
						'map'    => __( 'Map'    , 'crb' ),
					) ),
				Field::make( 'html', 'html', __( 'html', 'crb' ) )
					->set_width( 17 )
					->set_html( '<p>' . sprintf( __( 'You can setup this contact information from <a href="%s">Theme Options > Contacts</a>', 'crb' ), admin_url( 'admin.php?page=crb_carbon_fields_container_contacts.php' ) ) . '</p>' )
					->set_conditional_logic( array(
						array(
							'field' => 'type',
							'value' => 'custom',
							'compare' => '!=',
						)
					) ),
				Field::make( 'textarea', 'title', __( 'Title', 'crb' ) )
					->set_width( 17 )
					->set_rows( 2 )
					->set_required( true )
					->set_conditional_logic( array(
						array(
							'field' => 'type',
							'value' => 'custom',
							'compare' => '=',
						)
					) ),
				Field::make( 'icon', 'icon', __( 'Icon', 'crb' ) )
					->set_width( 17 )
					->set_required( true )
					->set_conditional_logic( array(
						array(
							'field' => 'type',
							'value' => 'custom',
							'compare' => '=',
						)
					) ),
				Field::make( 'text', 'link', __( 'Link', 'crb' ) )
					->set_width( 16 )
					->set_conditional_logic( array(
						array(
							'field' => 'type',
							'value' => 'custom',
							'compare' => '=',
						)
					) ),
				Field::make( 'checkbox', 'target', __( 'Open link in new window', 'crb' ) )
					->set_width( 16 )
					->set_conditional_logic( array(
						array(
							'field' => 'type',
							'value' => 'custom',
							'compare' => '=',
						)
					) ),
			) )
	);
}

function crb_get_button_fields( $prefix = 'button_', $separator_title = '' ) {
	$button_fields = array();

	if ( ! empty( $separator_title ) ) {
		$button_fields = array_merge( $button_fields, array(
			Field::make( 'separator', $prefix . 'separator', $separator_title ),
		) );
	}

	$button_fields = array_merge( $button_fields, array(
		Field::make( 'text', $prefix . 'title', __( 'Button Title', 'crb' ) )
			->set_width( 35 ),
		Field::make( 'text', $prefix . 'link', __( 'Button Link', 'crb' ) )
			->set_width( 35 ),
		Field::make( 'checkbox', $prefix . 'target', __( 'Open button link in new window', 'crb' ) )
			->help_text( __( 'Check the box if you want to open the link in a new browser tab.', 'crb' ) )
			->set_width( 30 ),
	) );

	return $button_fields;
}

// Callout Slider Fields
function crb_get_fields_callout_slider( $prefix = '' ) {
	return array(
		Field::make( 'complex', $prefix . 'slider', __( 'Slider', 'crb' ) )
			->set_layout( 'tabbed-vertical' )
			->add_fields( '', __( 'Slide', 'crb' ), array_merge(
				array(
					Field::make( 'image', 'image', __( 'Image', 'crb' ) )
						->set_required( true )
						->set_width( 30 )
						->help_text( crb_get_attachment_help( 'crb_callout_slider' ) ),
					Field::make( 'text', 'title', __( 'Title', 'crb' ) )
						->set_required( true )
						->set_width( 70 ),
				),
				crb_get_button_fields( 'button_', __( 'Button', 'crb' ) )
			) )
			->set_header_template( '<%- title %>' ),
	);
}

// Callback for Dropdown showing all user created menus
function crb_get_menu_options() {
	$menu_terms = get_terms( array(
		'taxonomy' => 'nav_menu',
		'orderby'  => 'name',
		'order'    => 'ASC',
	) );

	$menus = array_merge(
		array(
			'' => __( ' --- Select Menu --- ', 'crb' )
		),
		wp_list_pluck( $menu_terms, 'name', 'slug' )
	);

	return $menus;
}

// Fixes and forces fields_after_title to work
add_filter( 'get_user_option_' . 'meta-box-order_' . 'crb_community', 'crb_force_carbon_fields_after_title', 1000, 3 );
function crb_force_carbon_fields_after_title( $result, $option, $user ) {
	if ( ! isset( $result['carbon_fields_after_title'] ) ) {
		return $result;
	}

	$containers_to_move = array(
		'carbon_fields_container_intro',
	);

	$carbon_fields_after_title = explode( ',', $result['carbon_fields_after_title'] );
	$normal = explode( ',', $result['normal'] );
	$side = explode( ',', $result['side'] );
	foreach ( $containers_to_move as $container_slug ) {
		$normal_index = array_search( $container_slug, $normal );
		if ( $normal_index !== false ) {
			$carbon_fields_after_title[] = $container_slug;
			unset( $normal[$normal_index] );
		}

		$side_index = array_search( $container_slug, $side );
		if ( $side_index !== false ) {
			$carbon_fields_after_title[] = $container_slug;
			unset( $side[$side_index] );
		}
	}

	$result['carbon_fields_after_title'] = implode( ',', $carbon_fields_after_title );
	$result['normal'] = implode( ',', $normal );
	$result['side'] = implode( ',', $side );

	return $result;
}
