<?php
/* Template Name: Communities */
get_header();

the_post();

$count = 3;
$crb_communities_count = carbon_get_the_post_meta( 'crb_communities_count' );
if ( ! empty( $crb_communities_count ) ) {
	$count = absint( $crb_communities_count );
}

// Protect against arbitrary paged values
$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;

$communities_query = new WP_Query( array(
	'post_type'      => 'crb_community',
	'post_status'    => 'publish',
	'order'          => 'ASC',
	'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
	'posts_per_page' => $count,
	'paged'          => $paged,
	'meta_key'       => '_thumbnail_id',
) );

?>

<?php get_template_part( 'fragments/intro' ); ?>

<section class="section-main">
	<?php if ( $communities_query->have_posts() ): ?>
		<div class="section__body">
			<div class="shell">
				<div class="tiles tiles--1of3">
					<?php while ( $communities_query->have_posts() ): $communities_query->the_post(); ?>
						<div class="tile tile--secondary">
							<div class="tile__image">
								<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( '%s', 'crb' ), get_the_title() ) ); ?>">
									<?php echo crb_wp_get_attachment_image( get_post_thumbnail_id(), 'crb_communities_listing' ); ?>
								</a>
							</div><!-- /.tile__image -->

							<div class="tile__content">
								<h3><?php the_title(); ?></h3>

								<p><?php _e( 'View Community', 'crb' ); ?></p>
							</div><!-- /.tile__content -->
						</div><!-- /.tile tile-/-secondary -->
					<?php endwhile; ?>
				</div><!-- /.tiles -->

				<?php
				carbon_pagination( 'posts', crb_get_pagination_options( [
					'enable_numbers' => true,
					'current_page'   => $paged,
					'total_pages'    => $communities_query->max_num_pages,
				] ) );
				?>

				<?php wp_reset_postdata(); ?>
			</div><!-- /.shell -->
		</div><!-- /.section__body -->
	<?php endif; ?>
</section><!-- /.section-main -->

<?php get_footer(); ?>
