<?php
/* Template Name: Properties */
get_header();

// Protect against arbitrary paged values
$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;

$args_obj = new \Crb\Properties_Query_Args();
$args = $args_obj->get();

$properties_query = new \WP_Query( $args );

$args_obj->deregister_hooks();

?>

<?php get_template_part( 'fragments/intro' ); ?>

<section class="section-main">
	<div class="section__body">
		<div class="shell">
			<div class="content content--right">
				<?php crb_render_fragment( 'fragments/property/filters-top', array( 'properties_query' => $properties_query ) ); ?>

				<?php if ( $properties_query->have_posts() ): ?>
					<div class="products products--1of2">
						<?php
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

									<div class="product__details">
										<?php if ( ! empty( $price ) ): ?>
											<h3><?php echo apply_filters( 'the_title', $price ); ?></h3>
										<?php endif; ?>

										<?php if ( ! empty( $meta['neighborhood'] ) ): ?>
											<p><?php echo apply_filters( 'the_title', $meta['neighborhood'] ); ?></p>
										<?php endif; ?>
									</div><!-- /.product__details -->

									<?php
									$background_style = '';
									if ( ! empty( $meta['image'] ) ) {
										$background_style = sprintf( 'background-image: url(%s);', $meta['image'] );
									}
									?>
									<a href="<?php the_permalink(); ?>">
										<div class="background-image" style="<?php echo esc_attr( $background_style ); ?>"></div>
									</a>
								</figure><!-- /.product__image -->

								<div class="product__content">
									<?php if ( ! empty( $address ) ): ?>
										<p>
											<a href="<?php the_permalink(); ?>">
												<?php echo apply_filters( 'the_title', $address ); ?>
											</a>
										</p>
									<?php endif; ?>

									<?php crb_render_fragment( 'property/details', array( 'details' => $details ) ); ?>
								</div><!-- /.product__content -->
							</div><!-- /.product -->
						<?php endwhile; ?>
						<?php wp_reset_postdata(); ?>
					</div><!-- /.products -->
				<?php endif; ?>

				<?php
				carbon_pagination( 'posts', crb_get_pagination_options( [
					'enable_numbers' => true,
					'current_page'   => $paged,
					'total_pages'    => $properties_query->max_num_pages,
				] ) );
				?>
			</div><!-- /.content content-/-right -->

			<?php get_sidebar(); ?>
		</div><!-- /.shell -->
	</div><!-- /.section__body -->
</section><!-- /.section-main -->

<?php get_footer(); ?>
