<?php

if ( empty( $section ) || empty( $section['form'] ) ) {
	return;
}

$style = '';
if ( ! empty( $section['background'] ) ) {
	$style = sprintf( 'background-image: url(%s);', crb_wp_get_attachment_image_src( $section['background'], 'crb_section_subscribe_background' ) );
}

?>

<section class="section-subscribe" style="<?php echo esc_attr( $style ); ?>">
	<div class="shell">
		<div class="section__content">
			<?php
			crb_render_gform( $section['form'], true, array(
				'display_title'       => true,
				'display_description' => true,
				'tabindex'            => 999,
			) );
			?>
		</div><!-- /.section__content -->
	</div><!-- /.shell -->
</section><!-- /.section-subscribe -->
