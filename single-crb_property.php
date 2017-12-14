<?php
get_header();

the_post();

$property_obj = new \Crb\Property( get_the_id() );

$images = $property_obj->get_images();
$meta = $property_obj->get_meta();
$address = $property_obj->get_address();
$details = $property_obj->get_details();
$additional_details = $property_obj->get_additional_details();

$price = $property_obj->get_price();

?>

<header class="main__head">
	<div class="shell">
		<h5>
			<?php if ( ! empty( $address ) ): ?>
				<?php echo apply_filters( 'the_title', $address ); ?>
			<?php endif; ?>

			<?php if ( ! empty( $meta['neighborhood'] ) ): ?>
				<p><?php echo apply_filters( 'the_title', $meta['neighborhood'] ); ?></p>
			<?php endif; ?>
		</h5>

		<?php if ( ! empty( $price ) ): ?>
			<div class="price">
				<h3><?php echo $price; ?></h3>
			</div><!-- /.price -->
		<?php endif; ?>

		<?php
		Carbon_Breadcrumb_Trail::output( array(
			'glue'              => ' &gt; ',
			'link_before'       => '<li>',
			'link_after'        => '</li>',
			'wrapper_before'    => '<nav class="nav-breadcrumbs"><ul>',
			'wrapper_after'     => '</ul></nav>',
			'title_before'      => '',
			'title_after'       => '',
			'min_items'         => 2,
			'last_item_link'    => true,
			'display_home_item' => true,
			'home_item_title'   => __( 'Home', 'crb' ),
		) );
		?>
	</div><!-- /.shell -->
</header><!-- /.main__head -->

<section class="section-property">
	<?php if ( ! empty( $images ) ): ?>
		<header class="section__head">
			<div class="shell">
				<div class="section__gallery">
					<div class="section__gallery-left">
						<?php if ( ! empty( $images[0] ) && ! empty( $images[0]['url'] ) ): ?>
							<div>
								<div class="background-image" style="background-image: url(<?php echo esc_url( $images[0]['url'] ); ?>);">
									<a href="#property-gallery-0" data-index="0"></a>
								</div>
							</div>
						<?php endif; ?>
					</div><!-- /.section__gallery-left -->

					<div class="section__gallery-right">
						<?php if ( ! empty( $images[1] ) && ! empty( $images[1]['url'] ) ): ?>
							<div>
								<div class="background-image" style="background-image: url(<?php echo esc_url( $images[1]['url'] ); ?>);">
									<a href="#property-gallery-1" data-index="1"></a>
								</div>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $images[2] ) && ! empty( $images[2]['url'] ) ): ?>
							<div>
								<div class="background-image" style="background-image: url(<?php echo esc_url( $images[2]['url'] ); ?>);">
									<a href="#property-gallery-2" data-index="2"></a>
								</div>
							</div>
						<?php endif; ?>
					</div><!-- /.section__gallery-right -->
				</div><!-- /.section__gallery -->
			</div><!-- /.shell -->
		</header><!-- /.section__head -->

		<?php
		$images = array_map( function ( $image ) {
			$new_image['src'] = $image['url'];

			return $new_image;
		}, $images );
		?>
		<div class="source-gallery" data-items="<?php echo urlencode( json_encode( $images ) ); ?>" style="display: none;"></div>
	<?php endif; ?>

	<div class="section__body">
		<div class="shell">
			<div class="content content--left">
				<article class="article-details">
					<?php if ( ! empty( $address ) ): ?>
						<h4><?php echo apply_filters( 'the_title', $address ); ?></h4>
					<?php endif; ?>

					<?php crb_render_fragment( 'property/details', array( 'details' => $details ) ); ?>

					<?php the_content(); ?>

					<h6><?php _e( 'Amenities', 'crb' ); ?></h6>

					<ul>
						<?php /*
						<li>Shared Fitness Center</li>
						*/ ?>

						<?php if ( ! empty( $meta['garage_carport'] ) ): ?>
							<li><?php printf( __( 'Parking included: %s', 'crb' ), $meta['garage_carport'] ); ?></li>
						<?php endif; ?>

						<?php /*
						<li>View: Beach</li>
						*/ ?>

						<?php if ( ! empty( $meta['waterfront'] ) ): ?>
							<li><?php printf( __( 'Waterfront: %s', 'crb' ), $meta['waterfront'] ); ?></li>
						<?php endif; ?>

						<?php /*
						<li>Garden</li>
						*/ ?>

						<?php
						if ( ! empty( $meta['features'] ) ):
							$meta['features'] = explode( ',', $meta['features'] );
							foreach ( $meta['features'] as $feature ): ?>
								<li><?php echo apply_filters( 'the_title', $feature ); ?></li>
							<?php endforeach; ?>
						<?php endif; ?>
					</ul>

					<?php if ( ! empty( $additional_details ) ): ?>
						<h6><?php _e( 'Property Details', 'crb' ); ?></h6>

						<ul class="property-details">
							<?php foreach ( $additional_details as $detail ): ?>
								<li>
									<h6><?php echo apply_filters( 'the_title', $detail['title'] ); ?>:</h6>

									<?php echo apply_filters( 'the_title', $detail['value'] ); ?>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

					<?php if ( ! empty( $meta['schools'] ) || ! empty( $meta['food'] ) ): ?>
						<h5><?php _e( 'Aditional Information', 'crb' ); ?></h5>

						<div class="list-info">
							<?php
							if ( ! empty( $meta['schools'] ) ):
								$meta['schools'] = explode( ',', $meta['schools'] );
								?>
								<ul>
									<li>
										<i class="fa fa-graduation-cap" aria-hidden="true"></i>
										<h6><?php _e( 'Schools', 'crb' ); ?></h6>
									</li>

									<?php foreach ( $meta['schools'] as $school ): ?>
										<li>
											<h5><?php echo apply_filters( 'the_title', $school ); ?></h5>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>

							<?php
							if ( ! empty( $meta['food'] ) ):
								$meta['food'] = explode( ',', $meta['food'] );
								?>
								<ul>
									<li>
										<i class="fa fa-tripadvisor" aria-hidden="true"></i>

										<h6><?php _e( 'All About Food', 'crb' ); ?></h6>
									</li>

									<?php foreach ( $meta['food'] as $food ): ?>
										<li>
											<h5><?php echo apply_filters( 'the_title', $food ); ?></h5>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>

							<?php /*
							<ul>
								<li>
									<i class="fa fa-shield" aria-hidden="true"></i>

									<h6>Crime</h6>
								</li>

								<li>
									<p>Nearby Crimes</p>

									<h5>Lowest</h5>
								</li>
							</ul>
							*/ ?>
						</div><!-- /.list-info -->
					<?php endif; ?>

					<?php if ( ! empty( $meta['neighborhood'] ) ): ?>
						<h5>
							<?php printf( __( 'Neighborhood: %s', 'crb' ), $meta['neighborhood'] ) ?>
						</h5>
					<?php endif; ?>

					<?php /*
					<p>Coosaw Point home values will rise 3.9% next year, compared to a 1.1% rise for Beaufort as a whole. Among other homes, this home is 550% more expensive than the midpoint (median) home, and is priced 500% more per square foot.</p>
					*/ ?>
				</article><!-- /.article-details -->
			</div><!-- /.content -->

			<?php get_sidebar(); ?>

			<?php if ( ! empty( $meta['lat'] ) && ! empty( $meta['lng'] ) ): ?>
				<div
					id="map-<?php the_id(); ?>"
					class="sidebar google-map google-map-<?php the_id(); ?>"
					data-lat="<?php echo esc_attr( $meta['lat'] ); ?>"
					data-lng="<?php echo esc_attr( $meta['lng'] ); ?>"
					data-zoom="10"
				></div><!-- /#map.google-map-1 -->
			<?php endif; ?>
			
		</div><!-- /.shell -->
	</div><!-- /.section__body -->
</section><!-- /.section-property -->

<?php get_footer(); ?>
