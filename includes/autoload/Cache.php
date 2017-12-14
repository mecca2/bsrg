<?php

namespace Crb;

use Crb\Rets_Options;

class Cache {
	protected $prefix;

	public function __construct( $scope = '' ) {
		$this->prefix = 'crb_rets_cache_' . $scope . '_';
	}

	public function get( $key ) {
		// Cache Boost Here
		# return false;

		return get_transient( $this->prefix . $key );
	}

	public function set( $key, $value, $time = null ) {
		if ( empty( $time ) ) {
			$time = MONTH_IN_SECONDS;
			if ( empty( $value ) ) {
				$time = HOUR_IN_SECONDS;
			}
		}

		set_transient( $this->prefix . $key, $value, $time );
	}
}
