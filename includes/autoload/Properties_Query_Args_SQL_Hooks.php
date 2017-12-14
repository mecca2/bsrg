<?php

namespace Crb;

trait Properties_Query_Args_SQL_Hooks {

	public function deregister_hooks() {
		$this->deregister_search_order_optimization();
	}

	/* ==========================================================================
		# Search Optimizations
	========================================================================== */

	public function register_search_order_optimization() {
		add_filter( 'posts_request', array( $this, 'search_posts_request' ) );
		add_filter( 'posts_orderby', array( $this, 'search_posts_orderby' ) );
	}

	public function register_default_search_modification() {
		add_filter( 'posts_search', array( $this, 'exclude_excerpt_from_search' ), 1000, 2 );
	}

	// Remove the Search in Post Excerpt and Post Content
	function exclude_excerpt_from_search( $search, $wp_query ) {
		if (
			! empty( $wp_query->query['s'] ) &&
			! empty( $wp_query->query['post_type'] ) && $wp_query->query['post_type'] == 'crb_property'
		) {
			$search = preg_replace( '~^( AND \(\(\(.*?post_title.*?\))(.*)(\)\))~', '$1$3', $search );
		}

		return $search;
	}

	public function deregister_search_order_optimization() {
		remove_filter( 'posts_request', array( $this, 'search_posts_request' ) );
		remove_filter( 'posts_orderby', array( $this, 'search_posts_orderby' ) );
		remove_filter( 'posts_search', array( $this, 'exclude_excerpt_from_search' ), 1000, 2 );
	}

	public function search_posts_request( $request ) {
		$search = $this->filters->get_parameter( 'search' );
		if ( empty( $search ) ) {
			return;
		}

		global $wpdb;

		// use regular expression to modify "SELECT" statement, as no filter is available for that
		$request = preg_replace('/SELECT (.*?) FROM/iu', 'SELECT $1, COUNT(`' . $wpdb->posts . '`.`post_title`) AS `search_hits` FROM', $request );

		return $request;
	}

	public function search_posts_orderby( $orderby ) {
		return " `search_hits` DESC ";
	}

}
