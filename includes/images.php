<?php

function crb_get_image_thumbnail2( $attachment_id, $width, $height, $crop=true ) {
	$width = absint( $width );
	$height = absint( $height );

	$upload_dir = wp_upload_dir();
	$attachment = wp_get_attachment_metadata( $attachment_id );
	$attachment_path = get_attached_file( $attachment_id );
	$attachment_realpath = crb_normalize_path( $attachment_path );
	if ( ! $attachment || ! $attachment_path || ! file_exists( $attachment_realpath ) ) {
		return '';
	}

	// Replace everithing after the last "/"
	$attachment_subdirectory = 'cache/' . preg_replace( '/\/?[^\/]+\z/', '', $attachment['file'] );

	$filename = basename( $attachment_realpath );

	$crop_name = '';
	if ( is_array( $crop ) ) {
		$crop_name = '-' . implode( '-', $crop );
	} elseif ( $crop ) {
		$crop_name = '-cropped';
	}

	// Match the ".extension" and prepend the width, height, and cropping status
	$filename = preg_replace( '/(\.[^\.]+)$/', '-' . $width . 'x' . $height . $crop_name . '$1', $filename );

	$filepath = crb_normalize_path( crb_normalize_path( $upload_dir['basedir'] ) . DIRECTORY_SEPARATOR . $attachment_subdirectory ) . DIRECTORY_SEPARATOR . $filename;
	$fileurl = trailingslashit( $upload_dir['baseurl'] ) . $attachment_subdirectory . '/' . $filename;

	if ( !file_exists( $filepath ) ) {
		$editor = wp_get_image_editor( $attachment_realpath );
		if ( is_wp_error( $editor ) ) {
			return '';
		}
		$editor->resize( $width, $height, $crop );
		$editor->save( $filepath );
	}

	return $fileurl;
}

// Returns image help text from image size name or image dimensions
function crb_get_attachment_help( $args ) {
	$args = crb_sanitize_image_size_args( $args );

	if ( $args['crop'] ) {
		return sprintf( __( 'Recommended image size: %s x %s px. If the image is larger, it will be scaled down and cropped to match exactly both the recommended width and height.', 'crb' ), $args['width'], $args['height'] );
	} else {
		return sprintf( __( 'Largest image size: %s x %s px. If the image is larger, it will be scaled down proportionately to fit within either the recommended width or height.', 'crb' ), $args['width'], $args['height'] );
	}
}

// Wrapper for wpthumb, in case plugin is missing
// Supports image size as 2nd attr or width, height and crop as 2nd, 3rd, 4th
function crb_wpthumb( $image_id, $args ) {
	if ( ! function_exists( 'crb_get_image_thumbnail2' ) ) {
		return wp_get_attachment_image_url( $image_id );
	}

	$args = crb_sanitize_image_size_args( $args );

	return crb_get_image_thumbnail2( $image_id, $args['width'], $args['height'], $args['crop'] );
}

function crb_img_shortcode( $image_id, $image_full_url, $args ) {
	$args_attrs = array( 'class', 'alt', 'style' );
	$attrs = array();

	foreach ( $args_attrs as $att ) {
		if ( isset( $args[$att] ) ) {
			$attrs[$att] = $args[$att];
			unset( $args[$att] );
		}
	}

	$image_full_path = crb_convert_url_to_path( $image_full_url );

	// Gif fix
	if ( is_string( $image_full_url ) && in_array( substr( $image_full_url, -4 ), array( '.gif' ) ) ) {
		// Skip source modification
		$image = $image_full_url;

	// SVG direct output
	} elseif ( is_string( $image_full_url ) && in_array( substr( $image_full_url, -4 ), array( '.svg' ) ) ) {
		// Skip source modification
		if ( file_exists( $image_full_path ) ) {
			return file_get_contents( $image_full_path );
		} else {
			return;
		}

	// Croppable image
	} else {
		if ( file_exists( $image_full_path ) ) {
			$image_cropped_url = crb_wp_get_attachment_image_src( $image_id, $args );

			// Cropped file not found, since the image was not successfully cropped
			if ( ! file_exists( crb_convert_url_to_path( $image_cropped_url ) ) ) {
				$image_cropped_url = $image_full_url;
			}
		} else {
			$image_cropped_url = $image_full_url;
		}
	}

	// Check file exists
	list( $width, $height ) = array( 0, 0 );
	$image_cropped_path = crb_convert_url_to_path( $image_cropped_url );
	if ( file_exists( $image_cropped_path ) ) {
		try {
			list( $width, $height ) = getimagesize( $image_cropped_path );
		} catch ( \Exception $e ) {
			// Do nothing
		}
	}

	// Retina
	if ( !empty( $args['retina'] ) && $width && $height ) {
		$retina_del = intval( $args['retina'] );
		$image_ratio = $width / $height;

		if ( $args['crop'] == true && $width < $args['width'] && $height < $args['height'] ) {
			$width = $args['width'] / $image_ratio;
			$height = $args['height'] / $image_ratio;
		} else {
			$width = $width / $retina_del;
			$height = $height / $retina_del;
		}
	}

	// Lazyloading
	if ( !empty( $args['lazy'] ) ) {
		$attrs['data-original'] = $image_cropped_url;
		$image_cropped_url = get_bloginfo( 'stylesheet_directory' ) . '/images/Transparent.png';
	}

	if ( !empty( $width ) ) {
		$attrs['width'] = $width;
	}
	if ( !empty( $height ) ) {
		$attrs['height'] = $height;
	}

	$attr = '';
	foreach ( $attrs as $a => $value ) {
		$attr .= ' ' . $a . '="' . esc_attr( trim( $value ) ) . '"';
	}

	return '<img src="' . $image_cropped_url . '" ' . $attr . ' />';
}

// Fix potential issues with url fopen configuation
function crb_convert_url_to_path( $url ) {
	$home_url = home_url( '/' );
	$home_path = ABSPATH;

	$path = str_replace( $home_url, $home_path, $url );

	return $path;
}

// Parsing Args width, height and crop, or extracting them from image size.
function crb_sanitize_image_size_args( $args ) {
	if ( ! is_array( $args ) ) {
		global $_wp_additional_image_sizes;
		if ( ! empty( $_wp_additional_image_sizes[ $args ] ) ) {
			$current_size = $_wp_additional_image_sizes[ $args ];
		} else {
			$current_size = crb_get_unregistered_image_size( $args );
		}

		if ( ! empty( $current_size ) ) {
			$args = $current_size;
		}
	}

	return wp_parse_args( $args, array(
		'width' => 0,
		'height' => 0,
		'crop' => false,
	) );
}

// Extraction Image With Width and Height From Image ID and Image size, Supports additional atts
function crb_wp_get_attachment_image( $image_id, $args, $additional_atts = array( ) ) {
	if ( empty( $image_id ) || ! is_numeric( $image_id ) ) {
		return;
	}

	// Crop pre image, available when using: add_theme_support( 'wpthumb-crop-from-position' );
	$current_position = get_post_meta( $image_id, 'wpthumb_crop_pos', true );

	if ( ! empty( $current_position ) ) {
		$current_position = explode( ',', $current_position );
		if ( is_array( $crop ) && count( $crop ) == 2 ) {
			// Add 'crop_from_position' to the default args
			$additional_atts = wp_parse_args( $additional_atts, array(
				'crop' => $current_position,
			) );
		}
	}

	// Add 'class' to the default args
	$additional_atts = wp_parse_args( $additional_atts, array(
		'class' => '',
	) );
	// Add Image Size name as class
	$additional_atts['class'] .= is_string( $args ) ? ' ' . $args : '';

	// Add Image Alt from the attachment field
	$alt_text = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
	$image_metadata = get_post_meta( $image_id, '_wp_attachment_metadata', true );
	$post_title = get_the_title();

	// Prepare Title attr
	if ( ! empty( $image_metadata['image_meta']['title'] ) ) {
		$title_text = $image_metadata['image_meta']['title'];
	} elseif ( ! empty( $alt_text ) ) {
		$title_text = $alt_text;
	} else {
		$title_text = $post_title;
	}

	// Prepare Alt attr
	if ( ! empty( $alt_text ) ) {
		// No change, alt is OK
	} elseif ( ! empty( $title_text ) ) {
		$alt_text = $title_text;
	} else {
		$alt_text = $post_title;
	}

	if ( ! empty( $alt_text ) ) {
		$additional_atts['alt'] = $alt_text;
	}
	if ( ! empty( $title_text ) ) {
		$additional_atts['title'] = $title_text;
	}

	$image_full_url = crb_wp_get_attachment_image_src( $image_id, 'full' );

	// Check image has been deleted
	if ( empty( $image_full_url ) ) {
		return;
	}

	$args = crb_sanitize_image_size_args( $args );

	// Add default image classes, defined with the custom image size
	$additional_atts['class'] .= ! empty( $args['class'] ) ? ' ' . $args['class'] : '';

	// Prepare the $args array for crb_img_shortcode
	$args = array_merge( $args, $additional_atts );

	return @crb_img_shortcode( $image_id, $image_full_url, $args );
}

// Verify that the image size is registered and generated by WP
function crb_is_image_size_registered( $image_size ) {
	global $_wp_additional_image_sizes;

	// Not a string with image size name
	if ( ! is_string( $image_size ) ) {
		return false;
	}

	// A default WP image size
	if ( in_array( $image_size, array( 'full', 'large', 'medium', 'thumbnail', 'thumb', 'post-thumbnail' ) ) ) {
		return true;
	}

	// Custom image size defined with add_image_size
	if ( empty( $_wp_additional_image_sizes[ $image_size ] ) ) {
		return false;
	}

	return true;
}

// Extraction Image Src From Image ID and Image size
function crb_wp_get_attachment_image_src( $image_id, $args ) {
	if ( empty( $image_id ) ) {
		return;
	}

	$image_src = crb_wpthumb( $image_id, $args );

	return $image_src;
}

// Featured Image
function crb_get_the_post_thumbnail( $args, $additional_atts = array( ) ) {
	return crb_wp_get_attachment_image( get_post_thumbnail_id( ), $args, $additional_atts );
}
function crb_get_the_post_thumbnail_src( $args ) {
	return crb_wp_get_attachment_image_src( get_post_thumbnail_id( ), $args );
}

// Returns thumbnail from ID, Callback for admin columns
function crb_column_render_post_thumbnail( $post_id ) {
	if ( has_post_thumbnail( $post_id ) ) {
		$thumbnail = crb_wp_get_attachment_image( get_post_thumbnail_id( $post_id ), 'crb_admin_column', array( 'style' => 'vertical-align: middle;' ) );
		$thumbnail = sprintf( '<span class="featured-image-holder">%s</span>', $thumbnail );
	} else {
		$thumbnail = '';
	}

	return $thumbnail;
}

// Using custom image sizes, that are not registered in wordpress for automatic cropping
// This is for Preformance reasons, and cleaner FTP
function crb_get_unregistered_image_size( $name ) {
	$unregistered_image_sizes = array(
		'crb_agent_business_card' => array(
			'width' => 90,
			'height' => 90,
			'crop' => true,
			'class' => 'alignleft',
		),
		'crb_admin_column' => array(
			'width' => 100,
			'height' => 100,
			'crop' => false,
		),
		'crb_community_feature' => array(
			'width' => 128,
			'height' => 120,
			'crop' => false,
			'retina' => 2,
		),
		'crb_section_services_icon' => array(
			'width' => 160,
			'height' => 160,
			'crop' => false,
		),
		'crb_section_posts' => array(
			'width' => 270,
			'height' => 222,
			'crop' => false,
		),
		'crb_section_agents_loop' => array(
			'width' => 270,
			'height' => 300,
			'crop' => true,
		),
		'crb_agent_featured' => array(
			'width' => 270,
			'height' => 265,
			'crop' => false,
			'class' => 'alignleft',
		),
		'crb_callout_slider' => array(
			'width' => 370,
			'height' => 256,
			'crop' => false,
		),
		'crb_section_communities' => array(
			'width' => 370,
			'height' => 462,
			'crop' => false,
		),
		'crb_communities_listing' => array(
			'width' => 370,
			'height' => 462,
			'crop' => false,
		),
		'crb_community_image' => array(
			'width' => 537,
			'height' => 467,
			'crop' => false,
			'class' => 'alignright',
		),
		'crb_community_slider' => array(
			'width' => 954,
			'height' => 531,
			'crop' => false,
		),
		'crb_section_testimonials_background' => array(
			'width' => 1920,
			'height' => 538,
			'crop' => false,
		),
		'crb_section_subscribe_background' => array(
			'width' => 1920,
			'height' => 538,
			'crop' => false,
		),
		'crb_community_intro_background' => array(
			'width' => 1920,
			'height' => 1027,
			'crop' => false,
		),
		'crb_section_properties_search' => array(
			'width' => 1920,
			'height' => 1062,
			'crop' => false,
		),
		'crb_section_callout_background' => array(
			'width' => 1920,
			'height' => 1268,
			'crop' => false,
		),
	);

	if ( empty( $unregistered_image_sizes[$name] ) ) {
		// On callign with unexisting image size, will return 'full'.
		// This is safe, because it is meant to be used within other functions leading to crb_sanitize_image_size_args( )
		return 'full';
	}

	return $unregistered_image_sizes[$name];
}
