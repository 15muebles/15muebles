<?php
// theme setup main function
add_action( 'after_setup_theme', 'quincem_theme_setup' );
function quincem_theme_setup() {

	// theme global vars
	if (!defined('QUINCEM_BLOGNAME'))
	    define('QUINCEM_BLOGNAME', get_bloginfo('name'));

	if (!defined('QUINCEM_BLOGDESC'))
	    define('QUINCEM_BLOGDESC', get_bloginfo('description','display'));

	if (!defined('QUINCEM_BLOGURL'))
	    define('QUINCEM_BLOGURL', get_bloginfo('url'));

	if (!defined('QUINCEM_BLOGTHEME'))
	    define('QUINCEM_BLOGTHEME', get_bloginfo('template_directory'));

	/* Set up media options: sizes, featured images... */
	add_action( 'init', 'quincem_media_options' );

	/* Add your nav menus function to the 'init' action hook. */
	add_action( 'init', 'quincem_register_menus' );

	/* Load JavaScript files on the 'wp_enqueue_scripts' action hook. */
	add_action( 'wp_enqueue_scripts', 'quincem_load_scripts' );

	// Custom post types
	add_action( 'init', 'quincem_create_post_type', 0 );

	// Custom Taxonomies
	add_action( 'init', 'quincem_build_taxonomies', 0 );

	// Extra meta boxes in editor
	add_filter( 'cmb_meta_boxes', 'quincem_metaboxes' );
	// Initialize the metabox class
	add_action( 'init', 'quincem_init_metaboxes', 9999 );

	// excerpt support in pages
	add_post_type_support( 'page', 'excerpt' );

	// remove unused items from dashboard
	add_action( 'admin_menu', 'quincem_remove_dashboard_item' );

} // end quincem theme setup function

// remove item from wordpress dashboard
function quincem_remove_dashboard_item() {
	remove_menu_page('edit.php');	
}

// set up media options
function quincem_media_options() {
	/* Add theme support for post thumbnails (featured images). */
	add_theme_support( 'post-thumbnails', array( 'page','badge','itinerario','actividad') );
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
function quincem_register_menus() {
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
function quincem_load_scripts() {
	wp_enqueue_style( 'bootstrap-css', get_template_directory_uri() . '/bootstrap/css/bootstrap.min.css' );
	wp_enqueue_style( 'bootstrap-theme-css', get_template_directory_uri() . '/bootstrap/css/bootstrap-theme.min.css' );
	wp_enqueue_style( 'fontsquirrel-css', get_template_directory_uri() . '/fonts/stylesheet.css' );
	wp_enqueue_script('jquery');
	wp_enqueue_script(
		'bootstrap-js',
		get_template_directory_uri() . '/bootstrap/js/bootstrap.min.js',
		array( 'jquery' ),
		'3.1.1',
		FALSE
	);
	if ( is_home() ) {
	wp_enqueue_script(
		'smooth-scroll-js',
		get_template_directory_uri() . '/bootstrap/js/smooth.scroll.js',
		array( 'bootstrap-js' ),
		'0.1',
		FALSE
	);
	}


} // end load js scripts to avoid conflicts

// register post types
function quincem_create_post_type() {
	// Módulo post type
	register_post_type( 'badge', array(
		'labels' => array(
			'name' => __( 'Badges' ),
			'singular_name' => __( 'Badge' ),
			'add_new_item' => __( 'Añadir badge' ),
			'edit' => __( 'Editar' ),
			'edit_item' => __( 'Editar este badge' ),
			'new_item' => __( 'Nuevo badge' ),
			'view' => __( 'Ver badge' ),
			'view_item' => __( 'Ver este badge' ),
			'search_items' => __( 'Buscar badges' ),
			'not_found' => __( 'Ningún badge encontrado' ),
			'not_found_in_trash' => __( 'Ningún badge en la papelera' ),
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
		'supports' => array('title', 'editor','excerpt','author','comments','trackbacks','thumbnail'),
		'rewrite' => array('slug'=>'badge','with_front'=>false),
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
		'supports' => array('title', 'editor','excerpt','author','comments','trackbacks','thumbnail' ),
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
function quincem_build_taxonomies() {
//	register_taxonomy( 'type', 'project', array( // type taxonomy
//		'hierarchical' => true,
//		'label' => __( 'Type' ),
//		'name' => __( 'Types' ),
//		'query_var' => 'type',
//		'rewrite' => array( 'slug' => 'type', 'with_front' => false ),
//	) );
} // end register taxonomies

// get all posts from a post type to be used in select or multicheck forms
function quincem_get_list($post_type) {
	$posts = get_posts(array(
		'posts_per_page' => -1,
		'post_type' => $post_type,
	));
	foreach ( $posts as $post ) {
		$list[$post->ID] = $post->post_title;
	}
	return $list;
}

//Add metaboxes to several post types edit screen
function quincem_metaboxes( $meta_boxes ) {
	$prefix = '_quincem_'; // Prefix for all fields

	// get data for select and multicheck fields
	//$itinerarios = quincem_get_list("itinerario");
	$actividades = quincem_get_list("actividad");
	$badges = quincem_get_list("badge");

	// CUSTOM FIELDS FOR ITINERARIOS
	///

	// modulos multicheckbox
	$meta_boxes[] = array(
		'id' => 'quincem_modulos',
		'title' => 'Módulos en el itinerario',
		'pages' => array('itinerario'), // post type
		'context' => 'side', //  'normal', 'advanced', or 'side'
		'priority' => 'high',  //  'high', 'core', 'default' or 'low'
		'show_names' => false, // Show field names on the left
		'fields' => array(
				array(
					'name' => 'Badges',
					'id' => $prefix . 'badges',
					'type' => 'multicheck',
					'options' => $badges
				),
		),
	);

	// CUSTOM FIELDS FOR BADGES
	///

	// actividades multicheckbox
	$meta_boxes[] = array(
		'id' => 'quincem_actividades',
		'title' => 'Actividades',
		'pages' => array('badge'), // post type
		'context' => 'side', //  'normal', 'advanced', or 'side'
		'priority' => 'high',  //  'high', 'core', 'default' or 'low'
		'show_names' => false, // Show field names on the left
		'fields' => array(
				array(
					'name' => 'Actividades',
					'id' => $prefix . 'actividades',
					'type' => 'multicheck',
					'options' => $actividades
				),
		),
	);

	// modulos multicheckbox
	$meta_boxes[] = array(
		'id' => 'quincem_dependencias',
		'title' => 'Dependencias (otros badges)',
		'pages' => array('badge'), // post type
		'context' => 'side', //  'normal', 'advanced', or 'side'
		'priority' => 'high',  //  'high', 'core', 'default' or 'low'
		'show_names' => false, // Show field names on the left
		'fields' => array(
				array(
					'name' => 'Dependencias',
					'id' => $prefix . 'dependencias',
					'type' => 'multicheck',
					'options' => $badges
				),
		),
	);

	// Cómo ganar el badge
	$meta_boxes[] = array(
		'id' => 'quincem_badge_como',
		'title' => 'Cómo ganar el badge',
		'pages' => array('badge'), // post type
		'context' => 'normal', //  'normal', 'advanced', or 'side'
		'priority' => 'high',  //  'high', 'core', 'default' or 'low'
		'show_names' => false, // Show field names on the left
		'fields' => array(
				array(
					'name' => 'Cómo ganar el badge',
					'id' => $prefix . 'badge_como',
					'type' => 'wysiwyg',
					'options' => array(),
				),
		),
	);

	// Material de trabajo
	$meta_boxes[] = array(
		'id' => 'quincem_material',
		'title' => 'Material de trabajo',
		'pages' => array('badge'), // post type
		'context' => 'normal', //  'normal', 'advanced', or 'side'
		'priority' => 'high',  //  'high', 'core', 'default' or 'low'
		'show_names' => false, // Show field names on the left
		'fields' => array(
				array(
					'name' => 'Material de trabajo',
					'id' => $prefix . 'material',
					'type' => 'wysiwyg',
					'options' => array(),
				),
		),
	);

	// CUSTOM FIELDS FOR ACTIVIDADES
	///

	// On/Off line for actividades
	$meta_boxes[] = array(
		'id' => 'quincem_onoffline',
		'title' => 'Tipo de actividades',
		'pages' => array('actividad'), // post type
		'context' => 'side', //  'normal', 'advanced', or 'side'
		'priority' => 'high',  //  'high', 'core', 'default' or 'low'
		'show_names' => false, // Show field names on the left
		'fields' => array(
				array(
					'name' => 'On/off',
					'id' => $prefix . 'onoff',
					'type' => 'multicheck',
					'options' => array(
						'on' => 'Online',
						'off' => 'Offline',
					)
				),
		),
	);

	return $meta_boxes;
} // end Add metaboxes

// Initialize the metabox class
function quincem_init_metaboxes() {
	if ( !class_exists( 'cmb_Meta_Box' ) ) {
		require_once( 'lib/metabox/init.php' );
	}
} // end Init metaboxes

?>
