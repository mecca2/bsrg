<?php

namespace Crb;

/**
 * Options trait with shared functionallity
 */
trait Options {
	public function __construct() {
		$this->cache = new Rets_Cache( __CLASS__ );

		$this->load_from_cache();
	}

	public function get_options( $post_id ) {
		return get_post_meta( $post_id );
	}

	public function get_option( $option_key ) {
		$option = self::get_options();
		if ( ! empty( $option[$option_key] ) ) {
			return $option[$option_key];
		}

		return '';
	}

	private function load_from_cache() {
		$cache_keys = array(
			'fields',
			'display_fields',
		);

		foreach ( $cache_keys as $key ) {
			$cache = $this->cache->get( $key );
			if ( $cache !== false ) {
				$this->$key = $cache;
			}
		}
	}
}
