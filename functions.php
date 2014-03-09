<?php
// theme setup main function
add_action( 'after_setup_theme', '15m_theme_setup' );
function montera_theme_setup() {

	// theme global vars
	if (!defined('15M_BLOGNAME'))
	    define('15M_BLOGNAME', get_bloginfo('name'));

	if (!defined('15M_BLOGDESC'))
	    define('15M_BLOGDESC', get_bloginfo('description','display'));

	if (!defined('15M_BLOGURL'))
	    define('15M_BLOGURL', get_bloginfo('url'));

	if (!defined('15M_BLOGTHEME'))
	    define('15M_BLOGTHEME', get_bloginfo('template_directory'));

	/* Set up media options: sizes, featured images... */
	add_action( 'init', '15m_media_options' );

	/* Add your nav menus function to the 'init' action hook. */
	add_action( 'init', '15m_register_menus' );

	/* Load JavaScript files on the 'wp_enqueue_scripts' action hook. */
	add_action( 'wp_enqueue_scripts', '15m_load_scripts' );

	// Custom post types
	add_action( 'init', '15m_create_post_type', 0 );

	// Custom Taxonomies
	add_action( 'init', '15m_build_taxonomies', 0 );

	// Extra meta boxes in editor
	//add_filter( 'cmb_meta_boxes', '15m_metaboxes' );
	// Initialize the metabox class
	//add_action( 'init', '15m_init_metaboxes', 9999 );

	// excerpt support in pages
	add_post_type_support( 'page', 'excerpt' );

	// 15m shortcodes

} // end 15m theme setup function

// set up media options
function 15m_media_options() {
	/* Add theme support for post thumbnails (featured images). */
	add_theme_support( 'post-thumbnails', array( 'post','page','modulo','itinerario') );
	set_post_thumbnail_size( 231, 0 ); // default Post Thumbnail dimensions
	/* set up image sizes*/
	update_option('thumbnail_size_w', 231);
	update_option('thumbnail_size_h', 0);
	update_option('medium_size_w', 474);
	update_option('medium_size_h', 0);
	update_option('large_size_w', 717);
	update_option('large_size_h', 0);
} // end set up media options

// register custom menus
function 15m_register_menus() {
        if ( function_exists( 'register_nav_menus' ) ) {
                register_nav_menus(
                array(
                        'menu-sidebar' => 'Menú sidebar',
                        'menu-footer' => 'Menú footer',
                )
                );
        }
} // end register custom menus

// load js scripts to avoid conflicts
function 15m_load_scripts() {
	wp_enqueue_script('jquery');
//	wp_enqueue_script(
//		'bootstrap.min',
//		get_template_directory_uri() . '/bootstrap/js/bootstrap.min.js',
//		array( 'jquery' ),
//		'2.3.2',
//		FALSE
//	);

} // end load js scripts to avoid conflicts

// register post types
function 15m_create_post_type() {
	// Módulo post type
	register_post_type( 'modulo', array(
		'labels' => array(
			'name' => __( 'Módulos' ),
			'singular_name' => __( 'Módulo' ),
			'add_new_item' => __( 'Añadir módulo' ),
			'edit' => __( 'Editar' ),
			'edit_item' => __( 'Editar este módulo' ),
			'new_item' => __( 'Nuevo módulo' ),
			'view' => __( 'Ver módulo' ),
			'view_item' => __( 'Ver este módulo' ),
			'search_items' => __( 'Buscar módulos' ),
			'not_found' => __( 'Ningún módulo encontrado' ),
			'not_found_in_trash' => __( 'Ningún módulo en la papelera' ),
			'parent' => __( 'Superior' )
		),
		'has_archive' => true,
		'public' => true,
		'publicly_queryable' => true,
		'exclude_from_search' => false,
		'menu_position' => 5,
		//'menu_icon' => get_template_directory_uri() . '/images/icon-post.type-integrantes.png',
		'hierarchical' => false, // if true this post type will be as pages
		'query_var' => true,
		'supports' => array('title', 'editor','excerpt','author','comments','trackbacks' ),
		'rewrite' => array('slug'=>'modulo','with_front'=>false),
		'can_export' => true,
		'_builtin' => false,
	));

	// Itinerario post type
	register_post_type( 'itinerario', array(
		'labels' => array(
			'name' => __( 'Itinerarios' ),
			'singular_name' => __( 'Itinerario' ),
			'add_new_item' => __( 'Añadir itinerario' ),
			'edit' => __( 'Editar' ),
			'edit_item' => __( 'Editar este itinerario' ),
			'new_item' => __( 'Nuevo itinerario' ),
			'view' => __( 'Ver itinerario' ),
			'view_item' => __( 'Ver este itinerario' ),
			'search_items' => __( 'Buscar itinerarios' ),
			'not_found' => __( 'Ningún itinerario encontrado' ),
			'not_found_in_trash' => __( 'Ningún itinerario en la papelera' ),
			'parent' => __( 'Superior' )
		),
		'has_archive' => true,
		'public' => true,
		'publicly_queryable' => true,
		'exclude_from_search' => false,
		'menu_position' => 5,
		//'menu_icon' => get_template_directory_uri() . '/images/icon-post.type-integrantes.png',
		'hierarchical' => false, // if true this post type will be as pages
		'query_var' => true,
		'supports' => array('title', 'editor','excerpt','author','comments','trackbacks' ),
		'rewrite' => array('slug'=>'itinerario','with_front'=>false),
		'can_export' => true,
		'_builtin' => false,
	));

	// Actividad post type
	register_post_type( 'actividad', array(
		'labels' => array(
			'name' => __( 'Actividades' ),
			'singular_name' => __( 'Actividad' ),
			'add_new_item' => __( 'Añadir actividad' ),
			'edit' => __( 'Editar' ),
			'edit_item' => __( 'Editar este actividad' ),
			'new_item' => __( 'Nuevo actividad' ),
			'view' => __( 'Ver actividad' ),
			'view_item' => __( 'Ver este actividad' ),
			'search_items' => __( 'Buscar actividades' ),
			'not_found' => __( 'Ningún actividad encontrado' ),
			'not_found_in_trash' => __( 'Ningún actividad en la papelera' ),
			'parent' => __( 'Superior' )
		),
		'has_archive' => true,
		'public' => true,
		'publicly_queryable' => true,
		'exclude_from_search' => false,
		'menu_position' => 5,
		//'menu_icon' => get_template_directory_uri() . '/images/icon-post.type-integrantes.png',
		'hierarchical' => false, // if true this post type will be as pages
		'query_var' => true,
		'supports' => array('title', 'editor','excerpt','author','comments','trackbacks' ),
		'rewrite' => array('slug'=>'actividad','with_front'=>false),
		'can_export' => true,
		'_builtin' => false,
	));

} // end register post types

// register taxonomies
function 15m_build_taxonomies() {
//	register_taxonomy( 'type', 'project', array( // type taxonomy
//		'hierarchical' => true,
//		'label' => __( 'Type' ),
//		'name' => __( 'Types' ),
//		'query_var' => 'type',
//		'rewrite' => array( 'slug' => 'type', 'with_front' => false ),
//	) );
} // end register taxonomies

?>
