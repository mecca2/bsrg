<?php

if ( empty( $section ) ) {
	return;
}

if ( ! empty( $section['video_link'] ) ) {
	$video_obj = Carbon_Video::create( $section['video_link'] );
	$video_embed_url = $video_obj->get_embed_url();
}

if ( ! empty( $section['orientation'] ) && $section['orientation'] == 'right' ) {
	$content_orientation = 'left';
} else {
	$content_orientation = 'right';
}

?>

<div class="post post--with-aside-video">
	<div class="shell">
		<div class="post__entry">
			<?php if ( $section['orientation'] == 'right' ) { ?>

				<?php if ( ! empty( $section['title'] ) || ! empty( $section['content'] ) ): ?>
					<div class="post-left">
						<div class="text-content">
						<?php if ( ! empty( $section['title'] ) ): ?>
							<h2><?php echo apply_filters( 'the_title', $section['title'] ); ?></h2>
						<?php endif; ?>

						<?php if ( ! empty( $section['content'] ) ): ?>
							<?php echo crb_content( $section['content'] ); ?>
						<?php endif; ?>
						</div>
					</div><!-- /.post-left -->
				<?php endif; ?>
				<?php if ( ! empty( $video_embed_url ) || ! empty( $section['photo_link'] ) ): ?>
					<div class="post-right">
						<?php if ( ! empty($video_embed_url) ): ?>
							<div class="video">
								<iframe class="youtube-player" type="text/html" width="640" height="385" src="<?php echo esc_url( $video_embed_url ); ?>" allowfullscreen frameborder="0"></iframe>
							</div><!-- /.post-video -->
						<?php endif; ?>

						<?php if ( ! empty( $section['photo_link'] ) ): ?>
							<div class="photo">
								<img width="640" height="385" src="<?php echo esc_url( $section['photo_link'] ); ?>" >
							</div><!-- /.post-video -->
						<?php endif; ?>
					</div><!-- /.post-right -->
				<?php endif; ?>

			<?php } else { ?>

				<?php if ( ! empty( $video_embed_url ) || ! empty( $section['photo_link'] ) ): ?>
					<div class="post-left">
						<?php if ( ! empty($video_embed_url) ): ?>
							<div class="video">
								<iframe class="youtube-player" type="text/html" width="640" height="385" src="<?php echo esc_url( $video_embed_url ); ?>" allowfullscreen frameborder="0"></iframe>
							</div><!-- /.post-video -->
						<?php endif; ?>

						<?php if ( ! empty( $section['photo_link'] ) ): ?>
							<div class="photo">
								<img width="640" height="385" src="<?php echo esc_url( $section['photo_link'] ); ?>" >
							</div><!-- /.post-video -->
						<?php endif; ?>
					</div><!-- /.post-right -->
				<?php endif; ?>
				<?php if ( ! empty( $section['title'] ) || ! empty( $section['content'] ) ): ?>
					<div class="post-right">
						<div class="text-content">
						<?php if ( ! empty( $section['title'] ) ): ?>
							<h2><?php echo apply_filters( 'the_title', $section['title'] ); ?></h2>
						<?php endif; ?>

						<?php if ( ! empty( $section['content'] ) ): ?>
							<?php echo crb_content( $section['content'] ); ?>
						<?php endif; ?>
						</div>
					</div><!-- /.post-left -->
				<?php endif; ?>
			<?php } ?>
		</div><!-- /.post__entry -->
	</div><!-- /.shell -->
</div><!-- /.post -->
