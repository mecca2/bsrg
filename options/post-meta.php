<?php

use Carbon_Fields\Container\Container;
use Carbon_Fields\Field\Field;
use Crb\Property_Options;
use Crb\Agent_Options;

/* ==========================================================================
	# Contact - Template
========================================================================== */

Container::make( 'post_meta', __( 'Contact Settings', 'crb' ) )
	->where( 'post_type', '=', 'page' )
	->where( 'post_template', '=', 'templates/contact.php' )
	->add_fields( array(
		Field::make( 'gravity_form', 'crb_contact_form', __( 'Form', 'crb' ) ),
		Field::make( 'complex', 'crb_contact_pins', __( 'Map Pins', 'crb' ) )
			->set_layout( 'tabbed-horizontal' )
			->add_fields( array(
				Field::make( 'map', 'map', __( 'Map', 'crb' ) )
					->set_required( true ),
			) ),
	) );

/* ==========================================================================
	# Communities - Template
========================================================================== */

Container::make( 'post_meta', __( 'Communities Settings', 'crb' ) )
	->where( 'post_type', '=', 'page' )
	->where( 'post_template', '=', 'templates/communities.php' )
	->add_fields( array(
		Field::make( 'text', 'crb_communities_count', __( 'Communities per page', 'crb' ) )
			->set_attribute( 'type', 'number' )
			->set_attribute( 'min', '1' )
			->set_attribute( 'max', '100' )
			->set_attribute( 'step', '1' )
			->set_attribute( 'pattern', '[0-9]*' )
			->set_default_value( '9' ),
	) );

/* ==========================================================================
	# Sidebar
========================================================================== */

Container::make( 'post_meta', __( 'Sidebar Settings', 'crb' ) )
	->set_context( 'side' )
	->where( 'post_type', '=', 'page' )
	->where( 'post_template', '=', 'templates/properties.php' )
	->add_fields( array(
		Field::make( 'sidebar', 'crb_custom_sidebar', __( 'Select Sidebar', 'crb' ) ),
	) );

/* ==========================================================================
	# Property - Post Type
========================================================================== */

$property_options = new Property_Options();

Container::make( 'post_meta', __( 'Property Settings', 'crb' ) )
	->where( 'post_type', '=', 'crb_property' )
	->add_fields( $property_options->get_fields() );

/* ==========================================================================
	# Agent - Post Type
========================================================================== */

$rets_agents_api_url = 'http://retsgw.flexmls.com/rets2_3/Search?Class=Agent&QueryType=DMQL2&Query=*&Count=1&StandardNames=0&RestrictedIndicator=****&Format=STANDARD-XML&Limit=10000';

Container::make( 'post_meta', __( 'Agent Settings', 'crb' ) )
	->where( 'post_type', '=', 'crb_agent' )
	->add_fields( array(
		Field::make( 'text', 'crb_agent_id', __( 'Agent ID', 'crb' ) )
			->help_text(
				sprintf(
					__( 'You can view all <a href="%1$s" %3$s>Active agents</a> and <a href="%2$s" %3$s>All Agents</a> from the API by clicking on their links.', 'crb' ),
					add_query_arg( array( 'SearchType' => 'ActiveAgent' ), $rets_agents_api_url ),
					add_query_arg( array( 'SearchType' => 'Agent' ), $rets_agents_api_url ),
					'target="_blank"'
				)
			),
		Field::make( 'text', 'crb_agent_position', __( 'Position', 'crb' ) ),
		Field::make( 'text', 'crb_agent_phone', __( 'Phone', 'crb' ) ),
		Field::make( 'text', 'crb_agent_email', __( 'Email', 'crb' ) ),
		Field::make( 'complex', 'crb_agent_specialties', __( 'Specialities', 'crb' ) )
			->set_layout( 'tabbed-horizontal' )
			->add_fields( array(
				Field::make( 'text', 'title', __( 'Title', 'crb' ) )
					->set_required( true ),
			) ),
		Field::make( 'complex', 'crb_agent_socials', __( 'Specialities', 'crb' ) )
			->set_layout( 'tabbed-horizontal' )
			->add_fields( array(
				Field::make( 'icon', 'icon', __( 'Icon', 'crb' ) )
					->set_width( 30 )
					->set_required( true ),
				Field::make( 'text', 'link', __( 'Link', 'crb' ) )
					->set_width( 70 )
					->set_required( true ),
			) ),
	) );

/* ==========================================================================
	# Community - Post Type
========================================================================== */

Container::make( 'post_meta', __( 'Intro', 'crb' ) )
	->where( 'post_type', '=', 'crb_community' )
	->set_context( 'carbon_fields_after_title' )
	->add_fields( array(
		Field::make( 'image', 'crb_community_intro_background', __( 'Intro Background', 'crb' ) )
			->set_width( 50 )
			->help_text(
				__( 'If this field is left blank, the Featured Image will be used instead.', 'crb' ) .
				'<br />' .
				crb_get_attachment_help( 'crb_community_intro_background' )
			),
		Field::make( 'text', 'crb_community_intro_video', __( 'Video', 'crb' ) )
			->set_width( 50 )
			->help_text( __( 'You can use any YouTube or Vimeo video URL, for example: https://www.youtube.com/watch?v=OCWj5xgu5Ng or http://vimeo.com/87110435', 'crb' ) ),
	) );

Container::make( 'post_meta', __( 'Community Sections', 'crb' ) )
	->where( 'post_type', '=', 'crb_community' )
	->add_tab( __( 'Features', 'crb' ), array(
		Field::make( 'complex', 'crb_community_features', __( 'Features', 'crb' ) )
			->set_layout( 'tabbed-vertical' )
			->add_fields( array(
				Field::make( 'image', 'icon', __( 'Icon', 'crb' ) )
					->set_width( 25 ),
				Field::make( 'text', 'title', __( 'Title', 'crb' ) )
					->set_width( 25 ),
				Field::make( 'textarea', 'content', __( 'Content', 'crb' ) )
					->set_width( 50 ),
			) ),
	) )
	->add_tab( __( 'Callout Slider', 'crb' ), crb_get_fields_callout_slider( 'crb_callout_' ) )
	->add_tab( __( 'Slider', 'crb' ), array(
		Field::make( 'complex', 'crb_community_slider', __( 'Slider', 'crb' ) )
			->set_layout( 'tabbed-vertical' )
			->add_fields( array(
				Field::make( 'image', 'image', __( 'Image', 'crb' ) )
					->set_required( true )
					->help_text( crb_get_attachment_help( 'crb_community_slider' ) ),
			) ),
	) )
	->add_tab( __( 'Content', 'crb' ), array(
		Field::make( 'rich_text', 'crb_community_content', __( 'Content', 'crb' ) )
			->set_width( 70 ),
		Field::make( 'image', 'crb_community_image', __( 'Image', 'crb' ) )
			->set_width( 30 )
			->help_text( crb_get_attachment_help( 'crb_community_image' ) ),
	) );

/* ==========================================================================
	# Testimonial - Post Type
========================================================================== */

Container::make( 'post_meta', __( 'Testimonial Settings', 'crb' ) )
	->where( 'post_type', '=', 'crb_testimonial' )
	->add_fields( array(
		Field::make( 'text', 'crb_testimonial_position', __( 'Position', 'crb' ) ),
	) );

/* ==========================================================================
	# Slideshow - Post Type
========================================================================== */

$fields_methods = array(
	'set_conditional_logic' => array(
		array(
			'field' => 'crb_slideshow_type',
			'value' => 'filtered',
			'compare' => '=',
		),
	),
);

$filters = new \Crb\Properties_Filters();
$slideshow_fields = $filters->get_fields( 'crb_slideshow_filtered_', $fields_methods );

Container::make( 'post_meta', __( 'Slideshow Settings', 'crb' ) )
	->where( 'post_type', '=', 'crb_slideshow' )
	->add_fields( array_merge(
		array(
			Field::make( 'select', 'crb_slideshow_type', __( 'Type', 'crb' ) )
				->set_options( array(
					'featured' => __( 'Featured', 'crb' ),
					'filtered' => __( 'Filtered', 'crb' ),
				) ),
			// Shortcode
			Field::make( 'html', 'crb_slideshow_shortcode_featured', __( 'Shortcode', 'crb' ) )
				->set_html( 'crb_column_render_slideshow_shortcode_featured' )
				->set_conditional_logic( array(
					array(
						'field' => 'crb_slideshow_type',
						'value' => 'featured',
						'compare' => '=',
					)
				) ),
			Field::make( 'html', 'crb_slideshow_shortcode_filtered', __( 'Shortcode', 'crb' ) )
				->set_html( 'crb_column_render_slideshow_shortcode_filtered' )
				->set_conditional_logic( array(
					array(
						'field' => 'crb_slideshow_type',
						'value' => 'filtered',
						'compare' => '=',
					)
				) ),

			// Featured Fields
			Field::make( 'association', 'crb_slideshow_featured_properties', __( 'Select Featured Properties', 'crb' ) )
				->help_text( __( 'You can use this field to display specific properties in specific order. If this field is left blank, recent properties will be shown.', 'crb' ) )
				->set_types( array(
					array(
						'type'      => 'post',
						'post_type' => 'crb_property',
					),
				) )
				->set_conditional_logic( array(
					array(
						'field' => 'crb_slideshow_type',
						'value' => 'featured',
						'compare' => '=',
					)
				) ),
		),

		// Filtered Fields
		$slideshow_fields
	) );
