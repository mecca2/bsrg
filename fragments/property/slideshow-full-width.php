<?php

if ( ! $properties_query->have_posts() ) {
	return;
}

?>
<div class="slider--slideshow-full-width">
	<div id="slideshow-full-width" class="owl-carousel  ">
		<?php
		while ( $properties_query->have_posts() ): $properties_query->the_post();
			$property = new \Crb\Property( get_the_id() );
			$meta = $property->get_meta();
			$price = $property->get_price();
			$address = $property->get_address();
			$details = $property->get_details();

			if ( empty( $meta['image'] ) ) {
				continue;
			}
			?>
			<div class="owl-slide " width="100%"  height="100%"  style="background-image: url(<?php echo esc_url( $meta['image'] ); ?>);">
				<div class="slider__slide-content">
					<?php if ( ! empty( $price ) ): ?>
						<h2><?php echo apply_filters( 'the_title', $price ); ?></h2>
					<?php endif; ?>

					<?php if ( ! empty( $address ) ): ?>
						<p><?php echo apply_filters( 'the_title', $address ); ?></p>
					<?php endif; ?>

					<?php crb_render_fragment( 'property/details', array( 'details' => $details ) ); ?>
				</div>
			</div>
		<?php endwhile; ?>
	</div>
</div>
<script> 
jQuery(document).ready(function() {
 
  jQuery("#slideshow-full-width").owlCarousel({
		navigation : false, 
		slideSpeed : 200,
		paginationSpeed : 400,
		singleItem: true,
		pagination: false,
		rewindSpeed: 500, 
		items: 1, 
		autoplay: true, 
		loop: true, 
		nav:true, 
		navText: ["<i class='fa fa-angle-left' aria-hidden='true'></i>","<i class='fa fa-angle-right' aria-hidden='true'></i>"]
	});
 
});
</script>