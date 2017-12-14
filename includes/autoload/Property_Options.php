<?php

namespace Crb;

use Carbon_Fields\Field\Field;

class Property_Options {
	use Options;
	use Title;

	public function get_fields() {
		$prefix = 'crb_property_';

		if ( ! isset( $this->fields ) || ! isset( $this->display_fields ) ) {
			try {
				$legend = new \Crb\Rets_Legend();
				$fields = $legend->get_fields();
				$this->display_fields = $legend->get_filtered_fields_to_be_displayed();

				$types = $legend->get_types();
				$type = $types['ResidentialProperty'];

				$this->fields = $fields[$type];

				$this->cache->set( 'fields', $this->fields, DAY_IN_SECONDS );
				$this->cache->set( 'display_fields', $this->display_fields, DAY_IN_SECONDS );

			} catch ( \Exception $e ) {
				return array(
					Field::make( 'html', 'html', __( 'Html', 'crb' ) )
						->set_html( $e->getMessage() ),
				);
			}
		}

		$carbon_fields_displayed = array(
			Field::make( 'separator', 'crb_property_displayed_separator', __( 'Public', 'crb' ) ),
		);

		$carbon_fields_api = array(
			Field::make( 'separator', 'crb_property_api_separator', __( 'Other data from API', 'crb' ) ),
		);

		foreach ( $this->fields as $field_key => $field ) {
			$type = 'text';
			$key = '';
			$title = '';

			if ( $field['DataType'] === 'Character' && $field['MaximumLength'] <= 60 ) {
				$type = 'text';
			} elseif ( $field['DataType'] === 'Character' && $field['MaximumLength'] > 60 ) {
				$type = 'textarea';
			} elseif ( $field['DataType'] === 'Decimal' ) {
				$type = 'text';
			} elseif ( $field['DataType'] === 'DateTime' ) {
				$type = 'text';
			} elseif ( $field['DataType'] === 'Date' ) {
				$type = 'text';
			}

			$nice_key = array_search( $field_key, $this->display_fields );
			if ( false !== $nice_key ) {
				$key = $nice_key;
			} else {
				$key = strtolower( $field_key );
			}
			$key = $prefix . $key;

			$title = $this->get_title( $field );
			$title = $this->sanitize_title( $title );

			if ( $nice_key !== false ) {
				$carbon_fields_displayed[] = Field::make( $type, $key, $title )
					->set_width( 33 );
			} else {
				$carbon_fields_api[] = Field::make( $type, $key, $title )
					->set_width( 33 );
			}
		}

		$custom_fields = array(
			Field::make( 'complex', $prefix . 'gallery', __( 'Gallery', 'crb' ) )
				->set_layout( 'tabbed-horizontal' )
				->add_fields( array(
					Field::make( 'image', 'url', __( 'Image', 'crb' ) )
						->set_width( 35 )
						->set_required( true )
						->set_value_type( 'url' ),
					Field::make( 'checkbox', 'is_preferred', __( 'Is Preferred', 'crb' ) )
						->set_width( 30 ),
					Field::make( 'text', 'content', __( 'Content', 'crb' ) )
						->set_width( 35 ),
				) ),
		);

		return array_merge( $custom_fields, $carbon_fields_displayed, $carbon_fields_api );
	}
}
