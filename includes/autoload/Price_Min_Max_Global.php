<?php

namespace Crb;

/**
 *
 */
trait Price_Min_Max_Global {
	function get_included_post_ids() {
		if ( isset( $this->included_post_ids ) ) {
			return $this->included_post_ids;
		}

		global $wpdb;

		try {
			$legend = new \Crb\Rets_Legend();
			$types = $legend->get_types();
		} catch ( \Exception $e ) {
			echo '<pre get_included_post_ids Price_Min_Max_Global>';
			echo $e->getMessage();
			echo '</pre>';
			$types = array();
		}

		$prefix = 'crb_rets_results_limit_price_';
		$prices = array();

		foreach ( $types as $name => $key ) {
			$prices[$key] = array(
				'min' => carbon_get_theme_option( $prefix . 'min_' . strtolower( $key ) ),
				'max' => carbon_get_theme_option( $prefix . 'max_' . strtolower( $key ) ),
			);
		}


		$prefix = '_crb_property_';
		$sql_price = $prefix . Mapper::get( 'price' );
		$sql_type = $prefix . Mapper::get( 'type' );
		$offset = "\n" . str_repeat( "\t", 4 );
		$offset_large = "\n" . str_repeat( "\t", 5 );

		$where_ors = array();
		foreach ( $prices as $key => $price ) {
			if ( empty( $price['min'] ) && empty( $price['max'] ) ) {
				continue;
			}

			$where_and = array();

			$where_and[] = $wpdb->prepare( "PM1.meta_key = %s", $sql_type );
			$where_and[] = $wpdb->prepare( "PM1.meta_value = %s", $key );
			$where_and[] = $wpdb->prepare( "PM2.meta_key = %s", $sql_price );

			if ( ! empty( $price['min'] ) ) {
				$where_and[] = $wpdb->prepare( "CAST( PM2.meta_value AS SIGNED ) >= %d", $price['min'] );
			}
			if ( ! empty( $price['max'] ) ) {
				$where_and[] = $wpdb->prepare( "CAST( PM2.meta_value AS SIGNED ) <= %d", $price['max'] );
			}

			$where_ors[] = implode( "{$offset_large}AND{$offset_large}", $where_and );
		}

		$where = implode( "{$offset}){$offset}OR{$offset}({$offset_large}", $where_ors );

		if ( empty( $where ) ) {
			return;
		}

		$this->included_post_ids = $wpdb->get_col(
			"SELECT
				PM1.post_id as post_id
			FROM
				$wpdb->postmeta as PM1
			INNER JOIN $wpdb->postmeta as PM2
				ON ( PM1.post_id = PM2.post_id )
			WHERE
				(
					{$where}
				)
			GROUP BY
				post_id
			ORDER BY
				post_id
			"
		);

		$this->cache->set( 'included_post_ids', $this->included_post_ids, DAY_IN_SECONDS );

		if ( empty( $this->included_post_ids ) ) {
			return;
		}

		return $this->included_post_ids;
	}
}
