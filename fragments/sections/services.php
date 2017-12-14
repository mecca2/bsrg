<?php

if ( empty( $section ) ) {
	return;
}

?>

<div class="list-services">
	<div class="shell">
		<ul>
			<?php
			foreach ( $section['services'] as $service ):
				$style = '';
				if ( ! empty( $service['image'] ) ) {
					$style = sprintf( 'background-image: url(%s);', crb_wp_get_attachment_image_src( $service['image'], 'crb_section_services_icon' ) );
				}
				?>
				<li>
					<div class="list__content">
						<div class="list__image">
							<i class="icon" style="<?php echo esc_attr( $style ); ?>"></i>
						</div><!-- /.list__image -->

						<?php if ( ! empty( $service['title'] ) ): ?>
							<h2><?php echo apply_filters( 'the_title', $service['title'] ); ?></h2>
						<?php endif; ?>

						<?php if ( ! empty( $service['content'] ) ): ?>
							<?php echo apply_filters( 'the_content', $service['content'] ); ?>
						<?php endif; ?>

						<?php if ( ! empty( $service['button_title'] ) && ! empty( $service['button_link'] ) ): ?>
							<a href="<?php echo esc_url( $service['button_link'] ); ?>" class="btn" <?php crb_the_target( $service['button_target'] ); ?>><?php echo apply_filters( 'the_title', $service['button_title'] ); ?></a>
						<?php endif; ?>
					</div><!-- /.list__content -->
				</li>
			<?php endforeach; ?>
		</ul>
	</div><!-- /.shell -->
</div><!-- /.list-services -->
