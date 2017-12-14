<?php
get_header();
the_post();
?>

<?php get_template_part( 'fragments/community/intro' ); ?>

<section class="section-single">
	<header class="section__head">
		<div class="shell">
			<?php the_content(); ?>
		</div><!-- /.shell -->
	</header><!-- /.section__head -->

	<div class="section__body">
		<div class="shell">
			<?php get_template_part( 'fragments/community/features' ); ?>

			<?php get_template_part( 'fragments/callout-slider' ); ?>
			<?php get_template_part( 'fragments/community/callout-slider' ); ?>

			<?php get_template_part( 'fragments/community/slider' ); ?>

			<?php get_template_part( 'fragments/community/content' ); ?>
		</div><!-- /.shell -->
	</div><!-- /.section__body -->
</section><!-- /.section-single -->

<?php get_footer(); ?>
