<?php

namespace Crb;

trait Title {
	public function get_title( $field ) {
		$titles = array(
			$field['StandardName'],
			$field['LongName'],
			$field['ShortName'],
			$field['DBName'],
		);

		// Remove Empty Values
		$titles = array_filter( $titles );

		// Remove the SystemName
		$titles = array_diff( $titles, array( $field['SystemName'] ) );

		// Reindex array from 0
		$titles = array_values( $titles );

		$title = '';
		if ( ! empty( $titles[0] ) ) {
			$title = $titles[0];
		}

		return $title;
	}

	public function sanitize_key( $title ) {
		$title = preg_replace( '~([a-z])([A-Z])~', "$1 $2", $title );

		$title = sanitize_title( $title );
		$title = str_replace( array( '-', '__' ), '_', $title );

		return $title;
	}

	public function sanitize_title( $title ) {
		$title = preg_replace( '~([a-z])([A-Z])~', "$1 $2", $title );

		// $title = sanitize_title( $title );
		$title = str_replace( array( '-', '_' ), ' ', $title );

		$title = ucwords( $title );

		return $title;
	}
}
