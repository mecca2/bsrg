<?php

$slides = carbon_get_the_post_meta( 'crb_community_slider' );
if ( empty( $slides ) ) {
	return;
}

?>

<div class="slider slider--communities">
	<div class="slider__clip">
		<div class="slider__slides owl-carousel">
			<?php foreach ( $slides as $slide ): ?>
				<div class="slider__slide">
					<div class="slider__slide-image">
						<?php echo crb_wp_get_attachment_image( $slide['image'], 'crb_community_slider' ); ?>
					</div><!-- /.slider__slide-image -->
				</div><!-- /.slider__slide -->
			<?php endforeach; ?>
		</div><!-- /.slider__slides -->
	</div><!-- /.slider__clip -->
</div><!-- /.slider -->
