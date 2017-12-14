<?php

namespace Crb;

use Crb\Rets_Options;
use PHRETS\Configuration;
use PHRETS\Session;

class Rets_Property {
	use Rets_Api_Connect;

	protected $default_query_params = array(
		'Format' => 'COMPACT-DECODED',
		'ListingStatus' => 'Active',
		'Limit'  => 100,
	);
	protected $search_results_obj;
	protected $max_num_pages;
	protected $posts_per_page;
	protected $offset;
	protected $fields_to_be_displayed = array();

	public function __construct( $posts_per_page = 100 ) {
		// Display All Fields (for Debugging)
		# $this->fields_to_be_displayed = array();

		$legend = new \Crb\Rets_Legend();

		$this->statuses = $legend->get_statuses();
		$this->fields = $legend->get_fields();

		$this->posts_per_page = $posts_per_page;
		$this->default_query_params['Limit'] = $posts_per_page;
	}

	public function set_fields_to_be_displayed( $fields ) {
		$this->fields_to_be_displayed = $fields;
	}

	public function max_num_pages() {
		return $this->max_num_pages;
	}

	/**
	 *
	 * Get PHRETS API results
	 * Includes Offset
	 * @link https://github.com/troydavisson/PHRETS/wiki/SearchQuery
	 *
	 * @param offset => Loop through a virtual pages, aka change offset
	 */
	public function get( $type, $page = 1 ) {
		$this->offset = ( $page - 1 ) * $this->posts_per_page;

		$key_status = collect( $this->fields[$type] )
			->where( 'DBName', 'status' )
			->keys()
			->first();

		$query = sprintf(
			'(%s=|%s)',
			$key_status,
			$this->statuses[$type]['Active']
		);

		$this->set_query_params();

		$this->init_rets();
		$this->search_results_obj = SELF::$rets->api()->Search( 'Property', $type, $query, $this->query_params );

		$this->max_num_pages = ceil( $this->get_total_results_count() / $this->posts_per_page );

		$results = $this->get_formated_results_obj();

		return $results;
	}

	protected function get_formated_results_obj() {
		return $this->search_results_obj->toArray();
	}

	protected function get_results_count() {
		return $this->search_results_obj->getReturnedResultsCount();
	}

	protected function get_total_results_count() {
		return $this->search_results_obj->getTotalResultsCount();
	}

	/**
	 *
	 * Set Query Options
	 * TODO: Add SELECT options for types
	 * @link https://github.com/troydavisson/PHRETS/wiki/SearchQuery
	 */
	protected function set_query_params() {
		$this->query_params = wp_parse_args(
			array(
				'offset' => $this->offset,
			),
			$this->default_query_params
		);

		if ( ! empty( $this->fields_to_be_displayed ) ) {
			$this->query_params['Select'] = implode( ',', $this->fields_to_be_displayed );
		}

		return $this->query_params;
	}
}
