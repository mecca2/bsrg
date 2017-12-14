<?php

if ( empty( $section ) ) {
	return;
}

$count = 6;
if ( ! empty( $section['count'] ) ) {
	$count = absint( $section['count'] );
}

$args = array(
	'post_type'      => 'crb_property',
	'post_status'    => 'publish',
	'order'          => 'DESC',
	'orderby'        => 'modified',
	'posts_per_page' => $count,
);

if ( ! empty( $section['featured'] ) ) {
	$args['post__in'] = wp_list_pluck( $section['featured'], 'id' );
	$args['order'] = 'ASC';
	$args['orderby'] = 'post__in';
	$args['posts_per_page'] = -1;
}

$properties_query = new WP_Query( $args );

?>

<section class="section-main">
	<?php if ( ! empty( $section['title'] ) ): ?>
		<header class="section__head">
			<div class="shell">
				<h2><?php echo apply_filters( 'the_title', $section['title'] ); ?></h2>
			</div><!-- /.shell -->
		</header><!-- /.section__head -->
	<?php endif; ?>

	<?php if ( $properties_query->have_posts() ): ?>
		<div class="section__body">
			<div class="shell">
				<div class="products products--1of3">
					<?php
					$prefix = 'crb_property_';
					while ( $properties_query->have_posts() ): $properties_query->the_post();
						$property_obj = new \Crb\Property( get_the_id() );

						$meta = $property_obj->get_meta();
						$address = $property_obj->get_address();
						$details = $property_obj->get_details();
						$price = $property_obj->get_price();

						?>

						<div class="product">
							<figure class="product__image">
								<?php if ( ! empty( $meta['sale'] ) ): ?>
									<span><?php echo apply_filters( 'the_title', $meta['sale'] ); ?></span>
								<?php endif; ?>

								<?php if ( ! empty( $price ) || ! empty( $address ) ): ?>
									<div class="product__details">
										<?php if ( ! empty( $price ) ): ?>
											<h3><?php echo $price; ?></h3>
										<?php endif; ?>
									</div><!-- /.product__details -->
								<?php endif; ?>

								<?php if ( ! empty( $meta['image'] ) ): ?>
									<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( '%s', 'crb' ), get_the_title() ) ); ?>">
										<div class="background-image" style="background-image: url(<?php echo esc_url( $meta['image'] ); ?>);"></div>
									</a>
								<?php endif; ?>
							</figure><!-- /.product__image -->

							<div class="product__content">
								<?php if ( ! empty( $address ) ): ?>
									<p><?php echo apply_filters( 'the_title', $address ); ?></p>
								<?php endif; ?>

								<?php crb_render_fragment( 'property/details', array( 'details' => $details ) ); ?>
							</div><!-- /.product__content -->
						</div><!-- /.product -->
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				</div><!-- /.products -->
			</div><!-- /.shell -->
		</div><!-- /.section__body -->
	<?php endif; ?>

	<?php if ( ! empty( $section['button_title'] ) && ! empty( $section['button_link'] ) ): ?>
		<footer class="section__foot">
			<div class="shell">
				<a href="<?php echo esc_url( $section['button_link'] ); ?>" class="btn" <?php crb_the_target( $section['button_target'] ); ?>>
					<?php echo apply_filters( 'the_title', $section['button_title'] ); ?>
				</a>
			</div><!-- /.shell -->
		</footer><!-- /.section__foot -->
	<?php endif; ?>
</section><!-- /.section-main -->
