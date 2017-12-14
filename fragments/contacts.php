<?php

if ( empty( $contacts ) ) {
	return;
}

?>

<ul>
	<?php
	foreach ( $contacts as $contact ):
		if ( $contact['type'] == 'custom' ) {
			$title = nl2br( $contact['title'] );
			$link = $contact['link'];
			$target = $contact['target'];
			$class = $contact['icon']['class'];
		} elseif ( $contact['type'] == 'phone' ) {
			$phone = carbon_get_theme_option( 'crb_contact_phone' );
			if ( empty( $phone ) ) {
				continue;
			}

			$title = nl2br( $phone );
			$link = 'tel:' . $phone;
			$target = 'blank';
			$class = 'fa fa-phone';
		} elseif ( $contact['type'] == 'email' ) {
			$email = carbon_get_theme_option( 'crb_contact_email' );
			if ( empty( $email ) ) {
				continue;
			}

			$title = antispambot( $email );
			$link = 'mailto:' . antispambot( $email );
			$target = 'blank';
			$class = 'fa fa-envelope-o';
		} elseif ( $contact['type'] == 'map' ) {
			$address = carbon_get_theme_option( 'crb_contact_address' );
			if ( empty( $address ) ) {
				continue;
			}

			$title = nl2br( $address );
			$link = 'https://www.google.com/maps/search/?api=1&query=' . urlencode( $address );
			$target = 'blank';
			$class = 'fa fa-map-marker';
		}
		?>

		<li>
			<?php if ( ! empty( $link ) ): ?>
				<a href="<?php echo esc_url( $link ); ?>" <?php crb_the_target( $target ); ?>>
			<?php endif; ?>

				<i class="<?php echo esc_attr( $class ); ?>" aria-hidden="true"></i>

				<?php echo apply_filters( 'the_title', $title ); ?>

			<?php if ( ! empty( $link ) ): ?>
				</a>
			<?php endif; ?>
		</li>
	<?php endforeach; ?>
</ul>
