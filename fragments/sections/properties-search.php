<?php

if ( empty( $section ) ) {
	return;
}

?>

<div class="intro intro--home">
	<?php
	if ( ! empty( $section['background'] ) ):
		$style = sprintf( 'background-image: url(%s)', crb_wp_get_attachment_image_src( $section['background'], 'crb_section_properties_search' ) )
		?>
		<div class="intro__image" style="<?php echo esc_attr( $style ); ?>"></div><!-- /.intro__image -->
		<?php
	elseif ( ! empty( $section['slide_show'] ) ):
		?>
		<div class="intro__image home-slider-container">
			<div class=" owl-carousel" id="home-slider"><?php
			foreach($section['slide_show'] as $key => $value){
				 ?>
				 <div class="owl-slide" width="100%" style="background-image: url(<?php echo wp_get_attachment_url($value) ?>)"></div><?php
			}
			?>
			</div>
		</div>
		<script>
		jQuery(document).ready(function() {
			jQuery("#home-slider").owlCarousel({
				navigation : true, 
				slideSpeed : 300,
				paginationSpeed : 400,
				singleItem: true,
				pagination: false,
				rewindSpeed: 500, 
				items : 1, 
				nav: true, 
				autoplay: true, 
				loop: true, 
				navText: ["<i class='ico-arrow-left'></i>","<i class='ico-arrow-right'></i>"]
				
			})
		});
		</script>
		<?
	elseif ( ! empty( $section['video'] ) ):
		$video = Carbon_Video::create( $section['video'] );

		$video->set_params( array(
			'autoplay' => 1,
			'loop'     => 1,
			'title'    => 0,
			'byline'   => 0,
			'portrait' => 0,
			'background' => 1,
		) );
		?>
		<div class="intro__video">
			<iframe src="<?php echo esc_url( $video->get_embed_url() ); ?>" width="640" height="360" frameborder="0"></iframe>

			<div class="intro__video-placeholder" style="background-image: url(<?php echo $video->get_image(); ?>);"></div><!-- /.intro__video-placeholder -->
		</div><!-- /.intro__video -->
	<?php endif; ?>

	<div class="intro__content">
		<div class="shell">
			<?php if ( ! empty( $section['title'] ) ): ?>
				<h1><?php echo apply_filters( 'the_title', $section['title'] ); ?></h1>
			<?php endif; ?>

			<?php get_template_part( 'fragments/property/filters-form' ); ?>
		</div><!-- /.shell -->
	</div><!-- /.intro__content -->
</div><!-- /.intro intro-/-home -->
