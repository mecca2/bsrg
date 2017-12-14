<?php

$features = carbon_get_the_post_meta( 'crb_community_features' );
if ( empty( $features ) ) {
	return;
}

?>

<div class="list-features">
	<ul>
		<?php foreach ( $features as $feature_index => $feature ): ?>
			<?php if ( $feature_index % 3 == 0 && $feature_index > 0 ): ?>
				</ul>
				<ul>
			<?php endif; ?>

			<li>
				<h4>
					<?php
					if ( ! empty( $feature['icon'] ) ):
						$icon_style = sprintf( 'background-image: url(%s)', crb_wp_get_attachment_image_src( $feature['icon'], 'crb_community_feature' ) );
						?>
						<i class="ico-custom" style="<?php echo esc_attr( $icon_style ); ?>"></i>
					<?php endif; ?>

					<?php if ( ! empty( $feature['title'] ) ): ?>
						<?php echo apply_filters( 'the_title', $feature['title'] ); ?>
					<?php endif; ?>
				</h4>

				<?php if ( ! empty( $feature['content'] ) ): ?>
					<?php echo crb_content( $feature['content'] ); ?>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ul>
</div><!-- /.list-features -->
