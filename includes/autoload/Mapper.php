<?php

namespace Crb;

class Mapper {
	use Title;

	private $instance;

	// Reflects how the Meta Key is stored in database
	// Those are hardcoded keys, and may need adjustments in feature if the API updates
	public $base_keys = array(
		'api_id' => array(
			'meta' => 'internal_listing_id',
			'sql' => '',
			'title' => 'MLS number',
		),
		'post_modified' => array(
			'meta' => 'timestamp',
			'sql' => '',
			'title' => '',
		),
		'post_date'     => array(
			'meta' => 'entry_timestamp',
			'sql' => '',
			'title' => '',
		),
		'type' => array(
			'meta' => 'property_type',
			'sql' => '',
			'title' => '',
		),
		'sub_type' => array(
			'meta' => 'sub_type',
			'sql' => '',
			'title' => '',
		),
		'agent_id'      => array(
			'meta' => 'agent_id',
			'sql' => '',
			'title' => '',
		),
		'price'         => array(
			'meta' => 'list_price',
			'sql' => '',
			'title' => '',
		),
		'remarks'       => array(
			'meta' => 'remarks',
			'sql' => '',
			'title' => '',
		),
		'neighborhood' => array(
			'meta' => 'minor_area',
			'sql' => '',
			'title' => '',
		),
		'street_number' => array(
			'meta' => 'street_number',
			'sql' => '',
			'title' => '',
		),
		'street_prefix'   => array(
			'meta' => 'street_dir_prefix',
			'sql' => '',
			'title' => '',
		),
		'street_name'   => array(
			'meta' => 'street_name',
			'sql' => '',
			'title' => '',
		),
		'unit_number'   => array(
			'meta' => 'unit_number',
			'sql' => '',
			'title' => '',
		),
		'street_dir_suffix'   => array(
			'meta' => 'street_dir_suffix',
			'sql' => '',
			'title' => '',
		),
		'street_suffix'   => array(
			'meta' => 'street_suffix',
			'sql' => '',
			'title' => '',
		),
		'city'          => array(
			'meta' => 'city',
			'sql' => '',
			'title' => '',
		),
		'state'         => array(
			'meta' => 'state_or_province',
			'sql' => '',
			'title' => '',
		),
		'postal_code'   => array(
			'meta' => 'postal_code',
			'sql' => '',
			'title' => '',
		),
		'bedrooms'   => array(
			'meta' => 'bedrooms',
			'sql' => '',
			'title' => '',
		),
		'bathrooms'   => array(
			'meta' => 'baths_total',
			'sql' => '',
			'title' => '',
		),
		'sqft'   => array(
			'meta' => 'aprox_sq_ft',
			'sql' => '',
			'title' => '',
		),
		'sale'   => array(
			'meta' => 'for_sale_for_rent',
			'sql' => '',
			'title' => '',
		),
		'lat'   => array(
			'meta' => 'geo_lat',
			'sql' => '',
			'title' => '',
		),
		'lng'   => array(
			'meta' => 'geo_lon',
			'sql' => '',
			'title' => '',
		),
		'year_built' => array(
			'meta' => 'year_built',
			'sql' => '',
			'title' => '',
		),
		'garage_carport' => array(
			'meta' => 'garage_carport',
			'sql' => '',
			'title' => '',
		),
		'fireplace' => array(
			'meta' => 'fireplace',
			'sql' => '',
			'title' => '',
		),
		'features' => array(
			'meta' => 'community_amenities',
			'sql' => '',
			'title' => '',
		),
		'community_amenities' => array(
			'meta' => 'community_amenities',
			'sql' => '',
			'title' => '',
		),
		'schools' => array(
			'meta' => 'school_district',
			'sql' => '',
			'title' => '',
		),
		'food' => array(
			'meta' => 'dining',
			'sql' => '',
			'title' => '',
		),
		'waterfront' => array(
			'meta' => 'waterfront',
			'sql' => '',
			'title' => '',
		),
		'equipment' => array(
			'meta' => 'appliances_equipment',
			'sql' => '',
			'title' => '',
		),
		'basement' => array(
			'meta' => 'basement',
			'sql' => '',
			'title' => '',
		),
		'dryer' => array(
			'meta' => 'dryer_connection',
			'sql' => '',
			'title' => '',
		),
		'family_room' => array(
			'meta' => 'family_room',
			'sql' => '',
			'title' => '',
		),
		'floors' => array(
			'meta' => 'floors',
			'sql' => '',
			'title' => '',
		),
		'roofing' => array(
			'meta' => 'roofing',
			'sql' => '',
			'title' => '',
		),
		'shared_interest' => array(
			'meta' => 'shared_interest_y_n',
			'sql' => '',
			'title' => '',
		),
		'walls' => array(
			'meta' => 'walls',
			'sql' => '',
			'title' => '',
		),
		'water_sewer' => array(
			'meta' => 'water_sewer',
			'sql' => '',
			'title' => '',
		),
		'zoning' => array(
			'meta' => 'zoning',
			'sql' => '',
			'title' => '',
		),
		'age' => array(
			'meta' => 'approx_age',
			'sql' => '',
			'title' => 'Age',
		),
		'construction' => array(
			'meta' => 'construction',
			'sql' => '',
			'title' => '',
		),
		'exterior_extras' => array(
			'meta' => 'exterior_extras',
			'sql' => '',
			'title' => '',
		),
		'foundation' => array(
			'meta' => 'foundation',
			'sql' => '',
			'title' => '',
		),
		'heating' => array(
			'meta' => 'heating',
			'sql' => '',
			'title' => '',
		),
		'other_rooms' => array(
			'meta' => 'other_rooms',
			'sql' => '',
			'title' => '',
		),
		'days_on_market' => array(
			'meta' => 'days_on_market',
			'sql' => '',
			'title' => 'Approx Days On Market',
		),
		'style' => array(
			'meta' => 'style',
			'sql' => '',
			'title' => '',
		),
		'washer_connection' => array(
			'meta' => 'washer_connection',
			'sql' => '',
			'title' => '',
		),
		'water_heater' => array(
			'meta' => 'water_heater',
			'sql' => '',
			'title' => '',
		),
	);

	public $sql_keys = array();

	private function __construct() {
		$this->cache = new Rets_Cache( 'Mapper' );
		$this->load_from_cache();

		if ( isset( $this->keys ) ) {
			return $this->keys;
		}

		try {
			$legend = new \Crb\Rets_Legend();
			$this->filtered_fields = $legend->get_filtered_fields_to_be_displayed();
			$this->fields = $legend->get_fields();
			$this->fields = reset( $this->fields ); // flatten fields, removing the "type" from it

			$this->keys = $this->base_keys;
			foreach ( $this->base_keys as $key => $key_data ) {
				$key_data['sql'] = $this->filtered_fields[ $key_data['meta'] ];

				// Set title if it is not manually setup
				if ( empty( $key_data['title'] ) ) {
					$field = $this->fields[ $key_data['sql'] ];
					$key_data['title'] = $this->get_title( $field );
				}

				$this->keys[$key] = $key_data;
			}

			$this->cache->set( 'keys', $this->keys, DAY_IN_SECONDS );

		} catch ( \Exception $e ) {
			echo '<pre add_action \Exception>';
			echo $e->getMessage();
			echo '</pre>';
		}
	}

	public static function get_instance() {
		static $instance;

		if ( ! isset( $instance ) ) {
			$instance = new Mapper();
		}

		return $instance;
	}

	public static function get( $key ) {
		$instance = self::get_instance();

		if ( ! isset( $instance->keys[$key] ) || ! isset( $instance->keys[$key]['meta'] ) ) {
			return false;
		}

		return $instance->keys[$key]['meta'];
	}

	public static function get_pm( $key ) {
		return self::get( $key );
	}

	public static function get_sql( $key ) {
		$instance = self::get_instance();

		if ( ! isset( $instance->keys[$key] ) || ! isset( $instance->keys[$key]['sql'] ) ) {
			return false;
		}

		return $instance->keys[$key]['sql'];
	}

	public static function get_field_title( $key ) {
		$instance = self::get_instance();

		if ( ! isset( $instance->keys[$key] ) || ! isset( $instance->keys[$key]['title'] ) ) {
			return false;
		}

		return $instance->keys[$key]['title'];
	}

	private function load_from_cache() {
		$cache_keys = array(
			'keys',
		);

		foreach ( $cache_keys as $key ) {
			$cache = $this->cache->get( $key );
			if ( $cache !== false ) {
				$this->$key = $cache;
			}
		}
	}
}
