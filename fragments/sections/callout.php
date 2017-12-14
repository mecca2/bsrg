<?php

if ( empty( $section ) ) {
	return;
}

$style = '';
if ( ! empty( $section['background'] ) ) {
	$style = sprintf( 'background-image: url(%s);', crb_wp_get_attachment_image_src( $section['background'], 'crb_section_callout_background' ) );
}

$button = array(
	'title'  => $section['button_title'],
	'link'   => $section['button_link'],
	'target' => $section['button_target'],
);

?>

<div class="section-callout-home" style="<?php echo esc_attr( $style ); ?>">
	<div class="shell">
		<div class="section__content">
			<?php if ( ! empty( $section['title'] ) ): ?>
				<h2><?php echo apply_filters( 'the_title', $section['title'] ); ?></h2>
			<?php endif; ?>

			<?php if ( ! empty( $section['content'] ) ): ?>
				<?php echo apply_filters( 'the_content', $section['content'] ); ?>
			<?php endif; ?>

			<?php if ( ! empty( $button['title'] ) && ! empty( $button['link'] ) ): ?>
				<a href="<?php echo esc_url( $button['link'] ); ?>" class="btn" <?php crb_the_target( $button['target'] ); ?>><?php echo apply_filters( 'the_title', $button['title'] ); ?></a>
			<?php endif; ?>
		</div><!-- /.section__content -->
	</div><!-- /.shell -->
</div><!-- /.section-callout-home -->
