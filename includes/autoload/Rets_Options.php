<?php

namespace Crb;

use Carbon_Fields\Field\Field;

class Rets_Options {
	private function __construct() {}

	public static function get_fields() {
		try {
			$legend = new \Crb\Rets_Legend();
			$types = $legend->get_types();
		} catch ( \Exception $e ) {
			echo '<pre get_fields Rets_Options>';
			echo $e->getMessage();
			echo '</pre>';
		}

		$api_fields = array(
			Field::make( 'separator', 'crb_rets_api_separator', __( 'RETS API Settings', 'crb' ) ),
			Field::make( 'text', 'crb_rets_login_url', __( 'RETS Login URL', 'crb' ) )
				->set_required( true ),
			Field::make( 'text', 'crb_rets_username', __( 'Username', 'crb' ) )
				->set_required( true )
				->set_width( 50 ),
			Field::make( 'text', 'crb_rets_password', __( 'Password', 'crb' ) )
				->set_required( true )
				->set_width( 50 ),
		);

		$price_fields = array();
		if ( ! empty( $types ) ) {
			$prefix = 'crb_rets_results_limit_price_';

			$price_fields[] = Field::make( 'separator', $prefix . 'separator', __( 'Global Price Limit', 'crb' ) );

			foreach ( $types as $name => $key ) {
				$price_fields[] = Field::make( 'text', $prefix . 'min_' . strtolower( $key ), sprintf( __( 'Min Price for the "%s"', 'crb' ), $name ) )
					->set_width( 50 )
					->set_attribute( 'type', 'number' )
					->set_attribute( 'min', '1' )
					->set_attribute( 'max', '100000000' )
					->set_attribute( 'step', '1' )
					->set_attribute( 'pattern', '[0-9]*' );

				$price_fields[] = Field::make( 'text', $prefix . 'max_' . strtolower( $key ), sprintf( __( 'Max Price for the "%s"', 'crb' ), $name ) )
					->set_width( 50 )
					->set_attribute( 'type', 'number' )
					->set_attribute( 'min', '1' )
					->set_attribute( 'max', '100000000' )
					->set_attribute( 'step', '1' )
					->set_attribute( 'pattern', '[0-9]*' );
			}
		}

		$autopopulate_fields = array(
			Field::make( 'separator', 'crb_rets_update_separator', __( 'Get Results from RETS Api into the Website', 'crb' ) ),
			Field::make( 'html', 'crb_rets_update_button', __( 'Button', 'crb' ) )
				->set_html( Admin_Rets_Page::button_html() ),
		);

		return array_merge( $api_fields, $price_fields, $autopopulate_fields );
	}

	public static function get_options() {
		$login_url = get_option( '_crb_rets_login_url', false );
		$username  = get_option( '_crb_rets_username', false );
		$password  = get_option( '_crb_rets_password', false );

		if ( empty( $login_url ) || empty( $username ) || empty( $password ) ) {
			throw new \Exception( __( 'Please setup correctly Login URL, Username, Password for the RETS API.', 'crb' ) );
		}

		return array(
			'login_url' => $login_url,
			'username' => $username,
			'password' => $password,
		);
	}
}
