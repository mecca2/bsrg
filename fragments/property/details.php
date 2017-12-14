<?php

if ( empty( $details ) ) {
	return;
}

?>

<div class="list-details">
	<ul>
		<?php if ( ! empty( $details['bedrooms'] ) ): ?>
			<li>
				<i class="ico-bed"></i>
				<p><?php printf( __( '%s bd', 'crb' ), absint( $details['bedrooms'] ) ); ?></p>
			</li>
		<?php endif; ?>

		<?php if ( ! empty( $details['bathrooms'] ) ): ?>
			<li>
				<i class="ico-bath"></i>
				<p><?php printf( __( '%s ba', 'crb' ), absint( $details['bathrooms'] ) ); ?></p>
			</li>
		<?php endif; ?>

		<?php if ( ! empty( $details['sqft'] ) ): ?>
			<li>
				<p><?php printf( __( '%s sqft', 'crb' ), number_format( $details['sqft'] ) ); ?></p>
			</li>
		<?php endif; ?>
	</ul>
</div>
