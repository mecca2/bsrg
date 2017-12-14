<?php

$socials = carbon_get_theme_option( 'crb_socials' );
if ( empty( $socials ) ) {
	return;
}

?>

<div class="socials">
	<ul>
		<?php foreach ( $socials as $social ): ?>
			<li>
				<a href="<?php echo esc_url( $social['link'] ); ?>" target="_blank">
					<i class="<?php echo esc_attr( $social['icon']['class'] ); ?>" aria-hidden="true"></i>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</div><!-- /.socials -->
