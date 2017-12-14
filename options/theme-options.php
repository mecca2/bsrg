<?php

use Carbon_Fields\Container\Container;
use Carbon_Fields\Field\Field;
use Crb\Rets_Options;

Container::make( 'theme_options', __( 'Theme Options', 'crb' ) )
	->set_page_file( 'crbn-theme-options.php' )
	->add_fields( array(
		Field::make( 'text', 'crb_google_maps_api_key', __( 'Google Maps API Key', 'crb' ) ),
		Field::make( 'header_scripts', 'crb_header_script', __( 'Header Script', 'crb' ) ),
		Field::make( 'footer_scripts', 'crb_footer_script', __( 'Footer Script', 'crb' ) ),
	) );

Container::make( 'theme_options', __( 'Rets', 'crb' ) )
	->set_page_parent( 'crbn-theme-options.php' )
	->add_fields( Rets_Options::get_fields() );

Container::make( 'theme_options', __( 'Header', 'crb' ) )
	->set_page_parent( 'crbn-theme-options.php' )
	->add_fields( crb_get_contacts_fields( 'crb_header_' ) );

Container::make( 'theme_options', __( 'Sections', 'crb' ) )
	->set_page_parent( 'crbn-theme-options.php' )
	->add_tab( __( 'Callout', 'crb' ), array_merge(
		array(
			Field::make( 'icon', 'crb_callout_icon', __( 'Icon', 'crb' ) )
				->set_width( 20 ),
			Field::make( 'text', 'crb_callout_title', __( 'Title', 'crb' ) )
				->set_width( 40 ),
			Field::make( 'text', 'crb_callout_subtitle', __( 'Subtitle', 'crb' ) )
				->set_width( 40 ),
		),
		crb_get_button_fields( 'crb_callout_button_', __( 'Button', 'crb' ) )
	) );

Container::make( 'theme_options', __( 'Sidebars', 'crb' ) )
	->set_page_parent( 'crbn-theme-options.php' )
	->add_fields( array(
		Field::make( 'sidebar', 'crb_agent_sidebar', __( 'Single Agent Sidebar', 'crb' ) ),
		Field::make( 'sidebar', 'crb_property_sidebar', __( 'Single Property Sidebar', 'crb' ) ),
	) );

Container::make( 'theme_options', __( 'Socials', 'crb' ) )
	->set_page_parent( 'crbn-theme-options.php' )
	->add_fields( array(
		Field::make( 'complex', 'crb_socials', __( 'Socials', 'crb' ) )
			->add_fields( array(
				Field::make( 'icon', 'icon', __( 'Icon', 'crb' ) )
					->set_required( true )
					->set_width( 30 ),
				Field::make( 'text', 'link', __( 'Link', 'crb' ) )
					->set_required( true )
					->set_width( 70 ),
			) )
	) );

Container::make( 'theme_options', __( 'Contacts', 'crb' ) )
	->set_page_parent( 'crbn-theme-options.php' )
	->add_fields( array(
		Field::make( 'textarea', 'crb_contact_address', __( 'Address', 'crb' ) )
			->set_rows( 2 ),
		Field::make( 'text', 'crb_contact_email', __( 'Email', 'crb' ) ),
		Field::make( 'text', 'crb_contact_phone', __( 'Phone', 'crb' ) ),
	) );
