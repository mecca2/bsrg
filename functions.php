<?php
define( 'CRB_THEME_DIR', dirname( __FILE__ ) . DIRECTORY_SEPARATOR );

# Enqueue JS and CSS assets on the front-end
add_action( 'wp_enqueue_scripts', 'crb_wp_enqueue_scripts' );
function crb_wp_enqueue_scripts() {
	$template_dir = get_template_directory_uri();

	# Enqueue jQuery
	wp_enqueue_script( 'jquery' );
	\Carbon_Fields\Field\Map_Field::admin_enqueue_scripts();

	if ( is_page_template( array( 'templates/properties.php', 'templates/sections.php' ) ) ) {
		wp_enqueue_script( 'jquery-ui' );
		wp_enqueue_script( 'jquery-ui-widget' );
		wp_enqueue_script( 'jquery-ui-position' );
		wp_enqueue_script( 'jquery-ui-menu' );
		wp_enqueue_script( 'jquery-ui-autocomplete' );
	}

	# Enqueue Custom JS files
	# @crb_enqueue_script attributes -- id, location, dependencies, in_footer = false
	crb_enqueue_script( 'theme-mfp-js', $template_dir . '/js/jquery.magnific-popup.min.js' );
	crb_enqueue_script( 'theme-owl-carousel-js', $template_dir . '/js/owl.carousel.min.js' );
	crb_enqueue_script( 'theme-map-functions', $template_dir . '/js/map-functions.js', array( 'jquery' ) );
	crb_enqueue_script( 'theme-selectric', $template_dir . '/js/jquery.selectric.min.js', array( 'jquery' ) );
	crb_enqueue_script( 'theme-functions', $template_dir . '/js/functions.js', array( 'jquery', 'jquery-ui-autocomplete' ) );

	wp_localize_script( 'theme-functions', 'ajax_object', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
	) );

	# Enqueue Custom CSS files
	# @crb_enqueue_style attributes -- id, location, dependencies, media = all
	crb_enqueue_style( 'theme-google-fonts', 'https://fonts.googleapis.com/css?family=Open+Sans:400,600,700|Poppins:400,500,600' );
	crb_enqueue_style( 'theme-custom-styles', $template_dir . '/assets/bundle.css' );
	crb_enqueue_style( 'theme-styles', $template_dir . '/style.css' );

	# Enqueue Comments JS file
	if ( is_singular() ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

# Enqueue JS and CSS assets on admin pages
add_action( 'admin_enqueue_scripts', 'crb_admin_enqueue_scripts' );
function crb_admin_enqueue_scripts() {
	$template_dir = get_template_directory_uri();

	# Enqueue Scripts
	# @crb_enqueue_script attributes -- id, location, dependencies, in_footer = false
	# crb_enqueue_script( 'theme-admin-functions', $template_dir . '/js/admin-functions.js', array( 'jquery' ) );

	# Enqueue Styles
	# @crb_enqueue_style attributes -- id, location, dependencies, media = all
	crb_enqueue_style( 'theme-admin-styles', $template_dir . '/assets/admin-style.css' );
}

# Attach Custom Post Types and Custom Taxonomies
add_action( 'init', 'crb_attach_post_types_and_taxonomies', 0 );
function crb_attach_post_types_and_taxonomies() {
	# Attach Custom Post Types
	include_once( CRB_THEME_DIR . 'options/post-types.php' );

	# Attach Custom Taxonomies
	include_once( CRB_THEME_DIR . 'options/taxonomies.php' );

	// Remove main WYSIWYG Editor from templates that doesnt use it.
	if (
		is_admin() &&
		! empty( $_GET ) &&
		! empty( $_GET['post'] ) &&
		in_array(
			get_page_template_slug( $_GET['post'] ),
			array(
				'templates/sections.php',
			)
		)
	) {
		remove_post_type_support( 'page', 'editor' );
	}
}

add_action( 'after_setup_theme', 'crb_setup_theme' );

# To override theme setup process in a child theme, add your own crb_setup_theme() to your child theme's
# functions.php file.
if ( ! function_exists( 'crb_setup_theme' ) ) {
	function crb_setup_theme() {
		# Make this theme available for translation.
		load_theme_textdomain( 'crb', get_template_directory() . '/languages' );

		# Autoload dependencies
		$autoload_dir = CRB_THEME_DIR . 'vendor/autoload.php';
		if ( ! is_readable( $autoload_dir ) ) {
			wp_die( __( 'Please, run <code>composer install</code> to download and install the theme dependencies.', 'crb' ) );
		}
		include_once( $autoload_dir );
		\Carbon_Fields\Carbon_Fields::boot();

		// Fix Broken Rich Texts with Shortcodes in them
		remove_filter( 'crb_content', 'do_shortcode',                      9 );
		remove_filter( 'crb_content', 'wptexturize'                          );
		remove_filter( 'crb_content', 'wpautop'                              );
		remove_filter( 'crb_content', 'shortcode_unautop'                    );
		remove_filter( 'crb_content', 'prepend_attachment'                   );
		remove_filter( 'crb_content', 'wp_make_content_images_responsive'    );
		remove_filter( 'crb_content', 'convert_smilies',                  20 );
		remove_filter( 'crb_content', 'crb_shortcode_empty_paragraph_fix' );

		add_filter( 'crb_content', 'wptexturize'                       );
		add_filter( 'crb_content', 'convert_smilies',               20 );
		add_filter( 'crb_content', 'wpautop'                           );
		add_filter( 'crb_content', 'shortcode_unautop'                 );
		add_filter( 'crb_content', 'prepend_attachment'                );
		add_filter( 'crb_content', 'wp_make_content_images_responsive' );
		add_filter( 'crb_content', 'do_shortcode', 11 );
		add_filter( 'crb_content', 'crb_shortcode_empty_paragraph_fix' );

		# Additional libraries and includes
		include_once( CRB_THEME_DIR . 'includes/comments.php' );
		include_once( CRB_THEME_DIR . 'includes/title.php' );
		include_once( CRB_THEME_DIR . 'includes/images.php' );
		include_once( CRB_THEME_DIR . 'includes/helpers.php' );
		include_once( CRB_THEME_DIR . 'includes/fields-callbacks.php' );
		include_once( CRB_THEME_DIR . 'includes/gravity-forms.php' );
		include_once( CRB_THEME_DIR . 'includes/ajax-functions.php' );
		include_once( CRB_THEME_DIR . 'includes/mls/mls.php' );
		include_once( CRB_THEME_DIR . 'includes/mls/cron.php' );
		include_once( CRB_THEME_DIR . 'includes/breadcrumb-functions.php' );

		if ( is_admin() ) {
			new \Crb\Admin_Rets_Page();
			new \Crb\Admin_Rets_Ajax();
		}

		# Theme supports
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'menus' );
		add_theme_support( 'html5', array( 'gallery' ) );

		# Manually select Post Formats to be supported - http://codex.wordpress.org/Post_Formats
		// add_theme_support( 'post-formats', array( 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat' ) );

		# Register Theme Menu Locations
		register_nav_menus( array(
			'main-menu' => __( 'Main Menu', 'crb' ),
		) );

		# Attach custom widgets
		include_once( CRB_THEME_DIR . 'options/widgets.php' );

		# Attach custom shortcodes
		include_once( CRB_THEME_DIR . 'options/shortcodes.php' );

		# Add Actions
		add_action( 'widgets_init', 'crb_widgets_init' );
		add_action( 'carbon_fields_register_fields', 'crb_attach_theme_options' );

		# Add Filters
		add_filter( 'excerpt_more', 'crb_excerpt_more' );
		add_filter( 'excerpt_length', 'crb_excerpt_length', 999 );
		add_filter( 'carbon_fields_map_field_api_key', 'crb_get_google_maps_api_key' );
	}
}

# Register Sidebars
# Note: In a child theme with custom crb_setup_theme() this function is not hooked to widgets_init
function crb_widgets_init() {
	$sidebar_options = array_merge( crb_get_default_sidebar_options(), array(
		'name' => __( 'Default Sidebar', 'crb' ),
		'id'   => 'default-sidebar',
	) );

	register_sidebar( $sidebar_options );

	$sidebar_options = array_merge( crb_get_default_sidebar_options(), array(
		'name' => __( 'Widgetized Footer', 'crb' ),
		'id'   => 'widgetized-footer',
		'before_title'  => '<h6 class="widget__title">',
		'after_title'   => '</h6>',
	) );

	register_sidebar( $sidebar_options );
}

# Sidebar Options
function crb_get_default_sidebar_options() {
	return array(
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget'  => '</li>',
		'before_title'  => '<h6 class="widget__title">',
		'after_title'   => '</h6>',
	);
}

function crb_attach_theme_options() {
	# Attach fields
	include_once( CRB_THEME_DIR . 'options/theme-options.php' );
	include_once( CRB_THEME_DIR . 'options/post-meta.php' );
	include_once( CRB_THEME_DIR . 'options/sections-meta.php' );
	include_once( CRB_THEME_DIR . 'options/admin-columns.php' );
}

function crb_excerpt_more() {
	return '...';
}

function crb_excerpt_length() {
	return 55;
}

/**
 * Returns the Google Maps API Key set in Theme Options.
 *
 * @return string
 */
function crb_get_google_maps_api_key() {
	return carbon_get_theme_option( 'crb_google_maps_api_key' );
}
