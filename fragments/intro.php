<?php

if ( is_front_page() ) {
	return;
} elseif ( is_singular( 'page' ) ) {
	get_template_part( 'fragments/intro-image' );
	return;
}

?>

<header class="main__head">
	<div class="shell">
		<h5>
			<?php crb_the_title(); ?>
		</h5>

		<?php
		Carbon_Breadcrumb_Trail::output( array(
			'glue'              => ' &gt; ',
			'link_before'       => '<li>',
			'link_after'        => '</li>',
			'wrapper_before'    => '<nav class="nav-breadcrumbs"><ul>',
			'wrapper_after'     => '</ul></nav>',
			'title_before'      => '',
			'title_after'       => '',
			'min_items'         => 2,
			'last_item_link'    => true,
			'display_home_item' => true,
			'home_item_title'   => __( 'Home', 'crb' ),
		) );
		?>
	</div><!-- /.shell -->
</header><!-- /.main__head -->
