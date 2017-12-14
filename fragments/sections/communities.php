<?php

if ( empty( $section ) ) {
	return;
}

$count = 6;
if ( ! empty( $section['count'] ) ) {
	$count = absint( $section['count'] );
}

// Protect against arbitrary paged values
$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;

$args = array(
	'post_type'      => 'crb_community',
	'post_status'    => 'publish',
	'order'          => 'ASC',
	'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
	'posts_per_page' => $count,
	'paged'          => $paged,
	'meta_key'       => '_thumbnail_id',
);

if ( ! empty( $section['featured'] ) ) {
	$ids = wp_list_pluck( $section['featured'], 'id' );

	$args = wp_parse_args( array(
		'post__in' => $ids,
		'order' => 'ASC',
		'orderby' => 'post__in',
	), $args );
}

$communities_query = new WP_Query( $args );
if ( ! $communities_query->have_posts() ) {
	return;
}

?>

<section class="section-main section-main--solid">
	<?php if ( ! empty( $section['title'] ) ): ?>
		<header class="section__head">
			<div class="shell">
				<h2><?php echo apply_filters( 'the_title', $section['title'] ); ?></h2>
			</div><!-- /.shell -->
		</header><!-- /.section__head -->
	<?php endif; ?>

	<div class="section__body">
		<div class="shell">
			<div class="slider slider--main">
				<div class="slider__clip">
					<div class="slider__slides owl-carousel">
						<?php while ( $communities_query->have_posts() ): $communities_query->the_post(); ?>
							<div class="slider__slide">
								<div class="slider__slide-image">
									<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( '%s', 'crb' ), get_the_title() ) ); ?>">
										<?php echo crb_wp_get_attachment_image( get_post_thumbnail_id(), 'crb_section_communities' ); ?>
									</a>
								</div><!-- /.slider__slide-image -->

								<div class="slider__slide-content">
									<h3><?php the_title(); ?></h3>

									<p><?php _e( 'View Community', 'crb' ); ?></p>
								</div><!-- /.slider__slide-content -->
							</div><!-- /.slider__slide -->
						<?php endwhile; ?>
						<?php wp_reset_postdata(); ?>
					</div><!-- /.slider__slides -->
				</div><!-- /.slider__clip -->
			</div><!-- /.slider slider-/-main -->
		</div><!-- /.shell -->
	</div><!-- /.section__body -->
</section><!-- /.section-main -->
