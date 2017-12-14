<?php

if ( empty( $section ) ) {
	return;
}

$count = 4;
if ( ! empty( $section['count'] ) ) {
	$count = absint( $section['count'] );
}

$args = array(
	'post_type'      => 'crb_agent',
	'post_status'    => 'publish',
	'order'          => 'ASC',
	'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
	'posts_per_page' => $count,
	'paged'          => 1,
	'meta_key'       => '_thumbnail_id',
);

if ( ! empty( $section['featured'] ) ) {
	$args = wp_parse_args( array(
		'post__in'       => wp_list_pluck( $section['featured'], 'id' ),
		'order'          => 'ASC',
		'orderby'        => 'post__in',
		'posts_per_page' => -1,
	), $args );
}

$agents_query = new WP_Query( $args );
if ( ! $agents_query->have_posts() ) {
	return;
}

?>

<section class="section-main section-main--solid">
	<?php if ( ! empty( $section['title'] ) ): ?>
		<header class="section__head">
			<div class="shell">
				<h4><?php echo apply_filters( 'the_title', $section['title'] ); ?></h4>
			</div><!-- /.shell -->
		</header><!-- /.section__head -->
	<?php endif; ?>

	<div class="section__body">
		<div class="shell">
			<div class="tiles tiles--1of4">
				<?php
				while ( $agents_query->have_posts() ): $agents_query->the_post();
					$position = carbon_get_the_post_meta( 'crb_agent_position' );
					$phone = carbon_get_the_post_meta( 'crb_agent_phone' );
					?>

					<div class="tile">
						<?php if ( has_post_thumbnail() ): ?>
							<div class="tile__image">
								<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( '%s', 'crb' ), get_the_title() ) ); ?>">
									<?php echo crb_wp_get_attachment_image( get_post_thumbnail_id(), 'crb_section_agents_loop' ); ?>
								</a>
							</div><!-- /.tile__image -->
						<?php endif; ?>

						<div class="tile__content">
							<h5>
								<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( '%s', 'crb' ), get_the_title() ) ); ?>">
									<?php the_title(); ?>
								</a>
							</h5>

							<?php if ( ! empty( $position ) ): ?>
								<p><?php echo apply_filters( 'the_title', $position ); ?></p>
							<?php endif; ?>

							<?php if ( ! empty( $phone ) ): ?>
								<a href="<?php echo esc_url( 'tel:' . $phone ); ?>">
									<i class="fa fa-phone" aria-hidden="true"></i>

									<?php echo apply_filters( 'the_title', $phone ); ?>
								</a>
							<?php endif; ?>
						</div><!-- /.tile__content -->
					</div><!-- /.tile -->
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			</div><!-- /.tiles -->
		</div><!-- /.shell -->
	</div><!-- /.section__body -->
</section><!-- /.section-main-/-solid -->
