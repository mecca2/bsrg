<?php

if ( ! $properties_query->have_posts() ) {
	return;
}

?>

<div class="slider slider--slideshow">
	<div class="slider__clip">
		<div class="slider__slides owl-carousel">
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

				<div class="slider__slide" style="background-image: url(<?php echo esc_url( $meta['image'] ); ?>);">
					<div class="slider__slide-content">
						<?php if ( ! empty( $price ) ): ?>
							<h2><?php echo apply_filters( 'the_title', $price ); ?></h2>
						<?php endif; ?>

						<?php if ( ! empty( $address ) ): ?>
							<p><?php echo apply_filters( 'the_title', $address ); ?></p>
						<?php endif; ?>

						<?php crb_render_fragment( 'property/details', array( 'details' => $details ) ); ?>
					</div>

					<a class="ico-full-screen" href="<?php echo esc_url( $meta['image'] ); ?>"></a>
				</div>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		</div>
	</div>
</div>
