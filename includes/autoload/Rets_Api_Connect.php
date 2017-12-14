<?php

namespace Crb;

/**
 * Connect/Disconnect from RETS Api
 */
trait Rets_Api_Connect {
	public static $rets;

	private function init_rets( $rets = null ) {
		if ( isset( SELF::$rets ) ) {
			return;
		}

		if ( $rets === null ) {
			$rets = new \Crb\Rets();
		}

		if ( ! $rets instanceof Rets ) {
			throw new \Exception( 'Incorrect $rets instance.' );
			return;
		}

		SELF::$rets = $rets;
	}

	public function __destruct() {
		if ( ! isset( SELF::$rets ) ) {
			return;
		}

		SELF::$rets->disconnect();
	}
}
