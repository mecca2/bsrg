<?php

if ( empty( $section['slider'] ) ) {
	return;
}

$slider = $section['slider'];

?>

<section class="section-main">
	<div class="section__body">
		<div class="shell">
			<?php crb_render_fragment( 'callout-slider', array( 'slider' => $slider ) ); ?>
		</div><!-- /.shell -->
	</div><!-- /.section__body -->
</section>
