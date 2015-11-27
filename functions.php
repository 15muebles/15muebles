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

	// disable admin bar in front end
	add_filter('show_admin_bar', '__return_false');

	// adding classes to post_class()
	add_filter('post_class', 'quincem_classes');

	//add_action('wp_insert_post', 'quincem_write_badge_metadata');
	add_action('save_post', 'quincem_write_badge_metadata',9999);
	//add_action('wp_insert_post', 'quincem_write_earner_metadata');
	add_action('draft_to_publish', 'quincem_earner_admited');
	//add_action('wp_insert_post', 'quincem_write_badge_metadata');
	add_action('save_post', 'quincem_write_issuer_metadata',9999);

	// Enable support for Post Formats.
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link', 'gallery', 'status', 'audio', 'chat'
	) );

	// register and init widget bars
	add_action( 'widgets_init', 'quincem_widgets_init' );
	
	// Add post types to api
	add_action( 'init', 'quincem_api_post_type_support', 25 );
} // end quincem theme setup function


// register and init widget bars
function quincem_widgets_init() {

	register_sidebar( array(
		'name'          => 'Barra lateral derecha del blog',
		'id'            => 'blog_right',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widget-tit"><span class="glyphicon glyphicon-star"></span> ',
		'after_title'   => '</h2>',
	) );

}


// set up media options
function quincem_media_options() {
	/* Add theme support for post thumbnails (featured images). */
	add_theme_support( 'post-thumbnails', array( 'post','page','badge','itinerario','actividad','earner','issuer') );

	// add icon and extra sizes
	add_image_size( 'icon', '32', '32', true );
	add_image_size( 'bigicon', '48', '48', true );
	add_image_size( 'small', '234', '0', false );
	add_image_size( 'larger', '672', '0', false );
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
        'larger' => __('Larger'),
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
	if ( is_front_page() ) {
	wp_enqueue_script(
		'smooth-scroll-js',
		get_template_directory_uri() . '/js/smooth.scroll.js',
		array( 'bootstrap-js' ),
		'0.1',
		FALSE
	);
	}
	if ( is_page_template("page-about.php") ) {
	wp_enqueue_script(
		'smooth-scroll-page-js',
		get_template_directory_uri() . '/js/smooth.scroll.page.js',
		array( 'bootstrap-js' ),
		'0.1',
		FALSE
	);
	}
	if ( !is_page() || is_front_page() ) {
	wp_enqueue_script(
		'tit-position-js',
		get_template_directory_uri() . '/js/tit.position.js',
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
		'hierarchical' => true, // if true this post type will be as pages
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

	// Issuer post type
	register_post_type( 'issuer', array(
		'labels' => array(
			'name' => __( 'Issuers' ),
			'singular_name' => __( 'Issuer' ),
			'add_new_item' => __( 'Añadir issuer' ),
			'edit' => __( 'Editar' ),
			'edit_item' => __( 'Editar este issuer' ),
			'new_item' => __( 'Nuevo issuer' ),
			'view' => __( 'Ver issuer' ),
			'view_item' => __( 'Ver este issuer' ),
			'search_items' => __( 'Buscar issuers' ),
			'not_found' => __( 'Ningún issuer encontrado' ),
			'not_found_in_trash' => __( 'Ningún issuer en la papelera' ),
			'parent' => __( 'Emisor de badges' )
		),
		'description' => 'Éste es el perfil de una de las organizaciones que emiten badges en Ciudad Escuela. Si te interesa alguno de ellos, puedes consultar las actividades relacionadas para conseguirlo. Si lo prefieres puedes ponerte en contacto con el emisor directamente en la sección contacto.',
		'has_archive' => false,
		'public' => true,
		'publicly_queryable' => true,
		'exclude_from_search' => true,
		'menu_position' => 5,
		'menu_icon' => 'dashicons-welcome-learn-more',
		'hierarchical' => false, // if true this post type will be as pages
		'query_var' => true,
		'supports' => array('title', 'editor','excerpt','author','trackbacks','thumbnail'),
		'rewrite' => array('slug'=>'issuer','with_front'=>false),
		'can_export' => true,
		'_builtin' => false,
	));

} // end register post types

// get all posts from a post type to be used in select or multicheck forms
function quincem_get_list( $post_type,$output = 'form_select' ) {
	if ( $post_type == 'badge' ) {
		$args = array(
			'posts_per_page' => -1,
			'post_type' => $post_type,
			'post_parent' => 0
		);
		$badges = get_posts($args);
		$not_in = array();
		foreach ( $badges as $b ) {
			$args = array(
				'post_type' => $post_type,
				'post_parent' => $b->ID,
				'meta_key' => '_quincem_version',
				'orderby' => 'meta_value_num',
				'order' => 'DESC'

			);
			$children = get_posts($args);
			if ( count($children) == 1 ) { $not_in[] = $b->ID; }
			elseif ( count($children) >= 2 ) {
				$not_in[] = $b->ID;
				$ch_count = 0;
				foreach ( $children as  $ch ) {
					if ( $ch_count != 0 ) { $not_in[] = $ch->ID; }
					$ch_count++;
				}
			}
			
		}
		$posts = get_posts(array(
			'posts_per_page' => -1,
			'post_type' => $post_type,
			'orderby' => 'menu_order title',
			'order' => 'ASC',
			'post__not_in' => $not_in
		));
	
	} else {
		$posts = get_posts(array(
			'posts_per_page' => -1,
			'post_type' => $post_type,
			'orderby' => 'title',
			'order' => 'ASC'
		));
	
	}

	if ( count($posts) > 0 ) {
		if ( $output == 'form_select' ) { $list[] = ''; }
		foreach ( $posts as $post ) {
			$list[$post->ID] = $post->post_title;
		}
		return $list;
	}

}// END quincem_get_list

// get a list of users to use in select or multicheck form fields
function quincem_get_users( $role,$first_element_empty = false ) {
	if ( $role != 'all' ) {
		$args = array(
			'role' => $role,
			'orderby' => 'nicename',
			'order' => 'ASC'
		);

	} else {
		$args = array(
			'orderby' => 'nicename',
			'order' => 'ASC'
		);
	}
	$users = get_users($args);
	if ( count($users) > 0 ) {
		if ( $first_element_empty == true ) { $list[] = ''; }
		foreach ( $users as $u ) {
			$list[$u->ID] = $u->display_name;
		}
		return $list;
	}
	
} // END quincem_get_users

//Add metaboxes to several post types edit screen
function quincem_metaboxes( $meta_boxes ) {
	$prefix = '_quincem_'; // Prefix for all fields

	// get data for select and multicheck fields
	//$itinerarios = quincem_get_list("itinerario");
	$actividades = quincem_get_list("actividad");
	$badges = quincem_get_list("badge");
	$issuers = quincem_get_list("issuer",'form_select');
	$users = quincem_get_users('all',true);

	// CUSTOM FIELDS FOR ISSUERS
	$meta_boxes[] = array(
		'id' => 'quincem_issuer',
		'title' => 'Metadatos del emisor',
		'pages' => array('issuer'), // post type
		'context' => 'normal', //  'normal', 'advanced', or 'side'
		'priority' => 'high',  //  'high', 'core', 'default' or 'low'
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => 'URL',
				'desc' => '',
				'id' => $prefix . 'issuer_url',
				'type' => 'text_url',
			),
			array(
				'name' => 'E-mail',
				'desc' => 'La dirección de email no podrá cambiarse.',
				'id' => $prefix . 'issuer_email',
				'type' => 'text_email',
			),
		),
	);
	$meta_boxes[] = array(
		'id' => 'quincem_issuer_notifica',
		'title' => 'Datos para notificaciones',
		'pages' => array('issuer'), // post type
		'context' => 'normal', //  'normal', 'advanced', or 'side'
		'priority' => 'high',  //  'high', 'core', 'default' or 'low'
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => 'E-mail',
				'desc' => 'En esta dirección se recibirán las peticiones de emisión de badges.',
				'id' => $prefix . 'issuer_notifica_email',
				'type' => 'text_email',
			),
		),
	);
	$meta_boxes[] = array(
		'id' => 'quincem_issuer_user',
		'title' => 'Usuario Ciudad Escuela',
		'pages' => array('issuer'), // post type
		'context' => 'side', //  'normal', 'advanced', or 'side'
		'priority' => 'default',  //  'high', 'core', 'default' or 'low'
		'show_names' => false, // Show field names on the left
		'fields' => array(
			array(
				'name' => 'Usuario',
				'desc' => 'Usuario Ciudad Escuela asociado a este emisor.',
				'id' => $prefix . 'issuer_user',
				'type' => 'select',
				'options' => $users
			),
		),
	);

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
		'id' => 'quincem_issuers_list',
		'title' => 'Emisor',
		'pages' => array('itinerario','badge'), // post type
		'context' => 'side', //  'normal', 'advanced', or 'side'
		'priority' => 'high',  //  'high', 'core', 'default' or 'low'
		'show_names' => false, // Show field names on the left
		'fields' => array(
			array(
				'name' => 'Selecciona un emisor',
				'desc' => 'Emisor de este badge o itinerario.',
				'id' => $prefix . 'issuer',
				'type' => 'select',
				'options' => $issuers
			),
		),
	);

	// CUSTOM FIELDS FOR ITINERARIOS AND BADGES AND ISSUERS
	$meta_boxes[] = array(
		'id' => 'quincem_icono',
		'title' => 'Icono',
		'pages' => array('itinerario','badge','issuer'), // post type
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

	// Version
	$meta_boxes[] = array(
		'id' => 'quincem_version',
		'title' => 'Versión del badge',
		'pages' => array('badge'), // post type
		'context' => 'side', //  'normal', 'advanced', or 'side'
		'priority' => 'high',  //  'high', 'core', 'default' or 'low'
		'show_names' => false, // Show field names on the left
		'fields' => array(
			array(
				'name' => 'Versión',
				'id' => $prefix . 'version',
				'type' => 'text',
				'description' => 'Número de versión del badge: solo números naturales.'
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
	if ( is_front_page() ) { $new_classes = array("thumbnail");}
	elseif ( is_single() ) { $new_classes = array("row");}
		foreach( $new_classes as $class ) {
		        $classes[] = $class;
		        return $classes;
		}
} // end adding classes to post_class()

// create issuer json metadata
function quincem_write_issuer_metadata() {

	global $post;
	if ( $post ) {

	// If this is just a revision, don't continue
	if ( wp_is_post_revision( $post->ID ) )
		return;

	if ( $post->post_type == 'issuer' && $post->post_status == 'publish' ) {
		$issuer_json = $_SERVER['DOCUMENT_ROOT'] . "/openbadges/issuer-" .$post->post_name. ".json";

		$issuer_url = get_post_meta($post->ID,'_quincem_issuer_url',true);
		$issuer_email = get_post_meta( $post->ID, '_quincem_issuer_email',true );
 
		$data = '{
"name": "' .$post->post_title. '",
"url": "' .$issuer_url. '",
"description": "' .$post->post_excerpt. '",
"url": "' .$issuer_email. '"
}';

		// json metadata file
		$handle = fopen( $issuer_json, 'w') or die("Cannot create the file index.html. Be sure that " .$issuer_json. " directory is writable."); //open file for writing
		$write_success = fwrite($handle, $data);
		fclose($handle);

	}

	} // end if $post is set

} // end create badge json metadata

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

		$issuer_id = get_post_meta( $post->ID, '_quincem_issuer',true);
		$args = array(
			'post_type' => 'issuer',
			'include' => array($issuer_id)
		);
		$issuers = get_posts( $args );
		foreach ( $issuers as $i ) { $issuer_slug = $i->post_name; }
		$data = '{
"name": "' .$post->post_title. '. ' .$subtit. '",
"description": "' .$post->post_excerpt. '",
"image": "http://ciudad-escuela.org/openbadges/images/badge-' .$post->post_name. '.png",
"criteria": "' .$perma. '",
"issuer": "http://ciudad-escuela.org/openbadges/issuer-'.$issuer_slug.'.json"
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
			$badge_perma = get_permalink($badge->ID);
			$badge_json = "http://ciudad-escuela.org/openbadges/badge-" .$badge->post_name. ".json";
			$badge_img = "http://ciudad-escuela.org/openbadges/images/badge-" .$badge->post_name. ".png";		
			$earner_json_url = "http://ciudad-escuela.org/openbadges/assertions/badge-" .$badge->post_name. "-" .$post->ID. ".json";
			$earner_json_path = $_SERVER['DOCUMENT_ROOT'] . "/openbadges/assertions/badge-" .$badge->post_name. "-" .$post->ID. ".json";

			$badge_issuer = get_post_meta($badge->ID,'_quincem_issuer',true);
			$args = array(
				'post_type' => 'issuer',
				'include' => $badge_issuer
			);
			$issuers = get_posts($args);
			foreach ( $issuers as $i ) {
				$issuer_name = $i->post_title;
				$issuer_notifica_email = get_post_meta($i->ID,'_quincem_issuer_notifica_email',true);
				if ( $issuer_notifica_email == '' ) { $issuer_notifica_email = get_post_meta($i->ID,'_quincem_issuer_email',true); }
			}
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
'Si prefieres no figurar en la lista, comunícanoslo: el badge es tuyo y tú decides dónde y cómo mostrarlo. Puedes escribirnos a '.$issuer_notifica_email. '.'
. "\r\n\r\n" .
'Un saludo de '.$issuer_name.'.'
;
			$headers[] = 'From: '.$issuer_name. ' <' .$issuer_notifica_email. '>' . "\r\n";
			$headers[] = 'Sender: Sistema de emisión de badge de Ciudad Escuela <badges@ciudad-escuela.org>' . "\r\n";
			$headers[] = 'Reply-To:  '.$issuer_name. ' <' .$issuer_notifica_email. '>' . "\r\n";
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

	// which badge
	if ( array_key_exists('badge_id', $_GET) ) {
		$badge_from = sanitize_text_field( $_GET['badge_id']);
	} else { $badge_from = ""; }

	$badges = quincem_get_list("badge","form_select_first_not_empty");
	$options_badges = "<option></option>";
	while ( $badge = current($badges) ) {
		if ( $badge_from == key($badges) ) {
			$options_badges .= "<option value='" .key($badges). "' selected>" .$badge. "</option>";
		} else {
			$options_badges .= "<option value='" .key($badges). "'>" .$badge. "</option>";
		}
		next($badges);
	}

	$form_out = "
<form id='quincem-form-content' method='post' action='" .$action. "' enctype='multipart/form-data'>
<div class='row'>
<div class='form-horizontal col-md-12'>
<legend>Tus datos</legend>
<div class='form-group'>
<label for='quincem-form-badge-name' class='col-sm-6 control-label'>Nombre</label>
<div class='col-sm-6'>
    <input class='form-control req' type='text' value='' name='quincem-form-badge-name' />
</div>
</div>

<div class='form-group'>
<label for='quincem-form-badge-mail' class='col-sm-6 control-label'>Dirección de correo electrónico</label>
<div class='col-sm-6'>
    <input class='form-control req' type='text' value='' name='quincem-form-badge-mail' />
    <p class='help-block'><small>En el caso de que ya tengas una mochila de badges (Mozilla Backpack) <strong>esta dirección debe ser la misma de tu cuenta Persona</strong>.</small></p>
</div>
</div>

<div class='form-group'>
    <label for='quincem-form-badge-avatar' class='col-sm-6 control-label'>Imagen de perfil</label>
<div class='col-sm-6'>
    <input type='file' name='quincem-form-badge-avatar' />
	<input type='hidden' name='MAX_FILE_SIZE' value='4000000' />
    <p class='help-block'><small>Tu imagen aparecerá en las listas de ganadores del badge. El archivo <strong>no puede ser más grande de 4MB</strong> y <strong>debe ser una archivo de tipo JPG, PNG o GIF</strong>.</small></p>
</div>
  </div>

<legend>Datos del badge que solicitas</legend>
<div class='form-group'>
<label for='quincem-form-badge-actividad' class='col-sm-6 control-label'>Actividad realizada</label>
<div class='col-sm-6'>
    <input class='form-control req' type='text' value='' name='quincem-form-badge-actividad' />
</div>
</div>

<div class='form-group'>
<label for='quincem-form-badge-badge' class='col-sm-6 control-label'>Badge solicitado</label>
<div class='col-sm-6'>
	<select class='form-control req' name='quincem-form-badge-badge' maxlenght='11' >
		" .$options_badges. "
	</select>
</div>
</div>

<div class='form-group'>
<label for='quincem-form-badge-material' class='col-sm-6 control-label'>Dirección URL al material producido</label>
<div class='col-sm-6'>
    <input class='form-control req' type='text' value='' name='quincem-form-badge-material' />
</div>
</div>

<div class='form-group'>
    <div class='col-sm-offset-6 col-sm-6'>
    <input class='btn-cescuela' type='submit' value='Enviar' name='quincem-form-badge-submit' />
	<span class='help-block'><small><strong>Todos los campos son requeridos excepto la imagen</strong>.</small></span>
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
	$error = "<div class='alert alert-danger'>
		<p>Uno o varios campos están vacíos o no tienen un formato válido.</p>
		<p>Si has rellenado el campo de imagen comprueba que no pesa más de 4MB y que está en un formato adecuado (JPG, PNG, GIF).</p>
		<p>En cualquier caso el formulario no se envió correctamente. Por favor, inténtalo de nuevo.</p>
	</div>";
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
	// check that badge exists
	$args = array(
		'post_type' => 'badge',
		'include' => $earner_badge
	);
	$badges = get_posts($args);
	if ( count($badges) == 1 ) {
		foreach ( $badges as $badge ) {
			$earner_badge_tit = $badge->post_title;
			$earner_badge_slug = $badge->post_name;
			$badge_issuer = get_post_meta($badge->ID,'_quincem_issuer',true);
			$args = array(
				'post_type' => 'issuer',
				'include' => $badge_issuer
			);
			$issuers = get_posts($args);
			foreach ( $issuers as $i ) {
				$issuer_name = $i->post_title;
				$issuer_notifica_email = get_post_meta($i->ID,'_quincem_issuer_notifica_email',true);
				if ( $issuer_notifica_email == '' ) { $issuer_notifica_email = get_post_meta($i->ID,'_quincem_issuer_email',true); }
				$issuer_user = get_post_meta($i->ID,'_quincem_issuer_user',true);
				if ( $issuer_user == '' ) { $issuer_user = '1'; }
			}
		}
	} else {
		echo $error;
		quincem_reclaim_badge_form();
		return;
	}
	// check that all required fields were filled
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
	// checking if image file have the right format and size
	if ( array_key_exists('quincem-form-badge-avatar', $_FILES) ) {
		$file = $_FILES['quincem-form-badge-avatar'];
		if ( $file['name'] != '' ) {
			$finfo = new finfo(FILEINFO_MIME_TYPE);
			$mime = $finfo->file($file['tmp_name']); 
			//if ( $file['type'] == 'text/plain' || $file['type'] == 'application/pdf' || $file['type'] == 'application/vnd' ) {}
			if ( $mime == 'image/png' || $mime == 'image/jpg' || $mime == 'image/jpeg' || $mime == 'image/gif' ) {}
			else {
				echo $error;
				quincem_reclaim_badge_form();
				return;
			}
			if ( $file['size'] > '4000000' ) {
				echo $error;
				quincem_reclaim_badge_form();
				return;
			}
		} // if filename is not empty
	} // if file has been uploaded

	// end checking

	// if everything ok, do insert
	//$earner_tit = sanitize_title( $earner_name. ": " .$earner_badge_tit, "Título provisional" );
	$earner_tit = $earner_name. ": " .$earner_badge_tit;

	// insert post
	$earner_id = wp_insert_post(array(
		'post_type' => 'earner',
		'post_status' => 'draft',
		'post_author' => $issuer_user,
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

	// file insert
	$upload_dir_vars = wp_upload_dir();
	$upload_dir = $upload_dir_vars['path']; // absolute path to uploads folder
	$uploaddir = realpath($upload_dir);

	// if image has been added to form
	if ( array_key_exists('quincem-form-badge-avatar', $_FILES) ) {
		$file = $_FILES['quincem-form-badge-avatar'];
		$filename = basename($file['name']); // file name in client machine
		$filename = trim($filename); // removing spaces at the begining and end
		$filename = ereg_replace(" ", "-", $filename); // removing spaces inside the name

		$typefile = $file['type']; // file type
		$uploadfile = $uploaddir.'/'.$filename;

		$slugname = preg_replace('/\.[^.]+$/', '', basename($uploadfile));

		// if file exists
		if ( file_exists($uploadfile) ) {
			$count = "a";
			while ( file_exists($uploadfile) ) {
				$count++;
				if ( $typefile == 'image/png' ) { $exten = 'png'; }
				elseif ( $typefile == 'image/jpg' ) { $exten = 'jpg'; }
				elseif ( $typefile == 'image/jpeg' ) { $exten = 'jpg'; }
				elseif ( $typefile == 'image/gif' ) { $exten = 'gif'; }
				$uploadfile = $uploaddir.'/'.$slugname.'-'.$count.'.'.$exten;
			}
		} // end if file exist

		// if the file is uploaded, do the insert
		if (move_uploaded_file($file['tmp_name'], $uploadfile)) {
			$slugname = preg_replace('/\.[^.]+$/', '', basename($uploadfile)); // defining image slug again to make it matching the file name
			$attachment = array(
				'post_mime_type' => $typefile,
				'post_title' => $earner_name,
				'post_content' => '',
				'post_status' => 'inherit'
			);

			$attach_id = wp_insert_attachment( $attachment, $uploadfile, $earner_id );
			// you must first include the image.php file
			// for the function wp_generate_attachment_metadata() to work
			require_once(ABSPATH . "wp-admin" . '/includes/image.php');

			$attach_data = wp_generate_attachment_metadata( $attach_id, $uploadfile );
			wp_update_attachment_metadata( $attach_id,  $attach_data );
			
			add_post_meta($earner_id, "_thumbnail_id", $attach_id, TRUE);
			//$img_url = wp_get_attachment_url($attach_id);

		} // end if file is upload
	} // end if image has been uploaded

			$headers[] = 'From: '.$issuer_name. ' <' .$issuer_notifica_email. '>' . "\r\n";
			$headers[] = 'Sender: Sistema de emisión de badge de Ciudad Escuela <badges@ciudad-escuela.org>' . "\r\n";
			$headers[] = 'Reply-To:  '.$issuer_name. ' <' .$issuer_notifica_email. '>' . "\r\n";
			$headers[] = 'To: ' .$earner_name. ' <' .$to. '>' . "\r\n";

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
'Un saludo de '.$issuer_name.'.'
;
	$headers[] = 'From: '.$issuer_name. ' <' .$issuer_notifica_email. '>' . "\r\n";
	$headers[] = 'Sender: Sistema de emisión de badge de Ciudad Escuela <badges@ciudad-escuela.org>' . "\r\n";
	$headers[] = 'Reply-To:  '.$issuer_name. ' <' .$issuer_notifica_email. '>' . "\r\n";
	$headers[] = 'To: ' .$earner_name. ' <' .$to. '>' . "\r\n";
	// To send HTML mail, the Content-type header must be set, uncomment the following two lines
	//$headers[]  = 'MIME-Version: 1.0' . "\r\n";
	//$headers[] = 'Content-type: text/html; charset=utf-8' . "\r\n";
	wp_mail( $to, $subject, $message, $headers);

	// send notification mail to issuer
	$to = $issuer_notifica_email;
	$subject = "[badges] Solicitud de emisión de badge";
	$message = '
Hola ' .$issuer_name.','
. "\r\n\r\n" .
'un usuario ha solicitado uno de tus badge a través del sistema de emisión de Ciudad Escuela. Aquí puedes ver sus datos:'
. "\r\n\r\n" .
'+ Nombre del solicitante: ' .$earner_name.
"\r\n" .
'+ Dirección e-mail del solicitante: ' .$earner_mail.
"\r\n\r\n" .
'+ Actividad realizada: ' .$earner_actividad.
"\r\n" .
'+ Badge solicitado: ' .$earner_badge_tit.
"\r\n" .
'+ URL del material producido: ' .$earner_material.
"\r\n\r\n" .
'Si es la primera vez que recibes una solicitud de emisión de badge Ciudad Escuela puedes leer las siguientes instrucciones para gestionar esta solicitud:'.
"\r\n\r\n" .
'1. Comprueba los datos del solicitante y el material que ha generado, para asegurarte de que cumple los requisitos.'.
"\r\n" .
'2. Visita el panel de administración de Ciudad Escuela: puedes aprobar la solicitud pinchando sobre el botón azul "Publicar" que verás una vez hayas accedido al panel: http://ciudad-escuela.org/wp-admin/post.php?post=' .$earner_id. '&action=edit'.
"\r\n\r\n" .
'Si crees que el usuario no cumple alguno de los requisitos quizás quieras ponerte en contacto con él: ' .$earner_mail.
"\r\n\r\n" .
'Puedes escribirnos a Ciudad Escuela respondiendo a este correo sobre cualquier duda que tengas sobre el sistema de emisión.'.
"\r\n\r\n" .
'Un saludo del equipo de Ciudad Escuela.'
;
	$headers[] = 'From: Sistema de emisión de badge de Ciudad Escuela <badges@ciudad-escuela.org>' . "\r\n";
	$headers[] = 'Reply-To: Sistema de emisión de badge de Ciudad Escuela <badges@ciudad-escuela.org>' . "\r\n";
	$headers[] = 'To: <' .$to. '>' . "\r\n";
	// To send HTML mail, the Content-type header must be set, uncomment the following two lines
	//$headers[]  = 'MIME-Version: 1.0' . "\r\n";
	//$headers[] = 'Content-type: text/html; charset=utf-8' . "\r\n";
	wp_mail( $to, $subject, $message, $headers);

	wp_redirect( $location );
	exit;

} // end insert earner data in database

// contact form
function quincem_contact_form() {

	$action = get_permalink();

	$form_out = "
<form id='quincem-form-contact' method='post' action='" .$action. "'>
<div class='form-group'>
	<label for='quincem-form-contact-name'>Nombre</label>
	<input class='form-control req' type='text' value='' name='quincem-form-contact-name' />
</div>

<div class='form-group'>
	<label for='quincem-form-contact-mail' class='control-label'>Dirección de correo electrónico</label>
	<input class='form-control req' type='text' value='' name='quincem-form-contact-mail' />
</div>

<div class='form-group'>
	<label for='quincem-form-contact-message' class='control-label'>Mensaje</label>
	<textarea class='form-control req' name='quincem-form-contact-message'></textarea>
</div>
<div class='checkbox'>
	<label>
		<input type='checkbox' name='quincem-form-contact-copy' value='true' /> Recibir una copia del mensaje en mi buzón de correo.
	</label>
</div>
<div class='form-group'>
	<input class='btn-cescuela' type='submit' value='Enviar' name='quincem-form-contact-submit' />
	<span class='help-block'><small><strong>Todos los campos son requeridos</strong>.</small></span>
    </div>
  </div>

</form>
";
	echo $form_out;

} // end contact form

// contact issuer
function quincem_contact_issuer($post) {

	// issuer data
	$issuer_name = get_the_title($post->ID);
	$issuer_mail = get_post_meta($post->ID,'_quincem_issuer_email',true);

	// messages and locations for redirection
	$perma = get_permalink();
	$location = $perma."?form=success";
	$error = "<div class='alert alert-danger'>
		<p>Uno o varios campos están vacíos o contienen algún caracter no permitido.</p>
		<p>Aségurate de que rellenaste todos los campos y únicamente utilizaste texto plano.</p>
		<p>En cualquier caso el formulario no se envió correctamente. Por favor, inténtalo de nuevo.</p>
	</div>";
	$success = "<div class='alert alert-success'><p>Tu mensaje ha sido enviado correctamente a este emisor.</p></div><a class='btn-cescuela' href='".$perma."'>Enviar otro mensaje a ".$issuer_name."</a>";

	if ( array_key_exists('form', $_GET) ) {
	if ( sanitize_text_field( $_GET['form']) == 'success' ){
		echo $success;
		return;
	}
	}

	if ( !array_key_exists('quincem-form-contact-submit', $_POST) ) {
		quincem_contact_form();
		return;

	} elseif ( sanitize_text_field( $_POST['quincem-form-contact-submit'] ) != 'Enviar' ) {
		quincem_contact_form();
		return;
	}

	// check if all fields have been filled
	// sanitize them all
	$sender_name = sanitize_text_field( $_POST['quincem-form-contact-name'] );
	$sender_mail = sanitize_email( $_POST['quincem-form-contact-mail'] );
	$sender_message = sanitize_text_field( $_POST['quincem-form-contact-message'] );

	if ( $sender_name == '' || $sender_mail == '' || $sender_message == '' ) {
		echo $error;
		quincem_contact_form();
		return;
	}

	// if everything ok, send message to issuer
	$to = $issuer_mail;
	$headers[] = 'From: '.$sender_name. ' <' .$sender_mail. '>' . "\r\n";
	$headers[] = 'Sender: Formulario de contacto de Ciudad Escuela <no-reply@ciudad-escuela.org>' . "\r\n";
	$headers[] = 'Reply-To:  '.$sender_name. ' <' .$sender_mail. '>' . "\r\n";
	$headers[] = 'To: ' .$issuer_name. ' <' .$to. '>' . "\r\n";
	$subject = "Mensaje desde el formulario de contacto de Ciudad Escuela";
	$message = 
'Hola ' .$issuer_name.','
. "\r\n\r\n" .
'el usuario '.$sender_name.' te ha enviado el siguiente mensaje desde el formulario de contacto de tu perfil en Ciudad Escuela ('.$perma.'):'
. "\r\n\r\n" .
$sender_message
. "\r\n\r\n" .
'para contestarle puedes darle a responder en este correo o escribirle a '.$sender_mail
. "\r\n\r\n" .
'Un saludo,'
. "\r\n" .
'El sistema autómatico de mensajería de Ciudad Escuela'
;
	wp_mail( $to, $subject, $message, $headers);

	// send copy to sender
	if ( sanitize_text_field( $_POST['quincem-form-contact-copy'] ) == true ) {
		$to = $sender_mail;
		$headers[] = 'From: Formulario de contacto de Ciudad Escuela <no-reply@ciudad-escuela.org>' . "\r\n";
		$headers[] = 'Sender: Formulario de contacto de Ciudad Escuela <no-reply@ciudad-escuela.org>' . "\r\n";
		$subject = "Copia del mensaje enviado al emisor ".$issuer_name;
		$message =
'Hola ' .$sender_name.','
. "\r\n\r\n" .
'el siguiente mensaje ha sido enviado al emisor '.$issuer_name.':'
. "\r\n\r\n" .
$sender_message;
		wp_mail( $to, $subject, $message, $headers);
	}

	wp_redirect( $location );
	exit;

} // contact issuer

// /////////////////////////
// Rest API
// works with wp-json plugin
// /////////////////////////

// Add earners post type to api
function quincem_api_post_type_support() {
	global $wp_post_types;

	$pt = 'earner';
	if( isset( $wp_post_types[ $pt ] ) ) {
		$wp_post_types[$pt]->show_in_rest = true;
		$wp_post_types[$pt]->rest_base = $pt;
		$wp_post_types[$pt]->rest_controller_class = 'WP_REST_Posts_Controller';
	}
}

// Grab badge earners 
function quincem_api_badge_earners( $data ) {
	$earners = get_posts( array(
		'posts_per_page' => -1,
		'post_type' => 'earner',
//		'post_type' => 'badge',
//		'p' => $data['id']
		'meta_query' => array(
			array(
				'key' => '_quincem_earner_badge',
				'value' => $data['id'],
				'compare' => '='
			)
		)
	));

	if ( empty( $earners ) ) {
		return new WP_Error( 'no_badge', 'Invalid badge',array('status' => 404 ) );
	}

	$earners_filtered = array();
	foreach ( $earners as $e ) {
		if ( has_post_thumbnail( $e->ID ) ) {
			$avatar = wp_get_attachment_image_src( get_post_thumbnail_id($e->ID), 'bigicon' );
			$earners_filtered[$e->ID]['avatar'] = $avatar[0];
		} else { $earners_filtered[$e->ID]['avatar'] = QUINCEM_BLOGTHEME. "/images/quincem-earner-avatar.png"; }
		$earners_filtered[$e->ID]['name'] = get_post_meta( $e->ID, '_quincem_earner_name', true );
		$earners_filtered[$e->ID]['date'] = $e->post_date;
		$earners_filtered[$e->ID]['material'] = get_post_meta( $e->ID, '_quincem_earner_material', true );
		$earners_filtered[$e->ID]['actividad'] = get_post_meta( $e->ID, '_quincem_earner_actividad', true );

	}

	return $earners_filtered;
}
add_action( 'rest_api_init', function () {
	register_rest_route( 'quincem/v1', '/badge/(?P<id>\d+)/earners', array(
		'methods' => 'GET',
		'callback' => 'quincem_api_badge_earners',
//		'args' => array(
//			'id' => array(
//			'validate_callback' => 'is_numeric'
//			),
//		),
	));
} );

?>
