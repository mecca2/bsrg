<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">

	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<div class="wrapper">
		<header class="header">
			<div class="header__bar">
				<div class="shell">
					<?php
					$contacts = carbon_get_theme_option( 'crb_header_contacts' );
					if ( ! empty( $contacts ) ) : ?>
						<div class="header__contacts">
							<?php crb_render_fragment( 'contacts', compact( 'contacts' ) ); ?>
						</div><!-- /.header__contacts -->
					<?php endif; ?>

					<?php crb_render_fragment( 'socials' ); ?>
				</div><!-- /.shell -->
			</div><!-- /.header__bar -->

			<div class="header__inner">
				<div class="shell">
					<a href="<?php echo home_url( '/' ); ?>" class="logo"><?php bloginfo( 'name' ); ?></a>

					<?php if ( has_nav_menu( 'main-menu' ) ): ?>
						<a href="#" class="btn-menu">
							<span></span>
						</a>
					<?php endif; ?>

					<?php
					wp_nav_menu( array(
						'theme_location' => 'main-menu',
						'container' => 'nav',
						'container_class' => 'menu-main-menu-container nav',
						'fallback_cb' => '',
						'depth' => 2,
					) );
					?>
				</div><!-- /.shell -->
			</div><!-- /.header__inner -->
		</header><!-- /.header -->

		<div class="main">
