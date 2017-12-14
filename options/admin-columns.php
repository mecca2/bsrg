<?php

Carbon_Admin_Columns_Manager::modify_columns( 'post', array( 'post', 'crb_community' ) )
	->sort( array( 'cb', 'crb-thumbnail-column' ) )
	->add( array(
		Carbon_Admin_Column::create( 'Thumbnail' )
			->set_name( 'crb-thumbnail-column' )
			->set_callback( 'crb_column_render_post_thumbnail' )
			->set_width( 100 ),
	) );

Carbon_Admin_Columns_Manager::modify_columns( 'post', array( 'post', 'crb_slideshow' ) )
	->sort( array( 'cb', 'crb-shortcode-column' ) )
	->add( array(
		Carbon_Admin_Column::create( 'Shortcode' )
			->set_name( 'crb-shortcode-column' )
			->set_callback( 'crb_column_render_slideshow_shortcode' ),
	) );
