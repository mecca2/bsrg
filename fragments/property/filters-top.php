<?php

try {
	$filters = new \Crb\Properties_Filters();
} catch ( Exception $e ) {
	echo '<pre>';
	print_r( $e->getError() );
	echo '</pre>';
	return;
}

?>

<nav class="nav-filter">
	<ul>
		<?php foreach ( $filters->get_options( 'order' ) as $slug => $name ): ?>
			<li class="<?php echo $filters->get_current( $slug, 'order', 'current' ); ?>">
				<a href="<?php echo esc_url( $filters->get_link( $slug, 'order' ) ); ?>"><?php echo $name; ?></a>
			</li>
		<?php endforeach; ?>

		<?php /*
		<li class="menu-item-has-children">
			<a href="#">
				More Filters

				<i class="fa fa-angle-down" aria-hidden="true"></i>
			</a>

			<ul>
				<li>
					<a href="#">Item 1</a>
				</li>

				<li>
					<a href="#">Item 2</a>
				</li>

				<li>
					<a href="#">Item 3</a>
				</li>
			</ul>
		</li>
		*/ ?>
	</ul>

	<p><?php printf( __( '%s homes found', 'crb' ), $properties_query->found_posts ); ?></p>
</nav><!-- /.nav-filter -->
