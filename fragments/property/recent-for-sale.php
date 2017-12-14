<?php

$prefix = '_crb_property_';

$properties_query = new WP_Query( array(
	'post_type'      => 'crb_property',
	'post_status'    => 'publish',
	'order'          => 'DESC',
	'orderby'        => 'date',
	'posts_per_page' => $count,
	'paged'          => 1,
	'meta_key'       => $prefix . \Crb\Mapper::get( 'sale' ),
	'meta_value'     => 'For Sale',
) );

if ( ! $properties_query->have_posts() ) {
	return;
}

?>

<ul>
	<?php
	while ( $properties_query->have_posts() ): $properties_query->the_post();
		$property = new \Crb\Property( get_the_id() );
		$meta = $property->get_meta();
		$price = $property->get_price();
		$address = $property->get_address();
		?>
		<li>
			<figure>
				<?php if ( ! empty( $price ) ): ?>
					<h6><?php echo apply_filters( 'the_title', $price ); ?></h6>
				<?php endif; ?>

				<?php if ( ! empty( $meta['image'] ) ): ?>
					<a href="<?php the_permalink(); ?>">
						<div class="background-image" style="background-image: url(<?php echo esc_url( $meta['image'] ); ?>);"></div>
					</a>
				<?php endif; ?>
			</figure>

			<p>
				<strong>
					<a href="<?php the_permalink(); ?>">
						<?php the_title(); ?>
					</a>
				</strong>
			</p>

			<?php if ( ! empty( $address ) ): ?>
				<p><?php echo apply_filters( 'the_title', $address ); ?></p>
			<?php endif; ?>
		</li>
	<?php endwhile; ?>
	<?php wp_reset_postdata(); ?>
</ul>
