<?php

add_filter( 'cron_schedules', 'crb_cron_schedules_extend' );
function crb_cron_schedules_extend( $schedules ) {
	// add a 'twicehourly' schedule to the existing set
	$schedules['twicehourly'] = array(
		'interval' => 10 * MINUTE_IN_SECONDS,
		'display'  => __( 'Twice Hourly', 'crb' ),
	);

	// add a 'thricedaily' schedule to the existing set
	$schedules['thricedaily'] = array(
		'interval' => 8 * HOUR_IN_SECONDS,
		'display'  => __( 'Trice Daily', 'crb' ),
	);

	return $schedules;
}

add_action( 'crb_rets_get_from_api', 'crb_rets_api' );

// Cron
# wp_clear_scheduled_hook( 'crb_rets_get_from_api' );
if ( ! wp_next_scheduled( 'crb_rets_get_from_api' ) ) {
	wp_schedule_event( time(), 'thricedaily', 'crb_rets_get_from_api' );
}

add_action( 'crb_rets_update_posts', 'crb_rets_posts' );

// Cron
# wp_clear_scheduled_hook( 'crb_rets_update_posts' );
if ( ! wp_next_scheduled( 'crb_rets_update_posts' ) ) {
	wp_schedule_event( time(), 'twicehourly', 'crb_rets_update_posts' );
}
