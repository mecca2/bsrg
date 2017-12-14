<?php

if ( empty( $section ) ) {
	return;
}

$count = 3;
if ( ! empty( $section['count'] ) ) {
	$count = absint( $section['count'] );
}

$args = array(
	'post_type'      => 'crb_testimonial',
	'post_status'    => 'publish',
	'order'          => 'ASC',
	'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
	'posts_per_page' => $count,
	'paged'          => 1,
);

if ( ! empty( $section['featured'] ) ) {
	$args = wp_parse_args( array(
		'post__in'       => wp_list_pluck( $section['featured'], 'id' ),
		'order'          => 'ASC',
		'orderby'        => 'post__in',
		'posts_per_page' => -1,
	), $args );
}

$testimonials_query = new WP_Query( $args );
if ( ! $testimonials_query->have_posts() ) {
	return;
}

$style = '';
if ( ! empty( $section['background'] ) ) {
	$style = sprintf( 'background-image: url(%s);', crb_wp_get_attachment_image_src( $section['background'], 'crb_section_testimonials_background' ) );
}

?>

<section class="section-main section--testimonial" style="<?php echo esc_attr( $style ); ?>">
	<?php if ( ! empty( $section['title'] ) ): ?>
		<header class="section__head">
			<div class="shell">
				<h4><?php echo apply_filters( 'the_title', $section['title'] ); ?></h4>
			</div><!-- /.shell -->
		</header><!-- /.section__head -->
	<?php endif; ?>

	<div class="section__body">
		<div class="shell">
			<div class="slider slider--testimonials">
				<div class="slider__clip">
					<div class="slider__slides owl-carousel">
						<?php
						while ( $testimonials_query->have_posts() ): $testimonials_query->the_post();
							$position = carbon_get_the_post_meta( 'crb_testimonial_position' );
							?>
							<div class="slider__slide">
								<div class="slider__slide-content">
									<blockquote>
										<?php the_content(); ?>

										<cite>
											<p><?php the_title(); ?></p>

											<?php if ( ! empty( $position ) ): ?>
												<p>
													<strong>
														<?php echo apply_filters( 'the_title', $position ); ?>
													</strong>
												</p>
											<?php endif; ?>
										</cite>
									</blockquote>
								</div><!-- /.slider__slide-content -->
							</div><!-- /.slider__slide -->
						<?php endwhile; ?>
						<?php wp_reset_postdata(); ?>
					</div><!-- /.slider__slides -->
				</div><!-- /.slider__clip -->
			</div><!-- /.slider slider-/-testimonials -->
		</div><!-- /.shell -->
	</div><!-- /.section__body -->
</section><!-- /.section-main -->
