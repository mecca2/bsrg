<?php

namespace Crb;

use Crb\Rets_Options;

/**
 * Saves Rets SQL entries as posts from CPT
 *
 * Important Note:
 * Storing in post_excerpt the Property ID comming from the API for quicker refference search
 *
 */
class Rets_Posts {
	use Rets_Api_Connect;

	protected $type = '';
	protected $types = array();
	protected $table_name = '';
	protected $table_names = array();
	protected $meta_prefix = '_crb_property_';
	protected $touched_entries_count = 0;
	protected $touched_entries_limit = 20;
	protected $taxonomy = 'crb_property_type';

	function __construct() {
		add_filter( 'wp_insert_post_data', array( $this, 'wp_insert_post_data' ), 99999, 2 );

		global $wpdb;

		$legend = new \Crb\Rets_Legend();
		$types = $legend->get_types();

		$this->keys = $legend->get_filtered_fields_to_be_displayed();

		foreach ( $types as $type_key => $type ) {
			$this->types[$type_key] = $type;
			$this->table_names[$type_key] = $wpdb->prefix . strtolower( 'mls_' . $type );
		}

		$this->init_sql_select();
	}

	// Force WP to respect our custom "post_modified" date used in the "insert_update_posts" method
	public function wp_insert_post_data( $data, $postarr ) {
		if ( ! empty( $postarr['post_modified'] ) ) {
			$data['post_modified'] = $postarr['post_modified'];
			$data['post_modified_gmt'] = $postarr['post_modified'];
		}

		return $data;
	}

	protected function init_sql_select() {
		$sql_select = array();
		foreach ( $this->keys as $backend_key => $api_key ) {
			$sql_select[] = sprintf( 'P.%s as %s%s', $api_key, $this->meta_prefix, $backend_key );
		}
		$sql_select = implode( ', ', $sql_select );

		$this->sql_select = $sql_select;
	}

	function pre_init_diffs() {
		try {
			foreach ( $this->types as $type_key => $type ) {
				$this->type = $this->types[$type_key];
				$this->table_name = $this->table_names[$type_key];
				$this->type_name = $type_key;

				// Callbacks
				$this->init_terms();

				$this->init_update();

				$this->init_delete();

				$this->init_add();
			}

			$this->type = '';
			$this->table_name = '';
		} catch ( \Exception $e ) {
			// Break after 10 ( $touched_entries_limit ) posts modifications
			// Allows slower chunks of code to run by limiting how many loops will be executed
			// We don't need any frontend feedback on this.
			return;
		}
	}

	function populate() {
		try {
			$this->delete_posts_without_taxonomy();

			foreach ( $this->types as $type_key => $type ) {
				$this->type = $this->types[$type_key];
				$this->table_name = $this->table_names[$type_key];
				$this->type_name = $type_key;

				$this->init_terms();

				// Callbacks
				$this->init_update();
				$this->update_modified_posts();

				$this->init_delete();
				$this->delete_missing_posts();

				$this->init_add();
				$this->add_new_posts();
			}

			$this->type = '';
			$this->table_name = '';
		} catch ( \Exception $e ) {
			// Break after 10 ( $touched_entries_limit ) posts modifications
			// Allows slower chunks of code to run by limiting how many loops will be executed
			// We don't need any frontend feedback on this.
			return;
		}

		throw new \Exception( __( 'All Properties are up to date with the current API snapshot.', 'crb' ) );
	}

	private function delete_posts_without_taxonomy() {
		$posts = get_posts( array(
			'post_type' => 'crb_property',
			'posts_per_page' => $this->touched_entries_limit,
			'tax_query' => array(
				array(
					'taxonomy' => 'crb_property_type',
					'field' => 'term_id',
					'operator' => 'NOT IN',
					'terms' => get_terms( 'crb_property_type', array( 'fields' => 'ids' ) ),
				),
			),
		) );

		if ( empty( $posts ) ) {
			return;
		}

		foreach( $posts as $post ) {
			wp_delete_post( $post->ID, true );
		};
	}

	private function init_terms() {
		$term = get_term_by( 'slug', strtolower( $this->type ), $this->taxonomy );

		if ( $term === false ) {
			$term_id = wp_insert_term( $this->type_name, $this->taxonomy, array(
				'slug' => $this->type,
			) );

			$term = get_term( $term_id, $this->taxonomy );
		}

		$this->type_term_id[$this->type] = $term->term_id;
		$this->type_term_obj[$this->type] = $term;
	}

	private function init_update() {
		global $wpdb;

		$sql_api_key = Mapper::get_sql( 'api_id' );
		$sql_post_modified = Mapper::get_sql( 'post_modified' );

		$this->validate_field_keys( compact( 'sql_api_key', 'sql_post_modified' ) );
		$term_taxonomy_id = $this->type_term_obj[$this->type]->term_taxonomy_id;

		/**
		 * Get post_id, api_id, post_date, api_date
		 * Compare post_date and api_date
		 * Filter by the current iteration Term
		 */
		$this->modified_posts[$this->type] = $wpdb->get_results(
			"SELECT
				P.ID as post_id,
				Pr.{$sql_api_key} as api_id/* ,
				CAST( P.post_modified AS datetime ) as post_date,
				CAST( Pr.{$sql_post_modified} AS datetime ) as api_date */
			FROM
				$wpdb->posts as P
			INNER JOIN {$this->table_name} as Pr
				ON ( P.post_excerpt = Pr.{$sql_api_key} )
			INNER JOIN {$wpdb->term_relationships} as TR
				ON ( P.ID = TR.object_id )
			WHERE
				CAST( P.post_modified AS datetime ) < CAST( Pr.{$sql_post_modified} AS datetime )
				AND
				P.post_type = 'crb_property'
				AND
				P.post_status = 'publish'
				AND
				TR.term_taxonomy_id = '{$term_taxonomy_id}'
			",
			ARRAY_A
		);

		update_option( '_crb_rets_posts_update_count_' . $this->type, count( $this->modified_posts[$this->type] ) );
	}

	private function init_delete() {
		global $wpdb;

		$sql_api_id = Mapper::get_sql( 'api_id' );
		$this->validate_field_keys( compact( 'sql_api_id' ) );
		$term_taxonomy_id = $this->type_term_obj[$this->type]->term_taxonomy_id;

		/**
		 * Get post_id
		 * Check if the post_excerpt (which contains the Property ID) can be found in the custom table
		 * Filter by the current iteration Term
		 */
		$this->deleted_posts[$this->type] = $wpdb->get_col(
			"SELECT
				P.ID
			FROM
				$wpdb->posts as P
			INNER JOIN {$wpdb->term_relationships} as TR
				ON ( P.ID = TR.object_id )
			WHERE
				P.post_excerpt NOT IN ( SELECT {$sql_api_id} FROM {$this->table_name} )
				AND
				P.post_type = 'crb_property'
				AND
				P.post_status = 'publish'
				AND
				TR.term_taxonomy_id = '{$term_taxonomy_id}'
			" );

		update_option( '_crb_rets_posts_delete_count_' . $this->type, count( $this->deleted_posts[$this->type] ) );
	}

	private function init_add() {
		global $wpdb;

		$sql_api_id = Mapper::get_sql( 'api_id' );
		$this->validate_field_keys( compact( 'sql_api_id' ) );

		/**
		 * Get api_id
		 * Check if the api_id cannot be found as a post_excerpt to any of the existing properties
		 *
		 * Doesnt matter if a Filter by the current iteration Term is present for the end result
		 *   however having more restrictions, can cause posts to be duplicated,
		 *   when that post is missing category setup
		 */
		$this->new_properties[$this->type] = $wpdb->get_results(
			"SELECT
				{$sql_api_id} as api_id
			FROM
				{$this->table_name}
			WHERE
				{$sql_api_id} NOT IN (
					SELECT
						P.post_excerpt
					FROM
						$wpdb->posts as P
					WHERE
						P.post_type = 'crb_property'
						AND
						P.post_status = 'publish'
				)
			", ARRAY_A );

		update_option( '_crb_rets_posts_add_count_' . $this->type, count( $this->new_properties[$this->type] ) );
	}

	protected function update_modified_posts() {
		$this->insert_update_posts( $this->modified_posts[$this->type] );
	}

	protected function delete_missing_posts() {
		foreach ( $this->deleted_posts[$this->type] as $post_id ) {
			wp_delete_post( $post_id, true );

			// Lower performance impact, allowing for more operations
			$this->touched_entries_count += 0.5;
			if ( $this->touched_entries_count >= $this->touched_entries_limit ) {
				throw new \Exception( __( 'Rets_Posts runtimes limit reached. Will continue in the next cron.', 'crb' ) );
			}
		}
	}

	protected function add_new_posts() {
		$this->insert_update_posts( $this->new_properties[$this->type] );
	}

	protected function get_street( $property_data ) {
		$street = implode( ' ', array_filter( array(
			$property_data['street_number'],
			$property_data['street_prefix'],
			$property_data['street_name'],
			$property_data['unit_number'],
			$property_data['street_dir_suffix'],
			$property_data['street_suffix'],
		) ) );

		return $street;
	}

	protected function get_address( $property_data ) {
		$street = $this->get_street( $property_data );

		$state_postal_code = implode( ' ', array_filter( array(
			$property_data['state'],
			$property_data['postal_code'],
		) ) );

		$address = implode( ', ', array_filter( array(
			$street,
			$property_data['city'],
			$state_postal_code,
		) ) );

		return $address;
	}

	protected function get_updated_amenities( $property_data ) {
		$amenities_to_inject = array(
			array(
				'amenity' => 'Golf',
				'neighborhoods' => array(
					'Callawassie Island',
					'Cat Island',
					'Dataw Island',
					'Fripp Island',
					'Oldfield',
					'Pleasant Point',
				),
			)
		);

		$neighborhood = $property_data['neighborhood'];
		$community_amenities = $property_data['community_amenities'];
		$community_amenities = explode( ',', $community_amenities );
		if ( ! empty( $neighborhood ) ) {
			foreach ( $amenities_to_inject as $entry ) {
				if (
					in_array( $neighborhood, $entry['neighborhoods'] ) &&
					! in_array( $entry['amenity'], $community_amenities )
				) {
					$community_amenities[] = $entry['amenity'];
				}
			}
		}
		$community_amenities = implode( ',', $community_amenities );

		return $community_amenities;
	}

	/**
	 * Requires
	 * @param $this->table_name;
	 * @param $properties = array( array( 'post_id' => '7402', 'api_id' => '20171117161920498005000000' ) );
	 */
	protected function insert_update_posts( $properties ) {
		global $wpdb;

		foreach ( $properties as $property ) {
			$property_api_id = $property['api_id'];

			$sql_api_id = Mapper::get_sql( 'api_id' );
			$this->validate_field_keys( compact( 'sql_api_id' ) );

			/**
			 * Get all property meta into a single Array
			 */
			$api_property = $wpdb->get_row(
				"SELECT
					{$this->sql_select}
				FROM
					$this->table_name AS P
				WHERE
					{$sql_api_id} = '{$property_api_id}'
				",
				ARRAY_A
			);

			$property_data = array(
				'post_date'           => $api_property[ $this->meta_prefix . Mapper::get( 'post_date' ) ],
				'remarks'             => $api_property[ $this->meta_prefix . Mapper::get( 'remarks' ) ],
				'post_modified'       => $api_property[ $this->meta_prefix . Mapper::get( 'post_modified' ) ],
				'street_number'       => $api_property[ $this->meta_prefix . Mapper::get( 'street_number' ) ],
				'street_prefix'       => $api_property[ $this->meta_prefix . Mapper::get( 'street_prefix' ) ],
				'street_name'         => $api_property[ $this->meta_prefix . Mapper::get( 'street_name' ) ],
				'unit_number'         => $api_property[ $this->meta_prefix . Mapper::get( 'unit_number' ) ],
				'street_dir_suffix'   => $api_property[ $this->meta_prefix . Mapper::get( 'street_dir_suffix' ) ],
				'street_suffix'       => $api_property[ $this->meta_prefix . Mapper::get( 'street_suffix' ) ],
				'city'                => $api_property[ $this->meta_prefix . Mapper::get( 'city' ) ],
				'state'               => $api_property[ $this->meta_prefix . Mapper::get( 'state' ) ],
				'postal_code'         => $api_property[ $this->meta_prefix . Mapper::get( 'postal_code' ) ],
				'neighborhood'        => $api_property[ $this->meta_prefix . Mapper::get( 'neighborhood' ) ],
				'community_amenities' => $api_property[ $this->meta_prefix . Mapper::get( 'community_amenities' ) ],
			);

			// Modify "community_amenities" and set it back into the "$api_property"
			$community_amenities = $this->get_updated_amenities( $property_data );
			$api_property[ $this->meta_prefix . Mapper::get( 'community_amenities' ) ] = $community_amenities;

			$address = $this->get_address( $property_data );
			$street = $this->get_street( $property_data );

			$post_title = implode( ', ', array_filter( array(
				$property_data['neighborhood'],
				$address,
			) ) );

			$api_property['_crb_property_address'] = $address;
			$api_property['_crb_property_street'] = $street;

			$args = array(
				'post_author' => $this->get_admin_user_id(),
				'post_date' => $property_data['post_date'],
				'post_content' => $property_data['remarks'],
				'post_title' => $post_title,
				'post_excerpt' => $property_api_id,
				'post_status' => 'publish',
				'post_modified' => $property_data['post_modified'],
				'menu_order' => $property_api_id,
				'post_type' => 'crb_property',
				'meta_input' => $api_property,
				'tax_input' => array_filter( array(
					$this->taxonomy => $this->type_term_obj[$this->type]->term_id,
				) ),
			);

			// Prevent duplicate entries
			if ( empty( $property['post_id'] ) ) {
				/**
				 * Get post_id of the post with post_excerpt that matches the api_id
				 * Doesnt need a Filter by the current iteration Term, since api_id is unique
				 */
				$maybe_post_id = $wpdb->get_var( $wpdb->prepare(
					"SELECT
						P.ID
					FROM
						$wpdb->posts as P
					WHERE
						P.post_excerpt = %s
						AND
						P.post_type = 'crb_property'
						AND
						P.post_status = 'publish'
					",
					$property_api_id
				) );

				if ( ! empty( $maybe_post_id ) ) {
					$property['post_id'] = $maybe_post_id;
				}
			}

			// Update post if post_id is passed
			if ( ! empty( $property['post_id'] ) ) {
				$args['ID'] = $property['post_id'];
			}

			# add_filter( 'determine_current_user', array( $this, 'get_admin_user_id' ) );
			$post_id = wp_insert_post( $args );

			# in case the previous line fails
			wp_set_post_terms( $post_id, $this->type_term_obj[$this->type]->term_id, $this->taxonomy );

			# remove_filter( 'determine_current_user', array( $this, 'get_admin_user_id' ) );

			$this->update_images( $post_id, $property_api_id );

			$this->touched_entries_count++;
			if ( $this->touched_entries_count >= $this->touched_entries_limit ) {
				throw new \Exception( __( 'Rets_Posts runtimes limit reached. Will continue in the next cron.', 'crb' ) );
			}
		}

		return true;
	}

	public function update_images( $post_id, $property_api_id = null ) {
		if ( empty( $property_api_id ) ) {
			$property_api_id = carbon_get_post_meta( $post_id, $this->meta_prefix . Mapper::get( 'api_id' ) );
		}

		$this->init_rets();
		$images = SELF::$rets->api()->GetObject( "Property", "HiRes", array( $property_api_id ), '*', 1 );
		$images_meta = array();

		foreach ( $images as $image ) {
			// Skip errors
			if ( $image->getError() ) {
				continue;
			}

			$images_meta[] = array(
				'url' => $image->getLocation(),
				'is_preferred' => boolval( $image->getPreferred() ),
				'content' => $image->getContentDescription(),
			);
		}

		carbon_set_post_meta( $post_id, 'crb_property_gallery', $images_meta );
	}

	private function validate_field_keys( $field_keys ) {
		foreach ( $field_keys as $key => $value ) {
			if ( empty( $value ) ) {
				throw new \Exception( sprintf( __( '\Crb\Mapper failed to retrive the "%s" field.', 'crb' ), $key ) );
			}
		}
	}

	// Set current user to the first found admin. This is required
	private function get_admin_user_id( $user_id = null ) {
		if ( current_user_can( 'manage_options' ) ) {
			if ( empty( $user_id ) ) {
				$user_id = get_current_user_id();
			}

			return $user_id;
		}

		$admins = get_users( array( 'role' => 'administrator' ) );
		$user_id = $admins[0]->ID;

		return $user_id;
	}
}
