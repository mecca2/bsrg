<?php

namespace Crb;

/**
 *
 */
class Admin_Rets_Ajax {
	function __construct() {
		add_action( 'wp_ajax_crb_rets_get_from_api', array( $this, 'rets_api' ) );
		add_action( 'wp_ajax_crb_rets_update_posts_initial', array( $this, 'rets_posts_initial' ) );
		add_action( 'wp_ajax_crb_rets_update_posts', array( $this, 'rets_posts' ) );
		add_action( 'wp_ajax_crb_rets_force_update_all_posts', array( $this, 'force_update_all_posts' ) );
		add_action( 'wp_ajax_crb_rets_delete_all_posts', array( $this, 'delete_all_posts' ) );
		add_action( 'wp_ajax_crb_rets_flush_all_caches', array( $this, 'flush_all_caches' ) );
	}

	function rets_api() {
		crb_rets_api( false );

		$types = get_option( '_crb_rets_api_types' );

		$data = array();
		foreach ( $types as $type_name => $type_key ) {
			$last_run = get_option( '_crb_rets_api_last_run_' . $type_key );

			if ( $last_run ) {
				$data[] = sprintf( '<span class="highlight">%s</span> was pulled <span class="highlight">%s</span> ago.', $type_name, human_time_diff( $last_run, date( 'U' ) ) );
			} else {
				$data[] = sprintf( '<span class="highlight">%s</span> has never been pulled before.', $type_name );
			}
		}

		echo json_encode( array(
			'api_status' => $data,
		) );
		exit;
	}

	function rets_posts_initial() {
		try {
			$posts = new \Crb\Rets_Posts();
			$posts->pre_init_diffs();
		} catch ( \Exception $e ) {
			// Do Nothing
		}

		// $url = remove_query_arg( 'crb_rets_update_posts_initial' );
		// $url = add_query_arg( 'crb_rets_update_posts', 'true', $url );

		$update_data = SELF::get_update_data();

		$data = array(
			'status' => 'completed',
			// 'redirect' => $url,
			'data' => $update_data,
		);

		echo json_encode( $data );
		exit;
	}

	function rets_posts() {
		ob_start();
		crb_rets_posts( false );
		ob_get_clean();

		$data = SELF::get_update_data();

		if ( ! empty( $data ) ) {
			echo json_encode( $data );
		} else {
			echo json_encode( array(
				'status' => 'completed',
			) );
		}

		exit;
	}

	public static function get_update_data() {
		$data = array();

		$types = get_option( '_crb_rets_api_types' );
		foreach ( $types as $type_name => $type_key ) {
			$status = array(
				'update' => get_option( '_crb_rets_posts_update_count_' . $type_key ),
				'delete' => get_option( '_crb_rets_posts_delete_count_' . $type_key ),
				'add'    => get_option( '_crb_rets_posts_add_count_'    . $type_key ),
			);

			$format = __( 'There are <span class="highlight">%s</span> properties that needs to be %s from the <span class="highlight">%s</span>.', 'crb' );

			if ( $status['update'] ) {
				$data[] = $status['update'] ? sprintf( $format, $status['update'], 'updated', $type_name ) : '';
			}

			if ( $status['delete'] ) {
				$data[] = $status['delete'] ? sprintf( $format, $status['delete'], 'deleted', $type_name ) : '';
			}

			if ( $status['add'] ) {
				$data[] = $status['add']    ? sprintf( $format, $status['add']   , 'added'  , $type_name ) : '';
			}
		}

		return $data;
	}

	function force_update_all_posts() {
		global $wpdb;
		$test = $wpdb->query(
			"UPDATE
				$wpdb->posts
			SET
				post_modified = '0000-00-00 00:00:00',
				post_modified_gmt = '0000-00-00 00:00:00'
			WHERE
				post_type = 'crb_property'
			"
		);

		echo json_encode( array(
			'status' => 'completed',
		) );
		exit;
	}

	function delete_all_posts() {
		$posts = get_posts( array(
			'post_type' => 'crb_property',
			'posts_per_page' => 50,
		) );

		foreach( $posts as $post ) {
			wp_delete_post( $post->ID, true );
		};

		$posts_count = wp_count_posts( 'crb_property' );
		$posts_count = $posts_count->publish;

		if ( $posts_count > 0 ) {
			$result = array(
				'status' => 'wokring',
				'message' => sprintf( __( 'There are %d more posts to be deleted.', 'crb' ), $posts_count ),
			);
		} else {
			$result = array(
				'status' => 'completed',
				'message' => __( 'All posts had been deleted.', 'crb' ),
			);
		}

		echo json_encode( $result );
		exit;
	}

	function flush_all_caches() {
		global $wpdb;

		$wpdb->query(
			"DELETE
			FROM $wpdb->options
			WHERE
				option_name like '%_transient_%crb_rets_cache_%'
				OR
				option_name like 'crb_cache_key%'
			"
		);

		echo json_encode( array(
			'status' => 'completed',
		) );
		exit;
	}
}
