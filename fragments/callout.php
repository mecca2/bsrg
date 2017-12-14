<?php

// Hide on Communities and Properties templates
if ( is_page_template( 'templates/communities.php' ) || is_page_template( 'templates/properties.php' ) ) {
	return;
}

$icon = carbon_get_theme_option( 'crb_callout_icon' );
$title = carbon_get_theme_option( 'crb_callout_title' );
$subtitle = carbon_get_theme_option( 'crb_callout_subtitle' );
$button = array(
	'title' => carbon_get_theme_option( 'crb_callout_button_title' ),
	'link' => carbon_get_theme_option( 'crb_callout_button_link' ),
	'target' => carbon_get_theme_option( 'crb_callout_button_target' ),
);

if ( empty( $title ) && empty( $subtitle ) ) {
	return;
}

?>

<section class="section-callout">
	<div class="shell">
		<div class="section__content">
			<p>
				<?php if ( ! empty( $icon ) ): ?>
					<i class="<?php echo esc_attr( $icon['class'] ); ?>" aria-hidden="true"></i>
				<?php endif; ?>

				<?php if ( ! empty( $title ) ): ?>
					<strong><?php echo apply_filters( 'the_title', $title ); ?></strong>
				<?php endif; ?>

				<?php if ( ! empty( $subtitle ) ): ?>
					<?php echo apply_filters( 'the_title', $subtitle ); ?>
				<?php endif; ?>
			</p>

			<?php if ( ! empty( $button['title'] ) && ! empty( $button['link'] ) ): ?>
				<a href="<?php echo esc_url( $button['link'] ); ?>" class="btn" <?php crb_the_target( $button['target'] ); ?>>
					<?php echo apply_filters( 'the_title', $button['title'] ); ?>
				</a>
			<?php endif; ?>
		</div><!-- /.section__content -->
	</div><!-- /.shell -->
</section><!-- /.section-callout -->
