<?php

if ( empty( $section ) ) {
	return;
}

$count = 4;
if ( ! empty( $section['count'] ) ) {
	$count = absint( $section['count'] );
}

// Protect against arbitrary paged values
$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;

$args = array(
	'post_type'      => 'post',
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

$posts_query = new WP_Query( $args );
if ( ! $posts_query->have_posts() ) {
	return;
}

?>

<section class="section-main">
	<?php if ( ! empty( $section['title'] ) ): ?>
		<header class="section__head">
			<div class="shell">
				<h2><?php echo apply_filters( 'the_title', $section['title'] ); ?></h2>
			</div><!-- /.shell -->
		</header><!-- /.section__head -->
	<?php endif; ?>

	<div class="section__body">
		<div class="shell">
			<div class="tiles tiles--1of4">
				<?php while ( $posts_query->have_posts() ): $posts_query->the_post(); ?>
					<div class="tile">
						<figure class="tile__image">
							<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( '%s', 'crb' ), get_the_title() ) ); ?>">
								<?php echo crb_wp_get_attachment_image( get_post_thumbnail_id(), 'crb_section_posts' ); ?>
							</a>
						</figure><!-- /.tile__image -->

						<div class="tile__content">
							<h5>
								<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( '%s', 'crb' ), get_the_title() ) ); ?>">
									<?php the_title(); ?>
								</a>
							</h5>

							<p>
								<i class="fa fa-clock-o" aria-hidden="true"></i>
								<?php the_time( 'j F, Y' ); ?>
							</p>
						</div><!-- /.tile__content -->
					</div><!-- /.tile -->
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			</div><!-- /.tiles -->
		</div><!-- /.shell -->
	</div><!-- /.section__body -->
</section><!-- /.section-main -->
