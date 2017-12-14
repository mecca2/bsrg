<?php

if ( empty( $agent_id ) ) {
	return;
}

$agent = get_post( $agent_id );

?>

<div class="agent-business-card">
	<div class="widget__body">
		<?php if ( has_post_thumbnail( $agent_id ) ): ?>
			<?php echo crb_wp_get_attachment_image( get_post_thumbnail_id( $agent_id ), 'crb_agent_business_card' ); ?>
		<?php endif; ?>

		<h5><?php echo get_the_title( $agent ); ?></h5>

		<?php
		$position = carbon_get_post_meta( $agent_id, 'crb_agent_position' );
		if ( ! empty( $position ) ): ?>
			<p><?php echo apply_filters( 'the_title', $position ); ?></p>
		<?php endif; ?>

		<?php
		$phone = carbon_get_post_meta( $agent_id, 'crb_agent_phone' );
		if ( ! empty( $phone ) ): ?>
			<a href="<?php echo esc_url( 'tel:' . $phone ); ?>">
				<i class="fa fa-phone" aria-hidden="true"></i>
				<?php echo apply_filters( 'the_title', $phone ); ?>
			</a>
		<?php endif; ?>
	</div><!-- /.widget__body -->
</div><!-- /.agent-business-card -->
