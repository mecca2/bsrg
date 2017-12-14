<?php
/**
 * Functions, related to the Gravity Forms plugin
 */

// Add a field type class to each field
add_filter( 'gform_field_css_class', 'crb_custom_gforms_classes', 10, 3 );
function crb_custom_gforms_classes( $classes, $field, $form ) {
	$classes .= ' gfield--' . $field['type'] . ' gfield--' . $field['size'];

	return $classes;
}

// Get all available gravity forms
function crb_get_forms() {
	$forms_ids = array();

	if ( class_exists( 'RGFormsModel' ) ) {
		$forms = RGFormsModel::get_forms( null, 'title' );
		foreach ( $forms as $form ) {
			$forms_ids[ $form->id ] = $form->title;
		}
	}

	// crb_gravity_form_options filter - allows you to modify the form list
	// you can use it to add a "No Form" option
	$form_ids = apply_filters( 'crb_gravity_form_options', $forms_ids );

	return $forms_ids;
}

// Render a gravity form. This is just a shortcut for `gravity_form()` function without
// the crazy arguments list and all available options.
function crb_render_gform( $id, $is_ajax = false, $args = array() ) {
	if ( ! function_exists( 'gravity_form' ) || empty( $id ) ) {
		return;
	}

	// using a shared auto-increment tabindex
	static $gform_tabindex;
	if ( empty( $gform_tabindex ) ) {
		$gform_tabindex = 1;
	}

	// use generated tabindex only if no tabindex is specified
	// step to increment the starting tabindex of the next form
	$step = apply_filters( 'carbon_gform_tabindex_step', 500 );

	// Tabindex backward compatibility
	if ( is_numeric( $args ) ) {
		$args = array(
			'tabindex' => $args,
		);
	}

	$tabindex = $gform_tabindex;
	$gform_tabindex += $step;

	$args = wp_parse_args( $args, array(
		'display_title' => false,
		'display_description' => false,
		'display_inactive' => false,
		'field_values' => null,
		'tabindex' => $tabindex,
	) );

	// render the form
	gravity_form(
		$id,
		$args['display_title'], // display_title
		$args['display_description'], // display_description
		$args['display_inactive'], // display_inactive
		$args['field_values'], // field_values
		$is_ajax,
		$args['tabindex']
	);
}

// Disable the confirmation anchor
// http://www.gravityhelp.com/documentation/page/Gform_confirmation_anchor
add_filter( 'gform_confirmation_anchor', '__return_false' );

// Display an "Add Form" button above rich text fields on all custom field containers
// http://www.gravityhelp.com/documentation/page/Gform_display_add_form_button
add_filter( 'gform_display_add_form_button', '__return_true' );

/*
// Replace the ajax spinner image
add_filter( 'gform_ajax_spinner_url', 'crb_gform_ajax_spinner_url', 10, 2 );
function crb_gform_ajax_spinner_url( $image_src, $form ) {
	return get_bloginfo( 'stylesheet_directory' ) . '/images/spinner-sample.gif';
}
*/

// gform_field_value_
add_filter( 'gform_field_value_crb_agent_email', 'crb_callback_field_agent_email' );
function crb_callback_field_agent_email( $value ) {
	if ( is_singular( 'crb_agent' ) ) {
		$value = carbon_get_the_post_meta( 'crb_agent_email' );
	}

	return $value;
}

// Populate Dynamic Form Title for Agents Contact Form
add_filter( 'gform_pre_render', 'crb_modify_form_title', 10, 3 );
function crb_modify_form_title( $form, $ajax, $field_values ) {
	// Not on Single Agent
	if ( ! is_singular( 'crb_agent' ) && ! is_singular( 'crb_property' ) ) {
		return $form;
	}

	// Not containing any formatting or containing too many
	$matches_count = substr_count( $form['title'], '%s' );
	if ( $matches_count != 1 ) {
		return $form;
	}

	if ( is_singular( 'crb_agent' ) ) {
		$form['title'] = sprintf( $form['title'], get_the_title() );
	} elseif ( is_singular( 'crb_property' ) ) {
		$form['title'] = sprintf( $form['title'], __( 'Agent', 'crb' ) );
	}

	if ( is_singular( 'crb_property' ) ) {
		foreach ( $form['fields'] as $field_index => $field ) {
			if ( $field['type'] != 'html' ) {
				continue;
			}

			$classes = $field['cssClass'];
			$field['cssClass'] .= ' gfield-hidden';

			$property = new \Crb\Property( get_the_id() );

			$agent_id = $property->get_agent_id();
			if ( empty( $agent_id ) ) {
				break;
			}

			ob_start();
			crb_render_fragment( 'fragments/widget-agent-business-card', array( 'agent_id' => $agent_id ) );
			$field['content'] = ob_get_clean();

			$field['cssClass'] = $classes;

			$form['fields'][$field_index] = $field;

			break;
		}
	}

	return $form;
}
