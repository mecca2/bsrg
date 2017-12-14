<?php

namespace Crb;

class SQL {
	function __construct() {}

	/**
	 * Drop and re-create table
	 */
	function init_database( $name, $keys = array() ) {
		global $wpdb;

		$table_name = $wpdb->prefix . $name;
		$charset_collate = $wpdb->collate;
		//  This may cause MySQL errors, importance here is to have the same collation as the "post_date" column of "posts"
		$charset_collate = 'utf8mb4_unicode_ci';

		$wpdb->query( "DROP TABLE IF EXISTS $table_name" );

		if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {
			foreach ( $keys as $key_index => $key ) {
				$keys[$key_index] = $key . ' text,' . "\n";
			}

			$keys = implode( '', $keys );

			$sql = "CREATE TABLE $table_name (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				$keys
				PRIMARY KEY  (id)
			)
			ENGINE = MyISAM
			COLLATE $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}
	}

	/**
	 * Remove all table rows
	 */
	public function empty_database( $name ) {
		global $wpdb;

		$table_name = $wpdb->prefix . $name;

		$wpdb->query( "DELETE FROM $table_name" );
	}

	/**
	 * Inserts SQL row
	 */
	public function add_row( $name, $row ) {
		global $wpdb;

		$table_name = $wpdb->prefix . $name;

		$allowed_columns = $this->get_cols( $name );
		$allowed_columns = array_combine( $allowed_columns, $allowed_columns );

		$row = array_intersect_key( $row, $allowed_columns );

		$wpdb->insert( $table_name, $row );
	}

	/**
	 * Get all available columns
	 */
	public function get_cols( $name ) {
		global $wpdb;

		$table_name = $wpdb->prefix . $name;

		$columns = $wpdb->get_col( "SHOW COLUMNS FROM $table_name" );

		return $columns;
	}

	/**
	 * Get all rows
	 */
	public function get_all( $name, $select = '*' ) {
		global $wpdb;

		$table_name = $wpdb->prefix . $name;

		return $wpdb->get_results( "SELECT $select FROM {$table_name}", ARRAY_A );
	}

	/**
	 * Get a single row
	 */
	public function get_row( $name, $where = '1=1' ) {
		global $wpdb;

		$table_name = $wpdb->prefix . $name;

		return $wpdb->get_row( "SELECT * FROM {$table_name} WHERE $where" );
	}

}
