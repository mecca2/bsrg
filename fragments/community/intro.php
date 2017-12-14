<?php

$image = carbon_get_the_post_meta( 'crb_community_intro_background' );
if ( empty( $image ) && has_post_thumbnail() ) {
	$image = get_post_thumbnail_id();
}

$style = '';
if ( ! empty( $image ) ) {
	$style = sprintf( 'background-image: url(%s);', crb_wp_get_attachment_image_src( $image, 'crb_community_intro_background' ) );
} else {
	$style = 'background-color: #ebebeb';
}

$video_link = carbon_get_the_post_meta( 'crb_community_intro_video' );
if ( ! empty( $video_link ) ) {
	$video_obj = Carbon_Video::create( $video_link );

	$video = $video_obj->get_link();
}

?>

<div class="intro">
	<div class="intro__image" style="<?php echo esc_attr( $style ); ?>"></div><!-- /.intro__image -->

	<div class="intro__content">
		<div class="shell">
			<h1><?php the_title(); ?></h1>

			<?php if ( ! empty( $video ) ): ?>
				<a href="<?php echo esc_url( $video ); ?>" class="btn-play">
					<i class="fa fa-play" aria-hidden="true"></i>
				</a>
			<?php endif; ?>
		</div><!-- /.shell -->
	</div><!-- /.intro__content -->
</div><!-- /.intro -->
