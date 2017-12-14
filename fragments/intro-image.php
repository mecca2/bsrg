<?php

if ( has_post_thumbnail() ) {
	$image = get_post_thumbnail_id();
}

$style = '';
if ( ! empty( $image ) ) {
	$style = sprintf( 'background-image: url(%s);', crb_wp_get_attachment_image_src( $image, 'crb_community_intro_background' ) );
} else {
	$style = 'background-color: #ebebeb';
}

?>

<div class="intro">
	<div class="intro__image" style="<?php echo esc_attr( $style ); ?>"></div><!-- /.intro__image -->

	<div class="intro__content">
		<div class="shell">
			<h1><?php crb_the_title(); ?></h1>
		</div><!-- /.shell -->
	</div><!-- /.intro__content -->
</div><!-- /.intro -->
