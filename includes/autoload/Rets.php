<?php

namespace Crb;

use Crb\Rets_Options;
use PHRETS\Configuration;
use PHRETS\Session;

class Rets {
	private $_connected = false;
	private $resource_key = 'Property';
	private $tables = array();

	public function __construct() {
		$options = Rets_Options::get_options();

		$config = Configuration::load( array(
			'login_url' => $options['login_url'],
			'username' => $options['username'],
			'password' => $options['password'],
			# 'rets_version' => '1.8',
		) );

		$rets = new Session( $config );

		$this->rets = $rets;

		$this->connect();
	}

	public function __destruct() {
		$this->disconnect();
	}

	/**
	 * Expose API for outside Use
	 * This is usefull when developing a missing feature from this API
	 */
	public function api() {
		return $this->rets;
	}

	/* ==========================================================================
		# API Queries
	========================================================================== */

		public function system() {
			if ( isset( $this->system ) ) {
				return $this->system;
			}

			$this->system = $this->rets->GetSystemMetadata();

			return $this->system;
		}

		/**
		 * Returns a list of all "Property" and other possibilities
		 *  Link:
		 *  http://retsgw.flexmls.com/rets2_3/GetMetadata?Type=METADATA-RESOURCE&ID=0&Format=COMPACT
		 */
		public function resources() {
			if ( isset( $this->resources ) ) {
				return $this->resources;
			}

			$this->resources = $this->system()->getResources();

			return $this->resources;
		}

		public function property() {
			if ( isset( $this->property ) ) {
				return $this->property;
			}

			$this->property = $this->resources()->get( $this->resource_key );

			if ( empty( $this->property ) ) {
				throw new \Exception( "'{$this->resource_key}' key does not exists within the Resources." );
			}

			return $this->property;
		}

		public function classes() {
			if ( isset( $this->classes ) ) {
				return $this->classes;
			}

			$this->classes = $this->property()->getClasses();

			return $this->classes;
		}

		public function get_class( $row ) {
			$single_class = $this->classes()->get( $row );

			if ( empty( $single_class ) ) {
				throw new \Exception( "'$row' key does not exists within the Classes." );
			}

			return $single_class;
		}

		public function tables( $class_row_key ) {
			if ( isset( $this->tables ) && isset( $this->tables[$class_row_key] ) ) {
				return $this->tables[$class_row_key];
			}

			/**
			 * Using the 1st element
			 * This is a limitation of the implementation of the "PHRETS" and not of the original "RETS" API
			 * Where the original "RETS" API will return all data, regardless of the "get_class"
			 */
			$this->tables[$class_row_key] = $this->get_class( $class_row_key )->getTable();

			if ( empty( $this->tables[$class_row_key] ) ) {
				throw new \Exception( '"table" key does not exists within the Resources.' );
			}

			return $this->tables[$class_row_key];
		}

		public function table( $class_row_key, $row ) {
			$tables = $this->tables( $class_row_key );
			$table = $this->filter( $tables, 'getDBName', 'status' );

			if ( empty( $table ) ) {
				throw new \Exception( "'$row' key does not exists within the Tables." );
			}

			return $table;
		}

		public function fields( $class_row_key ) {
			// Same code as:
			#      $this->class( $class_row_key )->getTable()
			return $this->rets->GetTableMetadata( $this->resource_key, $class_row_key );
		}

	/* ==========================================================================
		# Utilities
	========================================================================== */

		public function connect() {
			if ( $this->_connected ) {
				return;
			}

			$this->rets->Login();
		}

		public function disconnect() {
			$this->rets->Disconnect();
		}

		public function filter( $object, $method, $variable ) {
			return collect( $object )->filter( function ( $row ) use ( $method, $variable ) {
				if ( ! is_callable( array( $row, $method ) ) ) {
					throw new \Exception( "Method '$method' does not exists." );
				}

				return $row->$method() == $variable;
			} )->first();
		}
}
