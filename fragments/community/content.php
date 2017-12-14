<?php

$image = carbon_get_the_post_meta( 'crb_community_image' );
$content = carbon_get_the_post_meta( 'crb_community_content' );

if ( empty( $image ) && empty( $content ) ) {
	return;
}

?>

<div class="post post--community">
	<div class="post__entry">
		<?php if ( ! empty( $image ) ): ?>
			<?php echo crb_wp_get_attachment_image( $image, 'crb_community_image' ); ?>
		<?php endif; ?>

		<?php if ( ! empty( $content ) ): ?>
			<?php echo crb_content( $content ); ?>
		<?php endif; ?>
	</div><!-- /.post__entry -->
</div><!-- /.post post--community -->
