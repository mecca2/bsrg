<?php

$slider = carbon_get_the_post_meta( 'crb_callout_slider' );
if ( empty( $slider ) ) {
	return;
}

crb_render_fragment( 'callout-slider', array( 'slider' => $slider ) );
