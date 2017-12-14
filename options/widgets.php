<?php

use Carbon_Fields\Widget\Widget;
use Carbon_Fields\Field\Field;

/**
 * Register the new widget classes here so that they show up in the widget list.
 */
function crb_register_widgets() {
	register_widget( 'CrbThemeWidgetRichText' );
	register_widget( 'CrbThemeWidgetContacts' );
	register_widget( 'CrbThemeWidgetPropertiesSearch' );
	register_widget( 'CrbThemeWidgetRecentProperties' );
	register_widget( 'CrbThemeWidgetMenuWithLargeButtons' );
}
add_action( 'widgets_init', 'crb_register_widgets' );

/**
 * Displays a block with a title and WYSIWYG rich text content
 */
class CrbThemeWidgetRichText extends Widget {
	function __construct() {
		$this->setup(
			'rich_text',
			__( 'Rich Text', 'crb' ),
			__( 'Displays a block with title and WYSIWYG content.', 'crb' ),
			array(
				Field::make( 'text', 'title', __( 'Title', 'crb' ) ),
				Field::make( 'rich_text', 'content', __( 'Content', 'crb' ) ),
			)
		);
	}

	function front_end( $args, $instance ) {
		if ( $instance['title'] ) {
			$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

			echo $args['before_title'] . $title . $args['after_title'];
		}

		echo apply_filters( 'the_content', $instance['content'] );
	}
}

/**
 * Displays a block with a title and WYSIWYG rich text content
 */
class CrbThemeWidgetContacts extends Widget {
	function __construct() {
		$this->setup(
			'contacts',
			__( 'Contacts', 'crb' ),
			__( 'Displays a block with title and contacts.', 'crb' ),
			array_merge(
				array(
					Field::make( 'text', 'title', __( 'Title', 'crb' ) ),
				),
				crb_get_contacts_fields()
			),
			'widget--contact'
		);
	}

	function front_end( $args, $instance ) {
		if ( $instance['title'] ) {
			$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

			echo $args['before_title'] . $title . $args['after_title'];
		}

		if ( empty( $instance['contacts'] ) ) {
			return;
		}

		$contacts = $instance['contacts'];
		crb_render_fragment( 'contacts', compact( 'contacts' ) );
	}
}

/**
 * Displays a block with a title and Properties Search
 */
class CrbThemeWidgetPropertiesSearch extends Widget {
	function __construct() {
		$this->setup(
			'properties_search',
			__( 'Properties Search', 'crb' ),
			__( 'Displays a block with a title and Properties Search.', 'crb' ),
			array(
				Field::make( 'text', 'title', __( 'Title', 'crb' ) )
					->set_default_value( __( 'Advanced Search', 'crb' ) ),
			),
			'widget--filter'
		);
	}

	function front_end( $args, $instance ) {
		if ( $instance['title'] ) {
			$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

			echo $args['before_title'] . $title . $args['after_title'];
		}

		get_template_part( 'fragments/property/filters-form' );
	}
}

/**
 * Displays a block with a title and recent properties
 */
class CrbThemeWidgetRecentProperties extends Widget {
	function __construct() {
		$this->setup(
			'recent_properties',
			__( 'Recent Properties', 'crb' ),
			__( 'Displays a block with a title and recent properties.', 'crb' ),
			array(
				Field::make( 'text', 'title', __( 'Title', 'crb' ) )
					->set_default_value( __( 'Recent For Sale', 'crb' ) ),
				Field::make( 'text', 'count', __( 'Count', 'crb' ) )
					->set_attribute( 'type', 'number' )
					->set_attribute( 'min', '1' )
					->set_attribute( 'max', '100' )
					->set_attribute( 'step', '1' )
					->set_attribute( 'pattern', '[0-9]*' )
					->set_default_value( '4' ),
			),
			'widget_recent_properties'
		);
	}

	function front_end( $args, $instance ) {
		if ( $instance['title'] ) {
			$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

			echo $args['before_title'] . $title . $args['after_title'];
		}

		$count = 4;
		if ( ! empty( $instance['count'] ) ) {
			$count = absint( $instance['count'] );
		}

		crb_render_fragment( 'fragments/property/recent-for-sale', array( 'count' => $count ) );
	}
}

/**
 * Displays a block with a title and menu with large buttons
 */
class CrbThemeWidgetMenuWithLargeButtons extends Widget {
	function __construct() {
		$this->setup(
			'menu_with_large_buttons',
			__( 'Menu With Large Buttons', 'crb' ),
			__( 'Displays a block with a title and menu with large buttons.', 'crb' ),
			array(
				Field::make( 'text', 'title', __( 'Title', 'crb' ) ),
				Field::make( 'select', 'menu', __( 'Select Menu', 'crb' ) )
					->set_required( true )
					->set_options( 'crb_get_menu_options' ),
			)
		);
	}

	function front_end( $args, $instance ) {
		if ( $instance['title'] ) {
			$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

			echo $args['before_title'] . $title . $args['after_title'];
		}

		wp_nav_menu( array(
			'menu' => $instance['menu'],
			'container' => 'nav',
			'fallback_cb' => '',
			'depth' => 1,
		) );
	}
}
