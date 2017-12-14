<?php
$prefix = 'crb_property_';

$agent_id = carbon_get_the_post_meta( 'crb_agent_id' );

// Protect against arbitrary paged values
$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;

$properties_query = new WP_Query( array(
	'post_type'      => 'crb_property',
	'post_status'    => 'publish',
	'order'          => 'DESC',
	'orderby'        => 'modified',
	'posts_per_page' => 4,
	'paged'          => $paged,
	'meta_key'       => '_' . $prefix . \Crb\Mapper::get( 'agent_id' ),
	'meta_value'     => $agent_id,
) );

if ( ! $properties_query->have_posts() ) {
	return;
}

?>

<div class="list-listings">
	<h4><?php _e( 'Active Listings', 'crb' ); ?></h4>

	<ul>
		<?php
		while ( $properties_query->have_posts() ): $properties_query->the_post();
			$property_obj = new \Crb\Property( get_the_id() );

			$address = $property_obj->get_address();
			$details = $property_obj->get_details();
			$price = $property_obj->get_price();

			?>

			<li>
				<a href="<?php the_permalink(); ?>">
					<?php
					$gallery = carbon_get_the_post_meta( $prefix . 'gallery' );
					if ( ! empty( $gallery ) && ! empty( $gallery[0] ) && ! empty( $gallery[0]['url'] ) ): ?>
						<img src="<?php echo esc_url( $gallery[0]['url'] ); ?>" alt="" />
					<?php endif; ?>

					<div class="list__content">
						<?php if ( ! empty( $price ) ): ?>
							<h3><?php echo $price; ?></h3>
						<?php endif; ?>

						<p><?php the_title(); ?></p>

						<?php if ( ! empty( $address ) ): ?>
							<p>
								<strong><?php echo apply_filters( 'the_title', $address ); ?></strong>
							</p>
						<?php endif; ?>
						
						<?php crb_render_fragment( 'property/details', array( 'details' => $details ) ); ?>
					</div>
				</a>
			</li>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
	</ul>
</div><!-- /.list-listings -->
