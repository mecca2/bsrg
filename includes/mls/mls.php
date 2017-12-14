<?php

$security_key = '7CBC4A8FBBECB114A42BB43C2C46D';

// Sample URL: /?crb_rets_get_from_api=7CBC4A8FBBECB114A42BB43C2C46D
if ( crb_request_param( 'crb_rets_get_from_api' ) === $security_key ) {
	/*if ( $_SERVER['HTTP_HOST'] == 'pkostadinov.2create.studio' ) {
		// Go Around the WP Engine cache by random parameter
		$url = 'https://bayst.wpengine.com/?crb_rets_get_from_api=7CBC4A8FBBECB114A42BB43C2C46D';
		$url = add_query_arg( 'random-lkjasdl', md5( rand() . rand() . rand() ), $url );
		$content = wp_remote_get( $url );
	}*/

	add_action( 'wp', 'crb_rets_api' );

	/*echo '<pre>';
	print_r( $url );
	echo "\n\n\n";
	print_r( $content );
	echo '</pre>';*/
}

// Sample URL: /?crb_rets_update_posts=7CBC4A8FBBECB114A42BB43C2C46D
if ( crb_request_param( 'crb_rets_update_posts' ) === $security_key ) {
	/*if ( $_SERVER['HTTP_HOST'] == 'pkostadinov.2create.studio' ) {
		// Go Around the WP Engine cache by random parameter
		$url = 'https://bayst.wpengine.com/?crb_rets_update_posts=7CBC4A8FBBECB114A42BB43C2C46D';
		$url = add_query_arg( 'random-lkjasdl', md5( rand() . rand() . rand() ), $url );
		$content = wp_remote_get( $url );
	}*/

	add_action( 'wp', 'crb_rets_posts' );

	/*echo '<pre>';
	print_r( $url );
	echo "\n\n\n";
	print_r( $content );
	echo '</pre>';*/
}

function crb_rets_api( $die = true ) {
	// if ( ! is_front_page() ) {
	// 	return;
	// }

	$type = '';

	try {
		global $wpdb;

		$legend = new \Crb\Rets_Legend();
		$property = new \Crb\Rets_Property( 100 );

		$fields_to_be_displayed = array_values( $legend->get_filtered_fields_to_be_displayed() );
		// $property->set_fields_to_be_displayed( $fields_to_be_displayed );

		$types = $legend->get_types();
		update_option( '_crb_rets_api_types', $types );

		// Run for Only 1 table, storing the last run table in DB

			$types = array_values( $types );
			$last_index = count( $types ) - 1;

			$option_key = 'crb_last_index_to_run';
			$index = get_option( $option_key, $last_index );
			$next_index = $index + 1;
			if ( $next_index > $last_index ) {
				$next_index = 0;
			}

			update_option( $option_key, $next_index );

		// Get API data and store it to DB

			$type = $types[$next_index];
			$table_name = strtolower( 'mls_' . $type );

			$db = new \Crb\SQL();
			$db->init_database( $table_name, $fields_to_be_displayed );
			$db->empty_database( $table_name );

			$page = 0;
			$results = array();

			# $debug = array();
			do {
				$page++;

				$properties_current_page = $property->get( $type, $page );
				# $debug = array_merge( $properties_current_page, $debug );
				foreach ( $properties_current_page as $row ) {
					$db->add_row( $table_name, $row );
				}

				$results = array_merge( $properties_current_page, $results );
			} while ( $property->max_num_pages() > $page );

		# echo '<pre $debug>';
		# print_r( $debug );
		# echo '</pre>';
		# exit;

	} catch ( \Exception $e ) {
		echo '<pre crb_rets_api>';
		echo $e->getMessage();
		echo '</pre>';
	}

	update_option( '_crb_rets_api_last_run_' . $type, date( 'U' ) );

	if ( $die ) {
		die();
	}
}

function crb_rets_posts( $die = true ) {
	// if ( ! is_front_page() ) {
	// 	return;
	// }

	$logged_in_user = get_current_user_id();
	if ( empty( $logged_in_user ) ) {
		$admins = get_users( array( 'role' => 'administrator' ) );
		wp_set_auth_cookie( $admins[0]->ID, false );
	}

	try {
		$posts = new \Crb\Rets_Posts();
		$posts->populate();
	} catch ( \Exception $e ) {
		echo '<pre crb_rets_posts>';
		echo $e->getMessage();
		echo '</pre>';
	}

	if ( empty( $logged_in_user ) ) {
		wp_clear_auth_cookie();
	}

	if ( $die ) {
		die();
	}
}
