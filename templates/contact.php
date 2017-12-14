<?php
/* Template Name: Contact */
get_header();
the_post();
?>

<?php get_template_part( 'fragments/intro' ); ?>

<section class="section-main">
	<div class="shell">
		<div class="cols">
			<div class="col col--1of2">
				<div class="post">
					<div class="post__entry">
						<?php the_content(); ?>
					</div><!-- /.post__entry -->
				</div><!-- /.post -->

				<?php
				$form = carbon_get_the_post_meta( 'crb_contact_form' );
				if ( ! empty( $form ) ): ?>
					<div class="form-contact">
						<?php crb_render_gform( $form, true ); ?>
					</div><!-- /.form-contact -->
				<?php endif; ?>
			</div><!-- /.col col-/-1of2 -->

			<div class="col col--1of2">
				<div class="section__map">
					<?php
					$pins = carbon_get_the_post_meta( 'crb_contact_pins' );
					if ( ! empty( $pins ) ) :
						$pins = array_map( function( $pin ) {
							$new_pin = array(
								'lat'   => $pin['map']['lat'],
								'lng'   => $pin['map']['lng'],
								'title' => $pin['map']['address'],
							);

							return $new_pin;
						}, $pins );
						?>
						<div
							id="map-<?php the_id(); ?>"
							data-pins="<?php echo urlencode( json_encode( $pins ) ); ?>"
							class="google-map google-map-<?php the_id(); ?>"
						></div>
					<?php endif; ?>

					<div class="section__map-contacts">
						<?php
						$email = carbon_get_theme_option( 'crb_contact_email' );
						if ( ! empty( $email ) ): ?>
							<a href="<?php echo esc_url( 'mailto:' . antispambot( $email ) ); ?>" target="_blank">
								<i class="fa fa-envelope-o"></i>

								<?php echo antispambot( $email ); ?>
							</a>
						<?php endif; ?>

						<?php
						$phone = carbon_get_theme_option( 'crb_contact_phone' );
						if ( ! empty( $phone ) ): ?>
							<a href="<?php echo esc_url( 'tel:' . $phone ); ?>">
								<i class="fa fa-phone"></i>

								<?php echo apply_filters( 'the_title', $phone ); ?>
							</a>
						<?php endif; ?>
					</div><!-- /.section__map-contacts -->
				</div><!-- /.section__map -->
			</div><!-- /.col col-/-1of2 -->
		</div><!-- /.cols -->
	</div><!-- /.shell -->
</section><!-- /.section-main -->

<?php get_footer(); ?>
