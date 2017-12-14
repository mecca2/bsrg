<?php get_header(); ?>
<?php the_post(); ?>

<?php get_template_part( 'fragments/intro' ); ?>

<section class="section-main">
	<div class="shell">
		<div class="content content--left">
			<div class="profile">
				<div class="profile__head">
					<?php if ( has_post_thumbnail() ): ?>
						<?php echo crb_wp_get_attachment_image( get_post_thumbnail_id(), 'crb_agent_featured' ); ?>
					<?php endif; ?>

					<h4><?php the_title(); ?></h4>

					<?php
					$specialties = carbon_get_the_post_meta( 'crb_agent_specialties' );
					if ( ! empty( $specialties ) ) :
						$specialties = wp_list_pluck( $specialties, 'title' );
						?>
						<p>
							<?php _e( 'Specialties:', 'crb' ); ?>
							<?php echo implode( ', ', $specialties ); ?>
						</p>
					<?php endif; ?>

					<?php
					$phone = carbon_get_the_post_meta( 'crb_agent_phone' );
					if ( ! empty( $phone ) ) : ?>
						<a href="<?php echo esc_url( 'tel:' . $phone ); ?>">
							<i class="fa fa-phone"></i>
							<?php echo apply_filters( 'the_title', $phone ); ?>
						</a>
					<?php endif; ?>

					<?php
					$email = carbon_get_the_post_meta( 'crb_agent_email' );
					if ( ! empty( $email ) ) : ?>
						<a href="<?php echo esc_url( 'mailto:' . antispambot( $email ) ); ?>">
							<i class="fa fa-envelope-o"></i>
							<?php echo antispambot( $email ); ?>
						</a>
					<?php endif; ?>
				</div><!-- /.profile__head -->

				<div class="profile__body">
					<h5><?php _e( 'About', 'crb' ); ?></h5>

					<?php the_content(); ?>

					<?php
					$socials = carbon_get_the_post_meta( 'crb_agent_socials' );
					if ( ! empty( $socials ) ) : ?>
						<div class="socials">
							<ul>
								<?php foreach ( $socials as $social ): ?>
									<li>
										<a href="<?php echo esc_url( $social['link'] ); ?>" target="_blank">
											<i class="<?php echo esc_attr( $social['icon']['class'] ); ?>"></i>
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
						</div><!-- /.socials -->
					<?php endif; ?>

					<?php get_template_part( 'fragments/agent-properties' ); ?>
				</div><!-- /.profile__body -->
			</div><!-- /.profile -->
		</div><!-- /.content content-/-left -->

		<?php get_sidebar(); ?>
	</div><!-- /.shell -->
</section><!-- /.section-main -->

<?php get_footer(); ?>
