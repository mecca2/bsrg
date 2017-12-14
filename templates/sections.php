<?php
/* Template Name: Sections */
get_header();

get_template_part( 'fragments/intro' );

$sections = carbon_get_the_post_meta( 'crb_sections' );
if ( ! empty( $sections ) ) {
	foreach ( $sections as $section ) {
		$type = str_replace( '_', '-', $section['_type'] );
		crb_render_fragment( 'sections/' . $type, compact( 'section' ) );
	}
}

get_footer();
