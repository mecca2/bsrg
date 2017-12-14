<?php

namespace Crb;

class Admin_Rets_Notices {
	public function update_posts_completed() {
		?>

		<div class="notice notice-success is-dismissible" id="crb-rets-admin-notice">
			<p>
				<?php _e( 'Properties sync completed.', 'crb' ); ?>
			</p>

			<button type="button" class="notice-dismiss">
				<span class="screen-reader-text"><?php _e( 'Dismiss this notice.', 'crb' ); ?></span>
			</button>
		</div>

		<?php
	}

	public function get_from_api() {
		?>

		<div class="notice notice-info is-dismissible" id="crb-rets-admin-notice">
			<div class="loading">
				<p>
					<img src="<?php echo admin_url( 'images/loading.gif' ); ?>" width="20" alt="">
				</p>

				<p>
					<?php _e( 'Getting Properties from the API. This may take a while.', 'crb' ); ?>
				</p>
			</div><!-- /.loading -->

			<div class="finished">
				<p class="crb-js-status"></p><!-- /.crb-js-status -->

				<button type="button" class="notice-dismiss">
					<span class="screen-reader-text"><?php _e( 'Dismiss this notice.', 'crb' ); ?></span>
				</button>
			</div><!-- /.finished -->
		</div>

		<?php
	}

	public function update_posts() {
		?>

		<div class="notice notice-info" id="crb-rets-admin-notice">
			<p>
				<img src="<?php echo admin_url( 'images/loading.gif' ); ?>" width="20" alt="">
			</p>

			<p>
				<?php _e( 'Updating Properties. This may take a while.', 'crb' ); ?>
			</p>

			<p class="crb-js-status"><?php echo implode( '<br />', Admin_Rets_Ajax::get_update_data() ); ?></p><!-- /.crb-js-status -->
		</div>

		<?php
	}

	public function force_update_all_posts() {
		?>

		<div class="notice notice-info" id="crb-rets-admin-notice">
			<p>
				<img src="<?php echo admin_url( 'images/loading.gif' ); ?>" width="20" alt="">
			</p>

			<p>
				<?php _e( 'Updating Properties. This may take a while.', 'crb' ); ?>
			</p>

			<p class="crb-js-status"></p><!-- /.crb-js-status -->
		</div>

		<?php
	}

	public function flush_all_caches() {
		?>

		<div class="notice notice-info" id="crb-rets-admin-notice">
			<p>
				<img src="<?php echo admin_url( 'images/loading.gif' ); ?>" width="20" alt="">
			</p>

			<p>
				<?php _e( 'Flushing All Caches, Please wait.', 'crb' ); ?>
			</p>
		</div>

		<?php
	}

	public function flush_completed() {
		?>

		<div class="notice notice-success is-dismissible" id="crb-rets-admin-notice">
			<p>
				<?php _e( 'All theme cache has been flushed.', 'crb' ); ?>
			</p>

			<button type="button" class="notice-dismiss">
				<span class="screen-reader-text"><?php _e( 'Dismiss this notice.', 'crb' ); ?></span>
			</button>
		</div>

		<?php
	}

	public function delete_all_posts() {
		?>

		<div class="notice notice-info" id="crb-rets-admin-notice">
			<p>
				<img src="<?php echo admin_url( 'images/loading.gif' ); ?>" width="20" alt="">
			</p>

			<p>
				<?php _e( 'Posts are being deleted, Please wait.', 'crb' ); ?>
			</p>

			<p class="crb-js-status"></p><!-- /.crb-js-status -->
		</div>

		<?php
	}

	public function delete_completed() {
		?>

		<div class="notice notice-success is-dismissible" id="crb-rets-admin-notice">
			<p>
				<?php _e( 'All posts had been deleted.', 'crb' ); ?>
			</p>

			<button type="button" class="notice-dismiss">
				<span class="screen-reader-text"><?php _e( 'Dismiss this notice.', 'crb' ); ?></span>
			</button>
		</div>

		<?php
	}
}
