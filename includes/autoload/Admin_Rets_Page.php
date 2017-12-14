<?php

namespace Crb;

// use Carbon_Fields\Container\Container;
// use Carbon_Fields\Field\Field;

/**
 *
 */
class Admin_Rets_Page {
	public static $admin_page_url;

	function __construct() {
		SELF::$admin_page_url = admin_url( 'admin.php?page=crb_carbon_fields_container_rets.php' );

		add_action( 'init', array( $this, 'init' ) );
	}

	public function init() {
		$notices = new Admin_Rets_Notices();

		// API
		if ( crb_request_param( 'crb_rets_get_from_api' ) ) {
			add_action( 'admin_notices', array( $notices, 'get_from_api' ) );
			$this->enqueue_script( 'crb_rets_get_from_api' );
		}

		// Posts
		if ( crb_request_param( 'crb_rets_update_posts_initial' ) ) {
			add_action( 'admin_notices', array( $notices, 'update_posts' ) );
			$this->enqueue_script( 'crb_rets_update_posts_initial' );
		}

		if ( crb_request_param( 'ajax-completed' ) == 'crb_rets_update_posts_initial'  ) {
			wp_redirect( add_query_arg( 'crb_rets_update_posts', 'true', SELF::$admin_page_url ) );
			exit;
		}

		// Posts
		if ( crb_request_param( 'crb_rets_force_update_all_posts' ) ) {
			add_action( 'admin_notices', array( $notices, 'force_update_all_posts' ) );
			$this->enqueue_script( 'crb_rets_force_update_all_posts' );
		}

		if ( crb_request_param( 'ajax-completed' ) == 'crb_rets_force_update_all_posts'  ) {
			wp_redirect( add_query_arg( 'crb_rets_update_posts_initial', 'true', SELF::$admin_page_url ) );
			exit;
		}

		if ( crb_request_param( 'crb_rets_update_posts' ) ) {
			add_action( 'admin_notices', array( $notices, 'update_posts' ) );
			$this->enqueue_script( 'crb_rets_update_posts' );
		}

		if ( crb_request_param( 'ajax-completed' ) == 'crb_rets_update_posts'  ) {
			add_action( 'admin_notices', array( $notices, 'update_posts_completed' ) );
		}

		// DELETE All
		if ( crb_request_param( 'crb_rets_delete_all_posts' ) ) {
			add_action( 'admin_notices', array( $notices, 'delete_all_posts' ) );
			$this->enqueue_script( 'crb_rets_delete_all_posts' );
		}

		if ( crb_request_param( 'ajax-completed' ) == 'crb_rets_delete_all_posts' ) {
			add_action( 'admin_notices', array( $notices, 'delete_completed' ) );
		}

		// Caches
		if ( crb_request_param( 'crb_rets_flush_all_caches' ) ) {
			add_action( 'admin_notices', array( $notices, 'flush_all_caches' ) );
			$this->enqueue_script( 'crb_rets_flush_all_caches' );
		}

		if ( crb_request_param( 'ajax-completed' ) == 'crb_rets_flush_all_caches' ) {
			add_action( 'admin_notices', array( $notices, 'flush_completed' ) );
		}
	}

	function enqueue_script( $action ) {
		$template_dir = get_template_directory_uri();

		wp_enqueue_script( 'crb-admin-rets-autoposter', $template_dir . '/js/rets-autoposting-ajax.js', array( 'jquery' ) );
		wp_localize_script( 'crb-admin-rets-autoposter', 'ajax_object', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'action' => $action,
			'redirect' => add_query_arg( 'ajax-completed', $action, SELF::$admin_page_url ),
		) );
	}

	public static function button_html() {
		if ( ! is_admin() ) {
			return;
		}

		try {
			$options = Rets_Options::get_options();

			ob_start();
				?>

				<p>&nbsp;</p>

				<p>
					<a href="<?php echo add_query_arg( 'crb_rets_get_from_api', 'true', SELF::$admin_page_url ); ?>" class="button button-large">
						<?php _e( 'Get Properties from API', 'crb' ); ?>
					</a>

				</p>

				<p>
					<small><?php _e( 'One type per run only. Must be clicked manually multiple times.', 'crb' ); ?></small>
				</p>

				<p>&nbsp;</p>

				<p>
					<a href="<?php echo add_query_arg( 'crb_rets_update_posts_initial', 'true', SELF::$admin_page_url ); ?>" class="button button-large">
						<?php _e( 'Populate Properties', 'crb' ); ?>
					</a>
				</p>

				<p>&nbsp;</p>

				<p>
					<a href="<?php echo add_query_arg( 'crb_rets_force_update_all_posts', 'true', SELF::$admin_page_url ); ?>" class="button button-large">
						<?php _e( 'Force Update All Properties', 'crb' ); ?>
					</a>
				</p>

				<p>&nbsp;</p>

				<p>
					<a href="<?php echo add_query_arg( 'crb_rets_delete_all_posts', 'true', SELF::$admin_page_url ); ?>" class="button button-large">
						<?php _e( 'DELETE ALL Properties', 'crb' ); ?>
					</a>
				</p>

				<p>&nbsp;</p>

				<p>
					<a href="<?php echo add_query_arg( 'crb_rets_flush_all_caches', 'true', SELF::$admin_page_url ); ?>" class="button button-large">
						<?php _e( 'Flush All Caches', 'crb' ); ?>
					</a>
				</p>

				<p>&nbsp;</p>

				<?php
			$html = ob_get_clean();

			return $html;
		} catch ( \Exception $e ) {
			return $e->getMessage();
		}
	}
}
