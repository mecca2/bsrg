<?php get_header(); ?>

<section class="section-main">
	<div class="shell">
		<div class="post post-404">
			<?php crb_the_title( '<h2 class="page__title pagetitle">', '</h2>' ); ?>

			<div class="post__entry">
				<?php
				printf( __( '<p>Please check the URL for proper spelling and capitalization.<br />If you\'re having trouble locating a destination, try visiting the <a href="%1$s">home page</a>.</p>', 'crb' ), home_url( '/' ) );
				?>
			</div><!-- /.post__entry -->
		</div><!-- /.post -->
	</div><!-- /.shell -->
</section><!-- /.section-main -->

<?php get_footer(); ?>
