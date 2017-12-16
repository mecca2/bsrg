<?php
get_header('single-slideshow');
the_post();
?>


<?php 

// apply filter from slideshow / copied from shortcode logic 
$filters = new \Crb\Properties_Filters();
$parameters = $filters->get_slider_parameters( get_the_id(), 'crb_slideshow_filtered_' );
$filters->set_params( $parameters );

$args_obj = new \Crb\Properties_Query_Args();

$args_obj->set_filters( $filters );
$args = $args_obj->get();

$properties_query = new WP_Query( $args );

$args_obj->deregister_hooks();

ob_start();
// fragment for display of full width slider 
crb_render_fragment( 'fragments/property/slideshow-full-width', array( 'properties_query' => $properties_query ) );
$html = ob_get_clean();
echo $html; 

?>

<?php get_footer('single-slideshow'); ?>
