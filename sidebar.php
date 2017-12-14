<aside class="sidebar">
	<ul class="widgets-sidebar">
		<?php
		$page_ID = crb_get_page_context();
		$sidebar = '';

		# If $page_ID is present, check for custom sidebar
		if ( is_singular( 'crb_agent' ) ) {
			$sidebar = carbon_get_theme_option( 'crb_agent_sidebar' );
		} elseif ( is_singular( 'crb_property' ) ) {
			$sidebar = carbon_get_theme_option( 'crb_property_sidebar' );
		} elseif ( ! empty( $page_ID ) ) {
			$sidebar = carbon_get_post_meta( $page_ID, 'crb_custom_sidebar' );
		}

		# If sidebar is not set or the $page_ID is not present, assign 'default-sidebar'
		if ( empty( $sidebar ) ) {
			$sidebar = 'default-sidebar';
		}

		dynamic_sidebar( $sidebar );
		?>
	</ul><!-- /.widgets-sidebar -->
</aside><!-- /.sidebar -->
