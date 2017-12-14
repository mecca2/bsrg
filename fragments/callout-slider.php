<?php

if ( empty( $slider ) ) {
	return;
}

?>

<div class="tiles tiles--1of3 owl-carousel">
	<?php foreach ( $slider as $slide ): ?>
		<div class="tile">
			<?php if ( ! empty( $slide['image'] ) ): ?>
				<figure class="tile__image">
					<?php echo crb_wp_get_attachment_image( $slide['image'], 'crb_callout_slider' ); ?>
				</figure><!-- /.tile__image -->
			<?php endif; ?>

			<div class="tile__content">
				<?php if ( ! empty( $slide['title'] ) ): ?>
					<h5><?php echo apply_filters( 'the_title', $slide['title'] ); ?></h5>
				<?php endif; ?>

				<?php if ( ! empty( $slide['button_title'] ) && ! empty( $slide['button_link'] ) ): ?>
					<a href="<?php echo esc_url( $slide['button_link'] ); ?>" <?php crb_the_target( $slide['button_target'] ); ?>><?php echo apply_filters( 'the_title', $slide['button_title'] ); ?></a>
				<?php endif; ?>
			</div><!-- /.tile__content -->
		</div><!-- /.tile -->
	<?php endforeach; ?>
</div><!-- /.tiles tiles-/-1of3 -->
