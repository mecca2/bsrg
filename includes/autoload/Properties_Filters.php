<?php

namespace Crb;

use Carbon_Fields\Field\Field;

/**
 * Handle the Properties listing filter parameters
 */
class Properties_Filters {
	use Price_Min_Max_Global;

	public $parameters = array(
		'order' => 'newest',
		'type' => '',
		'search' => false,
		'min_price' => '',
		'max_price' => '',
		'bedrooms' => false,
		'bathrooms' => false,
		'waterfronts' => [],
		'neighborhoods' => [],
		'community_amenities' => [],
		'year_built' => [],
		'sub_type' => [],
		'count' => 8,
	);
	public $default_parameters;
	public $options = array();
	public $properties_template = 'templates/properties.php';

	public $template_id;
	public $template_url_clean;
	public $template_url_with_params;
	public $prefix = 'crb_property_';

	function __construct() {
		$this->cache = new Rets_Cache( 'Properties_Filters' );
		$this->load_from_cache();

		$this->default_parameters = $this->parameters;

		$this->init_options();

		$this->init_parameters();

		$id = crb_get_id_from_template( $this->properties_template );
		if ( empty( $id ) ) {
			throw new \Exception( 'Properties template is not setup. Please assign this template to a page in order for this to work.' );
		}

		$this->template_id = $id;
		$this->template_url_clean = get_permalink( $id );

		$this->template_url_with_params = add_query_arg( $this->get_active_parameters(), $this->template_url_clean );
	}

	public function set_params( $custom_parameters ) {
		foreach ( $this->parameters as $parameter => $value ) {
			if ( ! empty( $custom_parameters[$parameter] ) ) {
				$this->parameters[$parameter] = urldecode( $custom_parameters[$parameter] );
			}
		}
	}

	public function get_action() {
		return $this->template_url_with_params;
	}

	public function get_template_url_clean() {
		return $this->template_url_clean;
	}

	public function get_template_url_with_params() {
		return $this->template_url_with_params;
	}

	function init_options() {
		if ( ! isset( $this->where_post__in ) ) {
			$this->where_post__in = '';

			// Force a freshly generated value
			$this->included_post_ids = NULL;

			$included_post_ids = $this->get_included_post_ids();

			if ( ! empty( $included_post_ids ) ) {
				$this->where_post__in = sprintf( 'AND post_id IN (%s)', implode( ',', $included_post_ids ) );
				$this->cache->set( 'where_post__in', $this->where_post__in, DAY_IN_SECONDS );
			}
		}

		global $wpdb;
		$min_price = array(
			100000  => '$ ' . number_format( '100000', 0 ),
			500000  => '$ ' . number_format( '500000', 0 ),
			1000000 => '$ ' . number_format( '1000000', 0 ),
			2000000 => '$ ' . number_format( '2000000', 0 ),
			3000000 => '$ ' . number_format( '3000000', 0 ),
		);

		$max_price = array(
			100000  => '$ ' . number_format( '100000', 0 ),
			500000  => '$ ' . number_format( '500000', 0 ),
			1000000 => '$ ' . number_format( '1000000', 0 ),
			2000000 => '$ ' . number_format( '2000000', 0 ),
			3000000 => '$ ' . number_format( '3000000', 0 ),
		);

		$bedrooms = array(
			'1'  => '1',
			'2'  => '2',
			'3'  => '3',
			'4'  => '4',
			'5'  => '5',
			'6*' => '6+' , # Asterisk is used instead of a Plus sign , since asterisk does not get encoded
		);

		$bathrooms = array(
			'1'  => '1',
			'2'  => '2',
			'3'  => '3',
			'4'  => '4',
			'5'  => '5',
			'6*' => '6+' , # Asterisk is used instead of a Plus sign , since asterisk does not get encoded
		);

		if ( ! isset( $this->waterfronts ) ) {
			$waterfronts_meta = $this->prefix . \Crb\Mapper::get( 'waterfront' );
			$this->waterfronts = $wpdb->get_col(
				"SELECT
					meta_value
				FROM
					$wpdb->postmeta
				WHERE
					meta_key like '%{$waterfronts_meta}%'
					{$this->where_post__in}
				GROUP BY
					meta_value
				LIMIT 999
				"
			);
			$this->waterfronts = array_combine( $this->waterfronts, $this->waterfronts );

			$this->cache->set( 'waterfronts', $this->waterfronts, DAY_IN_SECONDS );
		}

		if ( ! isset( $this->neighborhoods ) ) {
			$neighborhoods_meta = $this->prefix . \Crb\Mapper::get( 'neighborhood' );
			$this->neighborhoods = $wpdb->get_col(
				"SELECT
					meta_value
				FROM
					$wpdb->postmeta
				WHERE
					meta_key like '%{$neighborhoods_meta}%'
					{$this->where_post__in}
				GROUP BY
					meta_value
				LIMIT 999
				"
			);
			$this->neighborhoods = array_combine( $this->neighborhoods, $this->neighborhoods );

			$this->cache->set( 'neighborhoods', $this->neighborhoods, DAY_IN_SECONDS );
		}

		if ( ! isset( $this->community_amenities ) ) {
			$community_amenities_meta = $this->prefix . \Crb\Mapper::get( 'community_amenities' );
			$this->sql_community_amenities = $wpdb->get_col(
				"SELECT
					meta_value
				FROM
					$wpdb->postmeta
				WHERE
					meta_key like '%{$community_amenities_meta}%'
					{$this->where_post__in}
				GROUP BY
					meta_value
				LIMIT 999
				"
			);

			$this->community_amenities = array();
			array_walk( $this->sql_community_amenities, function( $entry ) {
				$amenities = explode( ',', $entry );

				$this->community_amenities = array_merge( $this->community_amenities, $amenities );
			} );
			$this->community_amenities = array_filter( array_unique( $this->community_amenities ) );
			sort( $this->community_amenities );

			$this->community_amenities = array_combine( $this->community_amenities, $this->community_amenities );

			$this->cache->set( 'community_amenities', $this->community_amenities, DAY_IN_SECONDS );
		}

		if ( ! isset( $this->year_built ) ) {
			$year_built_meta = $this->prefix . \Crb\Mapper::get( 'year_built' );
			$this->year_built = $wpdb->get_col(
				"SELECT
					meta_value
				FROM
					$wpdb->postmeta
				WHERE
					meta_key like '%{$year_built_meta}%'
					{$this->where_post__in}
				GROUP BY
					meta_value
				LIMIT 999
				"
			);
			$this->year_built = array_filter( $this->year_built );
			sort( $this->year_built );
			$this->year_built = array_combine( $this->year_built, $this->year_built );

			$this->cache->set( 'year_built', $this->year_built, DAY_IN_SECONDS );
		}

		if ( ! isset( $this->sub_type ) ) {
			$sub_type_meta = $this->prefix . \Crb\Mapper::get( 'sub_type' );
			$this->sub_type = $wpdb->get_col(
				"SELECT
					meta_value
				FROM
					$wpdb->postmeta
				WHERE
					meta_key like '%{$sub_type_meta}%'
					{$this->where_post__in}
				GROUP BY
					meta_value
				LIMIT 999
				"
			);
			$this->sub_type = array_filter( $this->sub_type );
			sort( $this->sub_type );
			$this->sub_type = array_combine( $this->sub_type, $this->sub_type );

			$this->cache->set( 'sub_type', $this->sub_type, DAY_IN_SECONDS );
		}

		if ( ! isset( $this->types ) ) {
			$types = get_terms( array(
				'taxonomy' => 'crb_property_type',
				'order' => 'ASC',
				'orderby' => 'slug',
			) );

			$this->types = wp_list_pluck( $types, 'name', 'slug' );;

			$this->cache->set( 'types', $this->types, DAY_IN_SECONDS );
		}

		$this->options = array(
			'order' => array(
				'newest' => __( 'Newest', 'crb' ),
				'price-asc' => __( 'Price Low to High', 'crb' ),
				'price-desc' => __( 'Price High to Low', 'crb' ),
			),
			'min_price' => array( '' => __( 'Min Price', 'crb' ), ) + $min_price,
			'max_price' => array( '' => __( 'Max Price', 'crb' ), ) + $max_price,
			'bedrooms' => array( '' => __( 'Any Beds', 'crb' ), ) + $bedrooms,
			'bathrooms' => array( '' => __( 'Any Baths', 'crb' ), ) + $bathrooms,
			'type' => array( '' => __( 'All', 'crb' ), ) + $this->types,
			'waterfronts' => array( '' => __( 'Waterfront', 'crb' ), ) + $this->waterfronts,
			'neighborhoods' => array( '' => __( 'Neighborhood', 'crb' ), ) + $this->neighborhoods,
			'community_amenities' => array( '' => __( 'Community Amenities', 'crb' ), ) + $this->community_amenities,
			'year_built' => array( '' => __( 'Year Built', 'crb' ), ) + $this->year_built,
			'sub_type' => array( '' => __( 'Home Types', 'crb' ), ) + $this->sub_type,
		);
	}

	function init_parameters() {
		if ( ! is_page_template( $this->properties_template ) ) {
			return;
		}

		foreach ( $this->parameters as $parameter => $value ) {
			$post_value = crb_request_param( $parameter );
			if ( ! empty( $post_value ) ) {
				if ( is_string( $post_value ) ) {
					$post_value = urldecode( $post_value );
				}

				$this->parameters[$parameter] = $post_value;
			}
		}
	}

	function get_parameter( $parameter ) {
		return wp_unslash( $this->parameters[$parameter] );
	}

	function get_current( $value, $parameter, $selected = 'selected' ) {
		if ( is_array( $this->default_parameters[$parameter] ) ) {
			return in_array( $value, (array) $this->get_parameter( $parameter ) ) && $value !== '' ? $selected : '';
		} else {
			return $value == $this->get_parameter( $parameter ) && $value !== '' ? $selected : '';
		}
	}

	function get_disabled( $value, $parameter, $disabled = 'disabled' ) {
		return $value == '' ? $disabled : '';
	}

	function get_options( $parameter ) {
		return isset( $this->options[$parameter] ) ? $this->options[$parameter] : '';
	}

	function get_link( $value, $parameter ) {
		if ( $this->default_parameters[$parameter] == $value ) {
			return remove_query_arg( $parameter, $this->template_url_with_params );
		} else {
			return add_query_arg( $parameter, $value, $this->template_url_with_params );
		}
	}

	function get_active_parameters() {
		$output = [];

		// Manuall Array Diff based on Keys
		foreach ( $this->parameters as $key => $value ) {
			if ( $this->parameters[$key] == $this->default_parameters[$key] ) {
				continue;
			}

			$output[] = $value;
		}

		return $output;
	}

	function get_default_parameters() {
		return $this->default_parameters;
	}

	function get_fields( $prefix = '', $fields_methods ) {
		$fields = array();

		foreach ( $this->default_parameters as $parameter => $default_value ) {
			$nice_name = ucfirst( str_replace( array( '_', '-' ), ' ', $parameter ) );

			$options = $this->get_options( $parameter );
			if ( ! empty( $options ) ) {
				$field = Field::make( 'select', $prefix . $parameter, $nice_name )
					->set_options( $options );
			} else {
				$field = Field::make( 'text', $prefix . $parameter, $nice_name );
			}

			foreach ( $fields_methods as $method => $method_parameters ) {
				$field->$method( $method_parameters );
			}

			$field->set_default_value( $default_value );

			$fields[] = $field;
		}

		return $fields;
	}

	function get_slider_parameters( $post_id, $prefix ) {
		$parameters = array();

		foreach ( $this->default_parameters as $parameter => $default_value ) {
			$parameters[$parameter] = carbon_get_post_meta( $post_id, $prefix . $parameter );
			if ( empty( $parameters[$parameter] ) ) {
				$parameters[$parameter] = $default_value;
			}
		}

		return $parameters;
	}

	private function load_from_cache() {
		$cache_keys = array(
			'where_post__in',
			'included_post_ids',
			'waterfronts',
			'neighborhoods',
			'community_amenities',
			'year_built',
			'sub_type',
			'types',
		);

		foreach ( $cache_keys as $key ) {
			$cache = $this->cache->get( $key );
			if ( $cache !== false ) {
				$this->$key = $cache;
			}
		}
	}
}
