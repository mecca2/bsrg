<?php while ( have_posts() ) : the_post(); ?>
	<article <?php post_class( 'article article--single' ) ?>>
		<header class="article__head">
			<h3 class="article__title">
				<?php the_title(); ?>
			</h3><!-- /.article__title -->

			<?php get_template_part( 'fragments/post-meta' ); ?>
		</header><!-- /.article__head -->

		<div class="article__body">
			<div class="article__entry">
				<?php the_content(); ?>
			</div><!-- /.article__entry -->
		</div><!-- /.article__body -->
	</article><!-- /.article -->

	<?php comments_template(); ?>

	<?php carbon_pagination( 'post', crb_get_pagination_options() ); ?>
<?php endwhile; ?>
