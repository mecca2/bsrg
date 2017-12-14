<?php

if ( empty( $section ) ) {
	return;
}

if ( ! empty( $section['video_link'] ) ) {
	$video_obj = Carbon_Video::create( $section['video_link'] );
	$video_embed_url = $video_obj->get_embed_url();
}

?>
<section class="section-main">
	<div class="section__body">
		<div class="shell">
			<div class="cols">
				<div class="col col--1of2">
					<?php if ( ! empty( $section['accordions_title'] ) ): ?>
						<h5><?php echo apply_filters( 'the_title', $section['accordions_title'] ); ?></h5>
					<?php endif; ?>

					<?php if ( ! empty( $section['accordion_entries'] ) ): ?>
						<div class="accordion">
							<?php foreach ( $section['accordion_entries'] as $accordion ): ?>
								<div class="accordion__section">
									<a href="#" class="accordion__head">
										<?php if ( ! empty( $accordion['title'] ) ): ?>
											<h6><?php echo apply_filters( 'the_title', $accordion['title'] ); ?></h6>
										<?php endif; ?>

										<span></span>
									</a><!-- /.accordion__head -->

									<div class="accordion__body">
										<?php if ( ! empty( $accordion['content'] ) ): ?>
											<?php echo crb_content( $accordion['content'] ); ?>
										<?php endif; ?>
									</div><!-- /.accordion__body -->
								</div><!-- /.accordion__section -->
							<?php endforeach; ?>
						</div><!-- /.accordion -->
					<?php endif; ?>
				</div><!-- /.cols col col-/-1of2 -->

				<div class="col col--1of2">
					<?php if ( ! empty( $section['video_title'] ) ): ?>
						<h5><?php echo apply_filters( 'the_title', $section['video_title'] ); ?></h5>
					<?php endif; ?>

					<?php if ( ! empty( $video_embed_url ) ): ?>
						<div class="video">
							<iframe class="youtube-player" type="text/html" width="640" height="385" src="<?php echo esc_url( $video_embed_url ); ?>" allowfullscreen frameborder="0"></iframe>
						</div><!-- /.video -->
					<?php endif; ?>
				</div><!-- /.cols col col-/-1of2 -->
			</div><!-- /.cols -->
		</div><!-- /.shell -->
	</div><!-- /.section__body -->
</section><!-- /.section-main -->
