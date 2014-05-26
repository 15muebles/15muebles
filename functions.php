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
	add_filter( 'image_size_names_choose', 'quincem_custom_sizes' );

	/* Load JavaScript files on the 'wp_enqueue_scripts' action hook. */
	add_action( 'wp_enqueue_scripts', 'quincem_load_scripts' );

	// Custom post types
	add_action( 'init', 'quincem_create_post_type', 0 );

	// Extra meta boxes in editor
	add_filter( 'cmb_meta_boxes', 'quincem_metaboxes' );
	// Initialize the metabox class
	add_action( 'init', 'quincem_init_metaboxes', 9999 );

	// excerpt support in pages
	add_post_type_support( 'page', 'excerpt' );

	// add page order to itinerarios
	add_post_type_support( 'itinerarios', 'page-attributes' );
	add_post_type_support( 'badge', 'page-attributes' );

	// remove unused items from dashboard
	add_action( 'admin_menu', 'quincem_remove_dashboard_item' );

	// disable admin bar in front end
	add_filter('show_admin_bar', '__return_false');

	// adding classes to post_class()
	add_filter('post_class', 'quincem_classes');

	add_action('wp_insert_post', 'quincem_write_badge_metadata');
	//add_action('wp_insert_post', 'quincem_write_earner_metadata');
	add_action('draft_to_publish', 'quincem_earner_admited');

} // end quincem theme setup function

// remove item from wordpress dashboard
function quincem_remove_dashboard_item() {
	remove_menu_page('edit.php');	
}

// set up media options
function quincem_media_options() {
	/* Add theme support for post thumbnails (featured images). */
	add_theme_support( 'post-thumbnails', array( 'page','badge','itinerario','actividad','earner') );

	// add icon and extra sizes
	add_image_size( 'icon', '32', '32', true );
	add_image_size( 'bigicon', '48', '48', true );
	add_image_size( 'small', '234', '0', false );
	add_image_size( 'extralarge', '819', '0', false );


	/* set up image sizes*/
	update_option('thumbnail_size_w', 117);
	update_option('thumbnail_size_h', 0);
	update_option('medium_size_w', 351);
	update_option('medium_size_h', 0);
	update_option('large_size_w', 468);
	update_option('large_size_h', 0);
} // end set up media options

function quincem_custom_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'icon' => __('Icon'),
        'small' => __('Small'),
        'extralarge' => __('Extra Large'),
    ) );
}

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
	if ( is_page_template("page-about.php") ) {
	wp_enqueue_script(
		'smooth-scroll-page-js',
		get_template_directory_uri() . '/bootstrap/js/smooth.scroll.page.js',
		array( 'bootstrap-js' ),
		'0.1',
		FALSE
	);
	}
	if ( !is_page() ) {
	wp_enqueue_script(
		'tit-position-js',
		get_template_directory_uri() . '/bootstrap/js/tit.position.js',
		array( 'bootstrap-js' ),
		'0.1',
		FALSE
	);
	}
	if ( is_page_template("page-solicita-badge.php") ) {
	wp_enqueue_script(
		'form-empty-fields-js',
		get_template_directory_uri() . '/js/form.empty.fields.js',
		array( 'jquery' ),
		'0.1',
		FALSE
	);
	}
	if ( is_singular('earner') ) {
//	wp_enqueue_script(
//		'issuer-js',
//		'https://backpack.openbadges.org/issuer.js',
//		array( 'jquery' ),
//		'0.1',
//		FALSE
//	);
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
			'parent' => __( 'Aprende' )
		),
		'description' => 'Estos son nuestros 15 badges: unidades de aprendizaje sobre las habilidades, saberes y herramientas que creemos importante poner en juego para construir una ciudad mejor.',
		'has_archive' => false,
		'public' => true,
		'publicly_queryable' => true,
		'exclude_from_search' => false,
		'menu_position' => 5,
		'menu_icon' => get_template_directory_uri() . '/images/quincem-dashboard-pt-badge.png',
		'hierarchical' => false, // if true this post type will be as pages
		'query_var' => true,
		'supports' => array('title', 'editor','excerpt','author','trackbacks','thumbnail'),
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
			'parent' => __( 'Descubre' )
		),
		'description' => 'Estos son nuestros 5 itinerarios pedagógicos. Cada itinerario ensaya un recorrido práctico y teórico sobre otra ciudad posible: imaginarios, herramientas, juegos y lenguajes urbanos que nos gustaría sirvieran para empoderar otras políticas.',
		'has_archive' => false,
		'public' => true,
		'publicly_queryable' => true,
		'exclude_from_search' => false,
		'menu_position' => 5,
		'menu_icon' => get_template_directory_uri() . '/images/quincem-dashboard-pt-itinerario.png',
		'hierarchical' => true, // if true this post type will be as pages
		'query_var' => true,
		'supports' => array('title', 'editor','excerpt','author','trackbacks','thumbnail','page-attributes' ),
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
			'parent' => __( 'Haz' )
		),
		'description' => 'Echa un vistazo a todas las actividades en las que puedes inscribirte y participar: desde talleres de auto-construcción a seminarios teóricos, pasando por debates virtuales o recorridos de abastecimiento.',
		'has_archive' => false,
		'public' => true,
		'publicly_queryable' => true,
		'exclude_from_search' => false,
		'menu_position' => 5,
		'menu_icon' => get_template_directory_uri() . '/images/quincem-dashboard-pt-actividad.png',
		'hierarchical' => false, // if true this post type will be as pages
		'query_var' => true,
		'supports' => array('title', 'editor','excerpt','author','trackbacks','thumbnail' ),
		'rewrite' => array('slug'=>'actividad','with_front'=>false),
		'can_export' => true,
		'_builtin' => false,
	));

	// Earners post type
	register_post_type( 'earner', array(
		'labels' => array(
			'name' => __( 'Earners' ),
			'singular_name' => __( 'Earner' ),
			'add_new_item' => __( 'Añadir earner' ),
			'edit' => __( 'Editar' ),
			'edit_item' => __( 'Editar este earner' ),
			'new_item' => __( 'Nuevo earner' ),
			'view' => __( 'Ver earner' ),
			'view_item' => __( 'Ver este earner' ),
			'search_items' => __( 'Buscar earners' ),
			'not_found' => __( 'Ningún earner encontrado' ),
			'not_found_in_trash' => __( 'Ningún earner en la papelera' ),
			'parent' => __( 'Parent' )
		),
		'description' => '',
		'has_archive' => false,
		'public' => true,
		'publicly_queryable' => true,
		'exclude_from_search' => false,
		'menu_position' => 5,
		'menu_icon' => get_template_directory_uri() . '/images/quincem-dashboard-pt-earner.png',
		'hierarchical' => false, // if true this post type will be as pages
		'query_var' => true,
		'supports' => array('title','author','trackbacks','thumbnail'),
		'rewrite' => array('slug'=>'earner','with_front'=>false),
		'can_export' => true,
		'_builtin' => false,
	));

} // end register post types

// get all posts from a post type to be used in select or multicheck forms
function quincem_get_list($post_type) {
	$posts = get_posts(array(
		'posts_per_page' => -1,
		'post_type' => $post_type,
	));
	if ( count($posts) > 0 ) {
		foreach ( $posts as $post ) {
			$list[$post->ID] = $post->post_title;
		}
		return $list;
	}
}

//Add metaboxes to several post types edit screen
function quincem_metaboxes( $meta_boxes ) {
	$prefix = '_quincem_'; // Prefix for all fields

	// get data for select and multicheck fields
	//$itinerarios = quincem_get_list("itinerario");
	$actividades = quincem_get_list("actividad");
	$badges = quincem_get_list("badge");

	// CUSTOM FIELDS FOR ITINERARIOS AND BADGES
	$meta_boxes[] = array(
		'id' => 'quincem_subtit',
		'title' => 'Subtítulo',
		'pages' => array('itinerario','badge'), // post type
		'context' => 'normal', //  'normal', 'advanced', or 'side'
		'priority' => 'high',  //  'high', 'core', 'default' or 'low'
		'show_names' => false, // Show field names on the left
		'fields' => array(
			array(
				'name' => 'Subtítulo',
				'desc' => 'Únicamente el subtítulo, sin paréntesis.',
				'id' => $prefix . 'subtit',
				'type' => 'text',
			),
		),
	);
	$meta_boxes[] = array(
		'id' => 'quincem_icono',
		'title' => 'Icono',
		'pages' => array('itinerario','badge'), // post type
		'context' => 'side', //  'normal', 'advanced', or 'side'
		'priority' => 'default',  //  'high', 'core', 'default' or 'low'
		'show_names' => false, // Show field names on the left
		'fields' => array(
			array(
				'name' => 'Icono',
				'desc' => '',
				'id' => $prefix . 'icono',
				'type' => 'file',
				'allow' => array( 'attachment' )
			),
		),
	);

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

	// On/Off, escenario, fechas for actividades
	$meta_boxes[] = array(
		'id' => 'quincem_actividad_meta',
		'title' => 'Información sobre la actividad',
		'pages' => array('actividad'), // post type
		'context' => 'side', //  'normal', 'advanced', or 'side'
		'priority' => 'high',  //  'high', 'core', 'default' or 'low'
		'show_names' => false, // Show field names on the left
		'fields' => array(
			array(
				'name' => 'Tipo: On/offline',
				'id' => $prefix . 'onoff',
				'type' => 'multicheck',
				'options' => array(
					'on' => 'Online',
					'off' => 'Offline',
				),
			),
			array(
				'name' => 'Escenario',
				'id' => $prefix . 'escenario',
				'type' => 'text'
			),
			array(
				'name' => 'Fecha inicio',
				'id'   => $prefix . 'date_begin',
				'type' => 'text_date_timestamp',
				'repeatable' => false,
			),
			array(
				'name' => 'Fecha fin',
				'id'   => $prefix . 'date_end',
				'type' => 'text_date_timestamp',
				'repeatable' => false,
			),
		),
	);
	// Info de contacto for actividades
	$meta_boxes[] = array(
		'id' => 'quincem_contact',
		'title' => 'Contacto',
		'pages' => array('actividad'), // post type
		'context' => 'normal', //  'normal', 'advanced', or 'side'
		'priority' => 'default',  //  'high', 'core', 'default' or 'low'
		'show_names' => false, // Show field names on the left
		'fields' => array(
				array(
					'name' => 'Contacto',
					'id' => $prefix . 'contacto',
					'type' => 'wysiwyg',
					'options' => array(),
				),
		),
	);

	// CUSTOM FIELDS FOR EARNERS
	///

	// mail, evidence, badge
	$meta_boxes[] = array(
		'id' => 'quincem_earner_meta',
		'title' => 'Información sobre el earner',
		'pages' => array('earner'), // post type
		'context' => 'normal', //  'normal', 'advanced', or 'side'
		'priority' => 'high',  //  'high', 'core', 'default' or 'low'
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => 'Nombre',
				'id' => $prefix . 'earner_name',
				'type' => 'text',
			),
			array(
				'name' => 'Dirección email',
				'id' => $prefix . 'earner_mail',
				'type' => 'text',
			),
			array(
				'name' => 'URL del material producido',
				'id' => $prefix . 'earner_material',
				'type' => 'text'
			),
			array(
				'name' => 'Actividad realizada',
				'id' => $prefix . 'earner_actividad',
				'type' => 'text'
			),
			array(
				'name' => 'Badge conseguido',
				'id' => $prefix . 'earner_badge',
				'type' => 'select',
				'options' => $badges,
				'default' => 'none',
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

// adding classes to post_class()
function quincem_classes($classes) {
	// add bootstrap classes to post elements
	global $post;
	if ( is_home() ) { $new_classes = array("thumbnail");}
	elseif ( is_single() ) { $new_classes = array("row");}
		foreach( $new_classes as $class ) {
		        $classes[] = $class;
		        return $classes;
		}
} // end adding classes to post_class()

// create badge json metadata
function quincem_write_badge_metadata() {

	global $post;
	if ( $post ) {

	// If this is just a revision, don't continue
	if ( wp_is_post_revision( $post->ID ) )
		return;

	if ( $post->post_type == 'badge' && $post->post_status == 'publish' ) {
		$badge_json = $_SERVER['DOCUMENT_ROOT'] . "/openbadges/badge-" .$post->post_name. ".json";
		$badge_img = $_SERVER['DOCUMENT_ROOT'] . "/openbadges/images/badge-" .$post->post_name. ".png";

		$perma = get_permalink($post->ID);
		$subtit = get_post_meta( $post->ID, '_quincem_subtit',true );
		$wp_img_id = get_post_thumbnail_id( $post->ID );
 
		$data = '{
"name": "' .$post->post_title. '. ' .$subtit. '",
"description": "' .$post->post_excerpt. '",
"image": "http://ciudad-escuela.org/openbadges/images/badge-' .$post->post_name. '.png",
"criteria": "' .$perma. '",
"issuer": "http://ciudad-escuela.org/openbadges/issuer-15muebles.json"
}';

		// json metadata file
		$handle = fopen( $badge_json, 'w') or die("Cannot create the file index.html. Be sure that " .$badge_json. " directory is writable."); //open file for writing
		$write_success = fwrite($handle, $data);
		fclose($handle);

		// badge image
		if ( $wp_img_id == '' || $wp_img_id == FALSE ) {} else {
			//copy( wp_get_attachment_url($wp_img_id), $badge_img );
			$wp_img = wp_get_attachment_image_src($wp_img_id,'large');
			copy( $wp_img[0], $badge_img );
		}

	}

	} // end if $post is set

} // end create badge json metadata

// create earner json metadata and notificate him/her by mail
function quincem_earner_admited() {

	global $post;
	if ( $post ) {

	// If this is just a revision, don't continue
	if ( wp_is_post_revision( $post->ID ) )
		return;

	//if ( $post->post_type == 'earner' && $post->post_status == 'publish' ) {
	if ( $post->post_type == 'earner' ) {

		$earner_name = get_post_meta( $post->ID, '_quincem_earner_name',true );
		$earner_mail = get_post_meta( $post->ID, '_quincem_earner_mail',true );
		$earner_evidence = get_post_meta( $post->ID, '_quincem_earner_material',true );
		$earner_date = $post->post_date;
		$earner_badge = get_post_meta( $post->ID, '_quincem_earner_badge',true );
		$earner_actividad = get_post_meta( $post->ID, '_quincem_earner_actividad',true );
		$earner_perma = get_permalink($post->ID);

		$args = array(
			'post_type' => 'badge',
			'include' => $earner_badge
		);
		$badges = get_posts($args);
		foreach ( $badges as $badge ) {
			$earner_badge_tit = $badge->post_title;
			$earner_badge_slug = $badge->post_name;
			$badge_perma = get_permalink();
			$badge_json = "http://ciudad-escuela.org/openbadges/badge-" .$badge->post_name. ".json";
			$badge_img = "http://ciudad-escuela.org/openbadges/images/badge-" .$badge->post_name. ".png";		
			$earner_json_url = "http://ciudad-escuela.org/openbadges/assertions/badge-" .$badge->post_name. "-" .$post->ID. ".json";
			$earner_json_path = $_SERVER['DOCUMENT_ROOT'] . "/openbadges/assertions/badge-" .$badge->post_name. "-" .$post->ID. ".json";
		}
 
		$data = '{
"uid": "' .$post->ID. '",
"recipient": {
	"type": "email",
	"hashed": false,
	"identity": "' .$earner_mail. '"
},
"evidence": "' .$earner_evidence. '",
"issuedOn": "' .$earner_date. '",
"badge": "' .$badge_json. '",
"image": "' .$badge_img. '",
"verify": {
	"type": "hosted",
	"url": "' .$earner_json_url. '"
}
}';

		// json metadata file
		$handle = fopen( $earner_json_path, 'w') or die("Cannot create the file index.html. Be sure that " .$earner_json_path. " directory is writable."); //open file for writing
		$write_success = fwrite($handle, $data);
		fclose($handle);

		// notificate user when badge application is admited
		if ( $write_success != FALSE ) {
			$to = $earner_mail;
			$subject = "Has ganado el badge " .$earner_badge_tit;
			$message = '
¡Enhorabuena ' .$earner_name. '!'
. "\r\n\r\n" .
'has ganado el badge ' .$earner_badge_tit. ' por tu participación en la actividad ' .$earner_actividad. '.'
. "\r\n\r\n" .
'Para añadirlo a tu mochila, y poder así mostrárselo al mundo, puedes visitar el siguiente enlace: ' .$earner_perma. '.'
. "\r\n\r\n" .
'Te hemos incluido en la lista de personas que ganaron este badge, para que otros puedan consultar el material que has generado. Puedes ver la lista en la sección "Ganaron el badge" del siguiente enlace: ' .$badge_perma. '.'
. "\r\n\r\n" .
'Si prefieres no figurar en la lista, comunícanoslo: el badge es tuyo y tú decides dónde y cómo mostrarlo. Puedes escribirnos a badges@ciudad-escuela.org'
. "\r\n\r\n" .
'Un saludo del equipo de Ciudad Escuela.';
			$headers[] = 'From: Ciudad Escuela <badges@ciudad-escuela.org>' . "\r\n";
			$headers[] = 'To: ' .$earner_name. ' <' .$to. '>' . "\r\n";
			// To send HTML mail, the Content-type header must be set, uncomment the following two lines
			//$headers[]  = 'MIME-Version: 1.0' . "\r\n";
			//$headers[] = 'Content-type: text/html; charset=utf-8' . "\r\n";
			wp_mail( $to, $subject, $message, $headers);

		} // end if metadata write success
		// end notificate user

	} // end if post type earner

	} // end if $post is set

} // end create badge json metadata


// reclaim a badge form
function quincem_reclaim_badge_form() {

	$action = get_permalink();

	$badges = quincem_get_list("badge");
	$options_badges = "<option></option>";
	while ( $badge = current($badges) ) {
		$options_badges .= "<option value='" .key($badges). "'>" .$badge. "</option>";
		next($badges);
	}

	$form_out = "
<form id='quincem-form-content' method='post' action='" .$action. "'>
<div class='row'>
<div class='form-horizontal col-md-10'>

<div class='form-group'>
<label for='quincem-form-badge-name' class='col-sm-4 control-label'>Tu nombre</label>
<div class='col-sm-6'>
    <input class='form-control' type='text' value='' name='quincem-form-badge-name' />
</div>
</div>

<div class='form-group'>
<label for='quincem-form-badge-mail' class='col-sm-4 control-label'>Tu dirección de correo electrónico</label>
<div class='col-sm-6'>
    <input class='form-control' type='text' value='' name='quincem-form-badge-mail' />
</div>
</div>

<div class='form-group'>
<label for='quincem-form-badge-actividad' class='col-sm-4 control-label'>Actividad realizada</label>
<div class='col-sm-6'>
    <input class='form-control' type='text' value='' name='quincem-form-badge-actividad' />
</div>
</div>

<div class='form-group'>
<label for='quincem-form-badge-badge' class='col-sm-4 control-label'>Badge solicitado</label>
<div class='col-sm-6'>
	<select class='form-control' name='quincem-form-badge-badge'i maxlenght='11' >
		" .$options_badges. "
	</select>
</div>
</div>

<div class='form-group'>
<label for='quincem-form-badge-material' class='col-sm-4 control-label'>Dirección URL al material producido</label>
<div class='col-sm-6'>
    <input class='form-control' type='text' value='' name='quincem-form-badge-material' />
</div>
</div>

<div class='form-group'>
    <div class='col-sm-offset-4 col-sm-6'>
    <input class='btn btn-default' type='submit' value='Enviar' name='quincem-form-badge-submit' />
	<span class='help-block'><small>Todos los campos son requeridos.</small></span>
    </div>
  </div>


</div>
</div>
</form>
";
	echo $form_out;

} // end reclaim a badge form

// insert earner data in database
function quincem_insert_earner() {

	// messages and locations for redirection
	$perma = get_permalink();
	$location = $perma."?form=success";
	$error = "<div class='alert alert-danger'>Uno o varios campos están vacíos o no tienen un formato válido: en cualquier caso el formulario no se envió correctamente. Por favor, inténtalo de nuevo.</div>";
	$success = "<div class='alert alert-success'>El formulario ha sido enviado correctamente: hemos recibido tus datos. Vamos a revisarlos y si todo está correcto recibirás el badge en unos cuantos días.</div><p><strong>¿Quieres solicitar otro badge?</strong>: <a href='" .$perma. "'>vuelve al formulario</a>.</p>";

	if ( array_key_exists('form', $_GET) ) {
	if ( sanitize_text_field( $_GET['form']) == 'success' ){
		echo "<strong>" .$success. "</strong>";
		return;
	}
	}

	if ( !array_key_exists('quincem-form-badge-submit', $_POST) ) {
		quincem_reclaim_badge_form();
		return;

	} elseif ( sanitize_text_field( $_POST['quincem-form-badge-submit'] ) != 'Enviar' ) {
		quincem_reclaim_badge_form();
		return;
	}

	// check if all fields have been filled
	// sanitize them all
	$earner_name = sanitize_text_field( $_POST['quincem-form-badge-name'] );
	$earner_mail = sanitize_email( $_POST['quincem-form-badge-mail'] );
	$earner_material = sanitize_text_field( $_POST['quincem-form-badge-material'] );
	$earner_actividad = sanitize_text_field( $_POST['quincem-form-badge-actividad'] );
	$earner_badge = intval( $_POST['quincem-form-badge-badge'] );
	if ( strlen( $earner_badge ) > 11 ) {
		echo $error;
		quincem_reclaim_badge_form();
		return;
	}
	$args = array(
		'post_type' => 'badge',
		'include' => $earner_badge
	);
	$badges = get_posts($args);
	if ( count($badges) == 1 ) {
		foreach ( $badges as $badge ) {
			$earner_badge_tit = $badge->post_title;
			$earner_badge_slug = $badge->post_name;
		}
	} else {
		echo $error;
		quincem_reclaim_badge_form();
		return;
	}

	$fields = array(
		'_quincem_earner_name' => $earner_name,
		'_quincem_earner_mail' => $earner_mail,
		'_quincem_earner_actividad' => $earner_actividad,
		'_quincem_earner_badge' => $earner_badge,
		'_quincem_earner_material' => $earner_material,
	);
	foreach ( $fields as $field ) {
		if ( $field == '' ) {
			echo $error;
			quincem_reclaim_badge_form();
			return;
		}
	}
	// end checking

	// if everything ok, do insert
	//$earner_tit = sanitize_title( $earner_name. ": " .$earner_badge_tit, "Título provisional" );
	$earner_tit = $earner_name. ": " .$earner_badge_tit;

	// insert post
	$earner_id = wp_insert_post(array(
		'post_type' => 'earner',
		'post_status' => 'draft',
		'post_author' => 1,
		'post_title' => $earner_tit,
	));

	if ( $earner_id == 0 ) {
		echo $error;
		quincem_reclaim_badge_form();
		return;
	}

	// insert custom fields
	reset($fields);
	while ( $field = current($fields) ) {
		add_post_meta($earner_id, key($fields), $field, TRUE);
		next($fields);
	}

	// send confirmation mail to earner
	$to = $earner_mail;
	$subject = "Solicitud de concesión de badge de Ciudad Escuela";
	$message = '
Hola ' .$earner_name. ','
. "\r\n\r\n" .
'si estás leyendo este mensaje todo ha ido bien: hemos recibido tu petición para emitir el badge ' .$earner_badge_tit. ' a tu nombre, por tu participación en la actividad ' .$earner_actividad. '.'
. "\r\n\r\n" .
'Vamos a revisar el material que has generado durante la actividad [' .$earner_material. '], y si cumple los criterios en unos días recibirás el badge en esta misma dirección de correo, junto con las instrucciones para mostrarlo al mundo.'
. "\r\n\r\n" .
'Un saludo del equipo de Ciudad Escuela.';
	$headers[] = 'From: Ciudad Escuela <no-reply@ciudad-escuela.org>' . "\r\n";
	$headers[] = 'To: ' .$earner_name. ' <' .$to. '>' . "\r\n";
	// To send HTML mail, the Content-type header must be set, uncomment the following two lines
	//$headers[]  = 'MIME-Version: 1.0' . "\r\n";
	//$headers[] = 'Content-type: text/html; charset=utf-8' . "\r\n";
	wp_mail( $to, $subject, $message, $headers);

	// send notification mail to issuer
	$to = "info@montera34.com";
	$subject = "Solicitud de emisión de badge de Ciudad Escuela";
	$message = '
+ Nombre del solicitante: ' .$earner_name.
"\r\n" .
'+ Dirección e-mail del solicitante: ' .$earner_mail.
"\r\n\r\n" .
'+ Actividad realizada: ' .$earner_actividad.
"\r\n" .
'+ Badge solicitado: ' .$earner_badge_tit.
"\r\n" .
'+ URL del material producido: ' .$earner_material;
	$headers[] = 'From: ' .$earner_name. ' <' .$earner_mail. '>' . "\r\n";
	$headers[] = 'To: <' .$to. '>' . "\r\n";
	// To send HTML mail, the Content-type header must be set, uncomment the following two lines
	//$headers[]  = 'MIME-Version: 1.0' . "\r\n";
	//$headers[] = 'Content-type: text/html; charset=utf-8' . "\r\n";
	wp_mail( $to, $subject, $message, $headers);

	wp_redirect( $location );
	exit;

} // end insert earner data in database

// add badge to backpack, notificate ciudad escuela system
//function quincem_earn_badge() {

	

//} // end add badge to backpack
?>
