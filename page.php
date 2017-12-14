<?php get_header(); ?>

<?php get_template_part( 'fragments/intro' ); ?>

<?php while ( have_posts() ) : the_post(); ?>
	<section class="section-main">
		<div class="shell">
			<div <?php post_class( 'post page' ); ?>>
				<?php /*crb_the_title( '<h2 class="page__title pagetitle">', '</h2>' );*/ ?>

				<div class="post__entry">
					<?php
					the_content();
					carbon_pagination( 'custom', crb_get_pagination_options() );
					?>
				</div><!-- /.post__entry -->
			</div><!-- /.post -->
		</div><!-- /.shell -->
	</section><!-- /.section-main -->
<?php endwhile; ?>

<?php get_footer(); ?>
