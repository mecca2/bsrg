<?php

namespace Crb;

/**
 * Build the Query for Properties Listing
 */
class Properties_Query_Args {
	use Properties_Query_Args_SQL_Hooks;
	use Price_Min_Max_Global;

	public $args = array(
		'post_type'      => 'crb_property',
		'post_status'    => 'publish',
		'order'          => 'DESC',
		'orderby'        => 'date',
		'posts_per_page' => 8,
	);
	protected $meta = array();
	protected $terms = array();
	protected $prefix = '_crb_property_';

	function __construct() {
		$this->cache = new Rets_Cache( 'Properties_Query_Args' );
		$this->load_from_cache();

		try {
			$this->filters = new \Crb\Properties_Filters();
		} catch ( \Exception $e ) {
			echo '<pre>';
			print_r( $e->getError() );
			echo '</pre>';
			return;
		}

		$this->init_args();
	}

	function init_args() {
		$this->set_paging();
		$this->set_order();
		$this->set_global_price_range();
		$this->set_meta_query();
		$this->set_term_query();
		$this->set_search();
	}

	function set_filters( $filters ) {
		$this->filters = $filters;

		$this->init_args();
	}

	function get() {
		return $this->args;
	}

	protected function set_paging() {
		// Protect against arbitrary paged values
		$this->args['paged'] = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
	}

	protected function set_order() {
		$order = $this->filters->get_parameter( 'order' );

		if ( $order == 'newest' ) {
			$this->args['order'] = 'DESC';
			$this->args['orderby'] = 'date';
		} elseif ( $order == 'price-asc' ) {
			$this->args['order'] = 'ASC';
			$this->args['orderby'] = 'meta_value_num';
			$this->set_price_meta();
		} elseif ( $order == 'price-desc' ) {
			$this->args['order'] = 'DESC';
			$this->args['orderby'] = 'meta_value_num';
			$this->set_price_meta();
		}
	}

	// Build Meta Query
	function set_meta_query() {
		$this->set_count();
		$this->set_price_range();
		$this->set_beds_count();
		$this->set_baths_count();
		$this->set_waterfronts();
		$this->set_neighborhoods();
		$this->set_community_amenities();
		$this->set_year_built();
		$this->set_sub_type();
		# $this->set_search_metas();

		if ( empty( $this->meta ) ) {
			return;
		}

		$this->args['meta_query'] = array(
			'relation' => 'AND',
		);

		foreach ( $this->meta as $query_key => $subquery ) {
			$this->args['meta_query'][$query_key] = $subquery;
		}
	}

	// Build Meta Query
	function set_term_query() {
		$this->set_types();

		if ( empty( $this->terms ) ) {
			return;
		}

		$this->args['tax_query'] = array(
			'relation' => 'AND',
		);

		foreach ( $this->terms as $query_key => $subquery ) {
			$this->args['tax_query'][$query_key] = $subquery;
		}
	}

	protected function set_price_meta() {
		$this->args['meta_key'] = $this->prefix . \Crb\Mapper::get( 'price' );
	}

	protected function set_count() {
		$count = $this->filters->get_parameter( 'count' );
		$count = absint( $count );
		if ( empty( $count ) ) {
			return;
		}

		$this->args['posts_per_page'] = $count;
	}

	protected function set_types() {
		$type = $this->filters->get_parameter( 'type' );
		if ( empty( $type ) ) {
			return;
		}

		$this->terms['types'] = array(
			'taxonomy' => 'crb_property_type',
			'field' => 'slug',
			'terms' => $type,
		);
	}

	protected function set_global_price_range() {
		$post__in = $this->get_included_post_ids();

		if ( ! empty( $post__in ) ) {
			$this->args['post__in'] = $post__in;
		}
	}

	protected function set_price_range() {
		$min_price = $this->filters->get_parameter( 'min_price' );
		$max_price = $this->filters->get_parameter( 'max_price' );

		if ( empty( $min_price ) && empty( $max_price ) ) {
			return;
		}

		$this->meta['range'] = array();
		$this->meta['range']['relation'] = 'AND';

		if ( ! empty( $min_price ) ) {
			$this->meta['range'][] = array(
				'key' => $this->prefix . \Crb\Mapper::get( 'price' ),
				'value' => $min_price,
				'compare' => '>=',
				'type' => 'NUMERIC',
			);
		}

		if ( ! empty( $max_price ) ) {
			$this->meta['range'][] = array(
				'key' => $this->prefix . \Crb\Mapper::get( 'price' ),
				'value' => $max_price,
				'compare' => '<=',
				'type' => 'NUMERIC',
			);
		}
	}

	protected function set_beds_count() {
		$bedrooms = $this->filters->get_parameter( 'bedrooms' );
		if ( empty( $bedrooms ) ) {
			return;
		}

		$compare = '=';

		if ( substr( $bedrooms, -1 ) == '*' ) {
			$bedrooms = trim( $bedrooms, '*' );
			$compare = '>=';
		}

		$this->meta['bedrooms'] = array(
			'key' => $this->prefix . \Crb\Mapper::get( 'bedrooms' ),
			'value' => $bedrooms,
			'compare' => $compare,
			'type' => 'NUMERIC',
		);
	}

	protected function set_baths_count() {
		$bathrooms = $this->filters->get_parameter( 'bathrooms' );
		if ( empty( $bathrooms ) ) {
			return;
		}

		$compare = '=';

		if ( substr( $bathrooms, -1 ) == '*' ) {
			$bathrooms = trim( $bathrooms, '*' );
			$compare = '>=';
		}

		$this->meta['bathrooms'] = array(
			'key' => $this->prefix . \Crb\Mapper::get( 'bathrooms' ),
			'value' => $bathrooms,
			'compare' => $compare,
			'type' => 'NUMERIC',
		);
	}

	protected function set_waterfronts() {
		$waterfronts = $this->filters->get_parameter( 'waterfronts' );
		if ( empty( $waterfronts ) ) {
			return;
		}

		$this->meta['waterfronts'] = array(
			'key' => $this->prefix . \Crb\Mapper::get( 'waterfront' ),
			'value' => $waterfronts,
		);
	}

	protected function set_neighborhoods() {
		$neighborhoods = $this->filters->get_parameter( 'neighborhoods' );
		if ( empty( $neighborhoods ) ) {
			return;
		}

		$this->meta['neighborhoods'] = array(
			'key' => $this->prefix . \Crb\Mapper::get( 'neighborhood' ),
			'value' => $neighborhoods,
		);
	}

	protected function set_community_amenities() {
		$community_amenities = $this->filters->get_parameter( 'community_amenities' );
		if ( empty( $community_amenities ) ) {
			return;
		}

		$community_amenities = (array) $community_amenities;
		$this->meta['community_amenities'] = array(
			'relation' => 'OR',
		);

		foreach ( $community_amenities as $value ) {
			$this->meta['community_amenities'][] = array(
				'key' => $this->prefix . \Crb\Mapper::get( 'community_amenities' ),
				'value' => $value,
				'compare' => 'LIKE',
			);
		}
	}

	protected function set_year_built() {
		$year_built = $this->filters->get_parameter( 'year_built' );
		if ( empty( $year_built ) ) {
			return;
		}

		$this->meta['year_built'] = array(
			'key' => $this->prefix . \Crb\Mapper::get( 'year_built' ),
			'value' => $year_built,
		);
	}

	protected function set_sub_type() {
		$sub_type = $this->filters->get_parameter( 'sub_type' );
		if ( empty( $sub_type ) ) {
			return;
		}

		$this->meta['sub_type'] = array(
			'key' => $this->prefix . \Crb\Mapper::get( 'sub_type' ),
			'value' => $sub_type,
		);
	}

	protected function set_search() {
		$search = $this->filters->get_parameter( 'search' );
		if ( empty( $search ) ) {
			return;
		}

		$this->args['s'] = $search;

		$this->register_default_search_modification();
	}

	protected function set_search_metas() {
		$search = $this->filters->get_parameter( 'search' );
		if ( empty( $search ) ) {
			return;
		}

		$this->meta['search'] = array();
		$this->meta['search']['relation'] = 'OR';

		$search_metas = array(
			$this->prefix . \Crb\Mapper::get( 'street_number' ),
			$this->prefix . \Crb\Mapper::get( 'street_name' ),
			$this->prefix . \Crb\Mapper::get( 'city' ),
			$this->prefix . \Crb\Mapper::get( 'neighborhood' ),
			$this->prefix . \Crb\Mapper::get( 'remarks' ),
			$this->prefix . \Crb\Mapper::get( 'community_amenities' ),
		);

		$search_array = preg_split( '~[^a-zA-Z0-9]+~', $search );
		array_unshift( $search_array, $search );

		foreach ( $search_array as $search_word ) {
			foreach ( $search_metas as $search_meta_key ) {
				$this->meta['search'][] = array(
					'key' => $search_meta_key,
					'value' => $search_word,
					'compare' => 'LIKE',
				);
			}
		}

		$this->register_search_order_optimization();
	}

	private function load_from_cache() {
		$cache_keys = array(
			'included_post_ids',
		);

		foreach ( $cache_keys as $key ) {
			$cache = $this->cache->get( $key );
			if ( $cache !== false ) {
				$this->$key = $cache;
			}
		}
	}

}
