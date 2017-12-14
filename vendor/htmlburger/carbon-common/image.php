<?php
function crb_get_image_thumbnail( $attachment_id, $width, $height, $crop=true ) {
	$width = absint( $width );
	$height = absint( $height );

	$upload_dir = wp_upload_dir();
	$attachment = wp_get_attachment_metadata( $attachment_id );
	$attachment_path = get_attached_file( $attachment_id );
	$attachment_realpath = crb_normalize_path( $attachment_path );
	if ( ! $attachment || ! $attachment_path || ! file_exists( $attachment_realpath ) ) {
		return '';
	}
	$attachment_subdirectory = preg_replace( '/\/?[^\/]+\z/', '', $attachment['file'] );

	$filename = basename( $attachment_realpath );
	$filename = preg_replace( '/(\.[^\.]+)$/', '-' . $width . 'x' . $height . ( $crop ? '-cropped' : '' ) . '$1', $filename);
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