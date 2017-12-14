<?php

use Carbon_Fields\Container\Container;
use Carbon_Fields\Field\Field;

/* ==========================================================================
	# Sections - Template
========================================================================== */

Container::make( 'post_meta', __( 'Sections Settings', 'crb' ) )
	->where( 'post_type', '=', 'page' )
	->where( 'post_template', '=', 'templates/sections.php' )
	->add_fields( array(
		Field::make( 'complex', 'crb_sections', __( 'Sections', 'crb' ) )
			->set_layout( 'tabbed-vertical' )
			->setup_labels( array(
				'singular_name' => __( 'Section', 'crb' ),
				'plural_name' => __( 'Sections', 'crb' ),
			) )

			/* ==========================================================================
				# Properties Search - Section
			========================================================================== */

			->add_fields( 'properties_search', __( 'Properties Search', 'crb' ), array(
				Field::make( 'select', 'type', __( 'Type', 'crb' ) )
					->set_width( 10 )
					->set_options( array(
						'background' => __( 'Background' , 'crb' ),
						'video'      => __( 'Video'      , 'crb' ),
						'slide_show'      => __( 'Slide Show'      , 'crb' ),
					) ),
				Field::make( 'image', 'background', __( 'Background Image', 'crb' ) )
					->set_width( 45 )
					->help_text( crb_get_attachment_help( 'crb_section_properties_search' ) )
					->set_conditional_logic( array(
						array(
							'field' => 'type',
							'value' => 'background',
						)
					) ),
				Field::make( 'text', 'video', __( 'Video', 'crb' ) )
					->set_width( 45 )
					->help_text( __( 'You can use any YouTube or Vimeo video URL, for example: https://www.youtube.com/watch?v=OCWj5xgu5Ng or http://vimeo.com/87110435', 'crb' ) )
					->set_conditional_logic( array(
						array(
							'field' => 'type',
							'value' => 'video',
						)
					) ),
				Field::make( 'media_gallery', 'slide_show', __( 'Slide Show', 'crb' ) )
					->set_width( 45 )
					->help_text( crb_get_attachment_help( 'crb_section_properties_search' ) )
					->set_conditional_logic( array(
						array(
							'field' => 'type',
							'value' => 'slide_show',
						)
					) ),
				Field::make( 'text', 'title', __( 'Title', 'crb' ) )
					->set_width( 100 ),
			) )

			/* ==========================================================================
				# Content With Video - Section
			========================================================================== */

			->add_fields( 'content_with_video', __( 'Content With Video or Photo', 'crb' ), array(
				Field::make( 'text', 'title', __( 'Title', 'crb' ) ),
				Field::make( 'rich_text', 'content', __( 'Content', 'crb' ) ),
				Field::make( 'select', 'type', __( 'Media Type', 'crb' ) )
					->set_width( 10 )
					->set_options( array(
						'video'      => __( 'Video'      , 'crb' ),
						'photo'      => __( 'Photo'      , 'crb' ),
					) ),
				Field::make( 'text', 'video_link', __( 'Video Link', 'crb' ) )
					->help_text( __( 'You can use any YouTube or Vimeo video URL, for example: https://www.youtube.com/watch?v=OCWj5xgu5Ng or http://vimeo.com/87110435', 'crb' ) )
					->set_conditional_logic( array(
						array(
							'field' => 'type',
							'value' => 'video',
						)
					) ),
				Field::make( 'text', 'photo_link', __( 'Photo Link', 'crb' ) )
					->help_text( __( 'You can use any photo', 'crb' ) )
					->set_conditional_logic( array(
						array(
							'field' => 'type',
							'value' => 'photo',
						)
					) ),
				Field::make( 'select', 'orientation', __( 'Media Orientation', 'crb' ) )
					->set_width( 10 )
					->set_options( array(
						'right'      => __( 'Right'      , 'crb' ),
						'left'      => __( 'Left'      , 'crb' ),
					) )
					->help_text( __( 'This will orient the photo/video to the left or right of Title and Content above', 'crb' ) ),
			) )

			/* ==========================================================================
				# Latest Properties - Section
			========================================================================== */

			->add_fields( 'latest_properties', __( 'Latest Properties', 'crb' ), array_merge(
				array(
					Field::make( 'text', 'title', __( 'Title', 'crb' ) ),
					Field::make( 'text', 'count', __( 'Count', 'crb' ) )
						->set_attribute( 'type', 'number' )
						->set_attribute( 'min', '1' )
						->set_attribute( 'max', '100' )
						->set_attribute( 'step', '1' )
						->set_attribute( 'pattern', '[0-9]*' )
						->set_default_value( '6' ),
					Field::make( 'association', 'featured', __( 'Select Featured Properties', 'crb' ) )
						->set_types( array(
							array(
								'type'      => 'post',
								'post_type' => 'crb_property',
							),
						) )
						->help_text( __( 'You can use this field to display featured properties. If this field is left blank, recent properties will be shown instead.', 'crb' ) ),
				),
				crb_get_button_fields( 'button_', __( 'Button', 'crb' ) )
			) )

			/* ==========================================================================
				# Subscribe - Section
			========================================================================== */

			->add_fields( 'subscribe', __( 'Subscribe', 'crb' ), array(
				Field::make( 'image', 'background', __( 'Background', 'crb' ) )
					->help_text( crb_get_attachment_help( 'crb_section_subscribe_background' ) ),
				Field::make( 'gravity_form', 'form', __( 'Select Form', 'crb' ) )
					->set_required( true ),
			) )

			/* ==========================================================================
				# Communities - Section
			========================================================================== */

			->add_fields( 'communities', __( 'Communities', 'crb' ), array(
				Field::make( 'text', 'title', __( 'Title', 'crb' ) ),
				Field::make( 'text', 'count', __( 'Count', 'crb' ) )
					->set_attribute( 'type', 'number' )
					->set_attribute( 'min', '3' )
					->set_attribute( 'max', '100' )
					->set_attribute( 'step', '1' )
					->set_attribute( 'pattern', '[0-9]*' )
					->set_default_value( '6' ),
				Field::make( 'association', 'featured', __( 'Select Featured Commuinties', 'crb' ) )
					->help_text( __( 'You can use this field to display specific communities in specific order.', 'crb' ) )
					->set_types( array(
						array(
							'type'      => 'post',
							'post_type' => 'crb_community',
						),
					) ),
			) )

			/* ==========================================================================
				# Latest Posts - Section
			========================================================================== */

			->add_fields( 'latest_posts', __( 'Latest Posts', 'crb' ), array(
				Field::make( 'text', 'title', __( 'Title', 'crb' ) ),
				Field::make( 'text', 'count', __( 'Count', 'crb' ) )
					->set_attribute( 'type', 'number' )
					->set_attribute( 'min', '4' )
					->set_attribute( 'max', '100' )
					->set_attribute( 'step', '4' )
					->set_attribute( 'pattern', '[0-9]*' )
					->set_default_value( '4' ),
				Field::make( 'association', 'featured', __( 'Select Featured Posts', 'crb' ) )
					->help_text( __( 'You can use this field to display specific posts in specific order.', 'crb' ) )
					->set_types( array(
						array(
							'type'      => 'post',
							'post_type' => 'post',
						),
					) ),
			) )

			/* ==========================================================================
				# Accordions With Aside Video - Section
			========================================================================== */

			->add_fields( 'accordions_with_aside_video', __( 'Accordions With Aside Video', 'crb' ), array(
				Field::make( 'separator', 'accordions_separator', __( 'Accordions', 'crb' ) ),
				Field::make( 'text', 'accordions_title', __( 'Title', 'crb' ) ),
				Field::make( 'complex', 'accordion_entries', __( 'Accordions', 'crb' ) )
					->set_layout( 'tabbed-vertical' )
					->setup_labels( array(
						'singular_name' => __( 'Accordion', 'crb' ),
						'plural_name' => __( 'Accordions', 'crb' ),
					) )
					->add_fields( '', __( 'Accordion', 'crb' ), array(
						Field::make( 'text', 'title', __( 'Title', 'crb' ) )
							->set_required( true ),
						Field::make( 'rich_text', 'content', __( 'Content', 'crb' ) )
							->set_required( true ),
					) )
					->set_header_template( '<%- title %>' ),
				Field::make( 'separator', 'video_separator', __( 'Video', 'crb' ) ),
				Field::make( 'text', 'video_title', __( 'Title', 'crb' ) ),
				Field::make( 'text', 'video_link', __( 'Video Link', 'crb' ) )
					->help_text( __( 'You can use any YouTube or Vimeo video URL, for example: https://www.youtube.com/watch?v=OCWj5xgu5Ng or http://vimeo.com/87110435', 'crb' ) ),
			) )

			/* ==========================================================================
				# Agents - Section
			========================================================================== */

			->add_fields( 'agents', __( 'Agents', 'crb' ), array(
				Field::make( 'text', 'title', __( 'Title', 'crb' ) ),
				Field::make( 'text', 'count', __( 'Count', 'crb' ) )
					->set_attribute( 'type', 'number' )
					->set_attribute( 'min', '4' )
					->set_attribute( 'max', '100' )
					->set_attribute( 'step', '4' )
					->set_attribute( 'pattern', '[0-9]*' )
					->set_default_value( '4' ),
				Field::make( 'association', 'featured', __( 'Select Featured Agents', 'crb' ) )
					->set_types( array(
						array(
							'type'      => 'post',
							'post_type' => 'crb_agent',
						),
					) )
					->help_text( __( 'You can use this field to setup featured entries. If this field is left blank, the latest entries will be shown instead.', 'crb' ) ),
			) )

			/* ==========================================================================
				# Testimonials - Section
			========================================================================== */

			->add_fields( 'testimonials', __( 'Testimonials', 'crb' ), array(
				Field::make( 'text', 'title', __( 'Title', 'crb' ) )
					->set_width( 70 ),
				Field::make( 'image', 'background', __( 'Background', 'crb' ) )
					->set_width( 30 )
					->help_text( crb_get_attachment_help( 'crb_section_testimonials_background' ) ),
				Field::make( 'text', 'count', __( 'Count', 'crb' ) )
					->set_attribute( 'type', 'number' )
					->set_attribute( 'min', '1' )
					->set_attribute( 'max', '100' )
					->set_attribute( 'step', '1' )
					->set_attribute( 'pattern', '[0-9]*' )
					->set_default_value( '3' ),
				Field::make( 'association', 'featured', __( 'Select Featured Agents', 'crb' ) )
					->set_types( array(
						array(
							'type'      => 'post',
							'post_type' => 'crb_testimonial',
						),
					) )
					->help_text( __( 'You can use this field to setup featured entries. If this field is left blank, the latest entries will be shown instead.', 'crb' ) ),
			) )

			/* ==========================================================================
				# Callout - Section
			========================================================================== */

			->add_fields( 'callout', __( 'Callout', 'crb' ), array(
				Field::make( 'image', 'background', __( 'Background', 'crb' ) )
					->set_required( true )
					->set_width( 35 )
					->help_text( crb_get_attachment_help( 'crb_section_callout_background' ) ),
				Field::make( 'text', 'title', __( 'Title', 'crb' ) )
					->set_width( 65 ),
				Field::make( 'rich_text', 'content', __( 'Content', 'crb' ) ),
				Field::make( 'text', 'button_title', __( 'Button Title', 'crb' ) )
					->set_width( 35 ),
				Field::make( 'text', 'button_link', __( 'Button Link', 'crb' ) )
					->set_width( 35 ),
				Field::make( 'checkbox', 'button_target', __( 'Open button link in new window', 'crb' ) )
					->set_width( 30 ),
			) )

			/* ==========================================================================
				# Callout Slider - Section
			========================================================================== */

			->add_fields( 'callout_slider', __( 'Callout Slider', 'crb' ), crb_get_fields_callout_slider() )

			/* ==========================================================================
				# Services - Section
			========================================================================== */

			->add_fields( 'services', __( 'Services', 'crb' ), array(
				Field::make( 'complex', 'services', __( 'Services', 'crb' ) )
					->set_layout( 'tabbed-horizontal' )
					->add_fields( array_merge(
						array(
							Field::make( 'image', 'image', __( 'Image', 'crb' ) )
								->set_width( 30 ),
							Field::make( 'text', 'title', __( 'Title', 'crb' ) )
								->set_width( 70 ),
							Field::make( 'rich_text', 'content', __( 'Content', 'crb' ) ),
						),
						crb_get_button_fields( 'button_' )
					) ),
			) ),
	) );
