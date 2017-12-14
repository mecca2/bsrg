<?php

namespace Crb;

use Crb\Rets_Options;
use dekor\ArrayToTextTable;

class Rets_Legend {
	use Title;
	use Rets_Api_Connect;

	# public static $rets;

	public function __construct() {
		$this->cache = new Rets_Cache( 'Rets_Legend' );

		$this->load_from_cache();
	}

	// GetMetadata?Type=METADATA-CLASS
	public function get_types() {
		if ( isset( $this->types ) ) {
			return $this->types;
		}

		$this->init_rets();

		$classes = SELF::$rets->classes();

		$this->types = array();

		foreach ( $classes as $class ) {
			$this->types[$class->getStandardName()] = $class->getClassName();
		}

		$this->cache->set( 'types', $this->types, DAY_IN_SECONDS );

		return $this->types;
	}

	// GetMetadata?Type=METADATA-LOOKUP_TYPE
	public function get_statuses() {
		if ( isset( $this->statuses ) ) {
			return $this->statuses;
		}

		$this->init_rets();

		$this->statuses = array();
		foreach ( $this->get_types() as $type ) {
			$this->statuses[$type] = array();

			$loopup_values = SELF::$rets->table( $type, 'status' )->getLookupValues();

			foreach ( $loopup_values as $lookup ) {
				$this->statuses[$type][$lookup->getShortValue()] = $lookup->getValue();
			}
		}

		$this->cache->set( 'statuses', $this->statuses, DAY_IN_SECONDS );

		return $this->statuses;
	}

	// GetMetadata?Type=METADATA-TABLE
	public function get_fields() {
		if ( isset( $this->fields ) ) {
			return $this->fields;
		}

		$this->init_rets();

		$this->fields = array();
		foreach ( $this->get_types() as $type ) {
			$this->fields[$type] = array();

			$fields = SELF::$rets->fields( $type );
			foreach ( $fields as $field_key => $field ) {
				$expose_metadata_table_values = function ( \PHRETS\Models\Metadata\Table $field ) {
					return $field->values;
				};

				// Closure::bind() actually creates a new instance of the closure
				$expose_metadata_table_values = \Closure::bind( $expose_metadata_table_values, null, $field );

				$this->fields[$type][$field_key] = $expose_metadata_table_values( $field );
			}
		}

		$this->cache->set( 'fields', $this->fields, DAY_IN_SECONDS );

		return $this->fields;
	}

	function get_filtered_fields_to_be_displayed() {
		if ( isset( $this->filtered_fields_to_be_displayed ) ) {
			return $this->filtered_fields_to_be_displayed;
		}

		$this->init_rets();

		$this->filtered_fields_to_be_displayed = array();

		foreach ( $this->get_types() as $type ) {
			$fields = $this->get_fields();
			$fields = $fields[$type];
			break;
		}

		foreach ( $fields as $field ) {
			$title = $this->get_title( $field );

			$key = $this->sanitize_key( $title );
			$this->filtered_fields_to_be_displayed[$key] = $field['SystemName'];
		}

		$this->cache->set( 'filtered_fields_to_be_displayed', $this->filtered_fields_to_be_displayed, DAY_IN_SECONDS );

		return $this->filtered_fields_to_be_displayed;
	}

	function get_all_fields() {
		$fields = $this->get_fields();

		$all_fields = wp_list_pluck( $fields, 'SystemName' );

		return $all_fields;
	}

	private function load_from_cache() {
		$cache_keys = array(
			'types',
			'statuses',
			'fields',
			'filtered_fields_to_be_displayed',
		);

		foreach ( $cache_keys as $key ) {
			$cache = $this->cache->get( $key );
			if ( $cache !== false ) {
				$this->$key = $cache;
			}
		}
	}
}
