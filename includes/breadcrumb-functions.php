<?php

# Add a new custom item by specifying title, link and priority
add_action( 'carbon_breadcrumbs_after_setup_trail', 'crb_add_custom_item' );
function crb_add_custom_item( $trail ) {
	if ( ! is_singular( 'crb_property' ) ) {
		return;
	}

	// Inject Projects Template title path
	$properties_id = crb_get_id_from_template( 'templates/properties.php' );
	if ( ! empty( $properties_id ) ) {
		$locator = Carbon_Breadcrumb_Locator::factory( 'post', 'page' );
		$priority = 500;
		$page_id = $properties_id;
		$items = $locator->get_items( $priority, $page_id );

		if ( $items ) {
			$trail->add_item( $items );
		}
	}

	// Replace title with "address" meta
	$items = $trail->get_items();
	if ( ! empty( $items[1000] ) && ! empty( $items[1000][0] ) ) {
		$items[1000][0]->set_title( get_post_meta( $items[1000][0]->get_id(), '_crb_property_address', true ) );

		$trail->set_items( $items );
	}
}
