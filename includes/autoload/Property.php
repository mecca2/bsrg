<?php

namespace Crb;

/**
 * Represent a property Entry
 * Used to display Property in Frontend
 */
class Property {
	protected $meta_keys = array(
		'agent_id',
		'api_id',
		'price',
		'neighborhood',
		'street_number',
		'street_prefix',
		'street_name',
		'unit_number',
		'street_dir_suffix',
		'street_suffix',
		'city',
		'state',
		'postal_code',
		'bedrooms',
		'bathrooms',
		'sqft',
		'sale',
		'lat',
		'lng',
		'year_built',
		'garage_carport',
		'features',
		'schools',
		'food',
		'waterfront',
		'water_sewer',
		'equipment',
		'basement',
		'dryer',
		'family_room',
		'floors',
		'roofing',
		'shared_interest',
		'walls',
		'zoning',
		'age',
		'construction',
		'exterior_extras',
		'fireplace',
		'foundation',
		'heating',
		'other_rooms',
		'days_on_market',
		'style',
		'washer_connection',
		'water_heater',
	);

	protected $details_keys = array(
		'bedrooms',
		'bathrooms',
		'sqft',
	);

	protected $additional_details_keys = array(
		'equipment',
		'basement',
		'dryer',
		'family_room',
		'floors',
		'garage_carport',
		'neighborhood',
		'roofing',
		'shared_interest',
		'walls',
		'waterfront',
		'water_sewer',
		'zoning',
		'age',
		'construction',
		'exterior_extras',
		'fireplace',
		'foundation',
		'heating',
		'other_rooms',
		'days_on_market',
		'style',
		'washer_connection',
		'water_heater',
		'year_built',
		'api_id',
	);

	function __construct( $property_id ) {
		$prefix = 'crb_property_';

		$this->images = carbon_get_the_post_meta( $prefix . 'gallery' );
		$image = '';
		if ( ! empty( $this->images ) && ! empty( $this->images[0] ) && ! empty( $this->images[0]['url'] ) ) {
			$image = $this->images[0]['url'];
		}

		$post_metadata = get_post_meta( $property_id );

		// API Meta
		$this->meta = array();
		foreach ( $this->meta_keys as $key ) {
			$this->meta[ $key ] = $post_metadata[ '_' . $prefix . \Crb\Mapper::get( $key ) ][0];
		}

		// Additional Meta
		$this->meta = array_merge( $this->meta, array(
			'address' => $post_metadata[ '_' . $prefix . 'address' ][0],
			'street'  => $post_metadata[ '_' . $prefix . 'street' ][0],
			'image'   => $image,
		) );

		$this->address = $this->meta['address'];
	}

	function get_meta_from_keys( $keys ) {
		$keys = array_combine( $keys, $keys );

		$meta = array();
		foreach ( $keys as $key ) {
			$meta[$key] = $this->meta[$key];
		}

		$meta = array_filter( $meta );
		# $meta = $this->filter_meta( $meta );

		$meta = $this->format_meta( $meta );

		return $meta;
	}

	function filter_meta( $meta_array ) {
		foreach ( $meta_array as $key => $value ) {
			if ( strtolower( $value ) == 'no' ) {
				unset( $meta_array[$key] );
			}
		}

		return $meta_array;
	}

	function format_meta( $meta_array ) {
		foreach ( $meta_array as $key => $value ) {
			// Add space after ",.;" when they are followed by letter
			$meta_array[$key] = preg_replace( '~([,.;])([a-zA-Z])~', '$1 $2', $value );
		}

		return $meta_array;
	}

	function set_titles( $meta_array ) {
		foreach ( $meta_array as $key => $value ) {
			$meta_array[$key] = array(
				'value' => $value,
				'title' => \Crb\Mapper::get_field_title( $key ),
			);
		}

		return $meta_array;
	}

	function get_images() {
		return $this->images;
	}

	function get_meta() {
		return $this->meta;
	}

	function get_address() {
		return $this->address;
	}

	function get_details() {
		if ( isset( $this->details ) ) {
			return $this->details;
		}

		$this->details = $this->get_meta_from_keys( $this->details_keys );

		return $this->details;
	}

	function get_additional_details() {
		if ( isset( $this->additional_details ) ) {
			return $this->additional_details;
		}

		$this->additional_details = $this->get_meta_from_keys( $this->additional_details_keys );
		$this->additional_details = $this->set_titles( $this->additional_details );

		return $this->additional_details;
	}

	function get_price() {
		if ( empty( $this->meta['price'] ) ) {
			return '';
		}

		return '$' . number_format( $this->meta['price'], 0 );
	}

	function get_agent_api_id() {
		return $this->meta['agent_id'];
	}

	function get_agent_id() {
		if ( ! isset( $this->agent_id ) ) {
			$agents = get_posts( array(
				'post_type' => 'crb_agent',
				'post_status' => 'publish',
				'posts_per_page' => 1,
				'paged' => 1,
				'meta_key' => '_crb_agent_id',
				'meta_value' => $this->meta['agent_id'],
			) );

			$this->agent_id = 0;
			if ( ! empty( $agents ) && ! empty( $agents[0] ) && ! empty( $agents[0]->ID ) ) {
				$this->agent_id = $agents[0]->ID;
			}
		}

		return $this->agent_id;
	}
}
