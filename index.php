<?php
get_header();
get_template_part( 'fragments/intro' );
?>

<section class="section-main">
	<div class="shell">
		<div <?php post_class( 'post page' ); ?>>
			<?php crb_the_title( '<h2 class="page__title pagetitle">', '</h2>' ); ?>
			<div class="post__entry">
				<?php
				if ( is_single() ) {
					get_template_part( 'loop', 'single' );
				} else {
					get_template_part( 'loop' );
				}
				?>
			</div><!-- /.post__entry -->
		</div><!-- /.post -->
	</div><!-- /.shell -->
</section><!-- /.section-main -->

<?php get_footer(); ?>
