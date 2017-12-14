<?php

namespace Crb;

use Crb\Rets_Options;

class Rets_Cache extends Cache {
	protected $prefix;

	public function __construct( $scope = '' ) {
		try {
			$options = Rets_Options::get_options();
		} catch ( \Exception $e ) {
			return $e->getMessage();
		}

		$options[] = $scope;

		$this->prefix = 'crb_rets_cache_' . md5( json_encode( $options ) ) . '_';

		// Store some user friendly information about md5 string in DB for better look-up
		update_option( 'crb_cache_key-' . $this->prefix, implode( '|-|', $options ) );
	}
}
