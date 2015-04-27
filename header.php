<!DOCTYPE html>

<html <?php language_attributes(); ?>>

<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />

<title>
<?php
	/* From twentyeleven theme
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	echo QUINCEM_BLOGNAME;

	// Add the blog description for the home/front page.
	if ( QUINCEM_BLOGDESC && ( is_home() || is_front_page() ) )
		echo " | " . QUINCEM_BLOGDESC;

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | Página ' . max( $paged, $page );

	?>
</title>

<?php
// metatags generation
if ( is_single() || is_page() && !is_front_page() ) {
	$metadesc = $post->post_excerpt;
	if ( $metadesc == '' ) { $metadesc = $post->post_content; }
	$metadesc = wp_strip_all_tags($post->post_content);
	$metadesc = strip_shortcodes( $metadesc );
	$metadesc_fb = substr( $metadesc, 0, 297 );
	$metadesc_tw = substr( $metadesc, 0, 200 );
	$metadesc = substr( $metadesc, 0, 154 );
	$metatit = $post->post_title;
	$metatype = "article";
	$img_id = get_post_thumbnail_id();
	if ( $img_id != '' ) {
		$img_array = wp_get_attachment_image_src($img_id,'large', true);
		$metaimg = $img_array[0];
	} else {
		$metaimg = "http://ciudad-escuela.org/wp-content/themes/montera34/screenshot.png";
	}
	$metaperma = get_permalink();

} elseif ( is_category() ) {
	$term =	$wp_query->queried_object;
	$metadesc = $term->description;
	if ( $metadesc == '' ) { $metadesc = "Contenidos en la categoría ".$term->name; }
	$metadesc_fb = substr( $metadesc, 0, 297 );
	$metadesc_tw = substr( $metadesc, 0, 200 );
	$metatit = $term->name;
	$metatype = "blog";
	$metaperma = "http://ciudad-escuela.org/seccion/".$term->slug;

} elseif ( is_tag() ) {
	$term =	$wp_query->queried_object;
	$metadesc = "Contenidos en con la etiqueta ".$term->name;
	$metadesc_fb = substr( $metadesc, 0, 297 );
	$metadesc_tw = substr( $metadesc, 0, 200 );
	$metatit = $term->name;
	$metatype = "blog";
	$metaperma = "http://ciudad-escuela.org/etiqueta/".$term->slug;

} else {
	$metadesc = QUINCEM_BLOGDESC;
	$metadesc_tw = QUINCEM_BLOGDESC;
	$metadesc_fb = QUINCEM_BLOGDESC;
	$metatit = QUINCEM_BLOGNAME;
	$metatype = "website";
	$metaimg = "http://ciudad-escuela.org/wp-content/themes/15muebles/images/quincem-imago.png";
	$metaperma = QUINCEM_BLOGURL;
}
?>

<!-- generic meta -->
<meta content="15muebles" name="author" />
<meta name="title" content="<?php echo $metatit ?>" />
<meta content="<?php echo $metadesc ?>" name="description" />
<meta content="Pedagogía abierta, pedagogía urbana, pedagogía opensource, pedagogía urbana abierta, escuela abierta, Ciudad Escuela, 15muebles, Open Badges, aprendizaje no reglado, Madrid, Zuloark, Basurama, Domenico Di Siena, Alberto Corsín, Adolfo Estalella, Alfonso Sánchez Uzábal" name="keywords" />
<!-- facebook meta -->
<meta property="og:title" content="<?php echo $metatit ?>" />
<meta property="og:type" content="<?php echo $metatype ?>" />
<meta property="og:description" content="<?php echo $metadesc_fb ?>" />
<meta property="og:url" content="<?php echo $metaperma ?>" />
<meta property="og:image" content="<?php echo $metaimg; ?>" />
<!-- twitter meta -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@15muebles">
<meta name="twitter:title" content="<?php echo $metatit ?>">
<meta name="twitter:description" content="<?php echo $metadesc_tw ?>">
<meta name="twitter:creator" content="@15muebles">
<meta name="twitter:image:src" content="<?php echo $metaimg ?>">

<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="profile" href="http://gmpg.org/xfn/11" />

<link rel="alternate" type="application/rss+xml" title="<?php echo QUINCEM_BLOGNAME; ?> RSS Feed suscription" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="<?php echo QUINCEM_BLOGNAME; ?> Atom Feed suscription" href="<?php bloginfo('atom_url'); ?>" /> 
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php
//if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
wp_head(); ?>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />

</head>

<?php // better to use body tag as the main container ?>
<body <?php body_class(); ?>>

<?php
// navbar links
if ( !is_front_page() ) { $link_prefix = QUINCEM_BLOGURL. "/"; }
else { $link_prefix = ""; }

// custom post types info
global $wp_post_types;

// descubre, aprende, haz bands
$band_pts = array("itinerario","badge","actividad");
$band_tits = array("Descubre","Aprende","Haz");
?>

<nav id="pre-navbar" class="navbar navbar-default navbar-fixed-top" role="navigation">
	<div class="container">
		<div class="navbar-header quincem-smooth">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#quincem-pre-navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?php echo $link_prefix; ?>#top" title="<?php echo QUINCEM_BLOGNAME; ?>"><img src="<?php echo QUINCEM_BLOGTHEME; ?>/images/quincem-logo.png" alt="<?php echo QUINCEM_BLOGNAME; ?>" /></a>
		</div>
		<div class="collapse navbar-collapse" id="quincem-pre-navbar-collapse">
			<ul id="navbar-main" class="nav navbar-nav quincem-smooth">
				<?php $nav_count = 0;
				foreach ( $band_pts as $pt ) {
					if ( is_single() && $wp_query->query_vars['post_type'] == $pt ) {
						echo "<li class='active'><a class='" .$pt. "' href='" .$link_prefix. "#" .$pt. "'>" .$band_tits[$nav_count]. "</a></li>";
					} else {
						echo "<li><a href='" .$link_prefix. "#" .$pt. "'>" .$band_tits[$nav_count]. "</a></li>";
					}
					$nav_count++;
				} ?>
			</ul>
			<ul id="navbar-sec" class="nav navbar-nav navbar-left">
				<?php if ( $wp_query->query_vars['pagename'] == 'about' ) { ?>
					<li class="active"><a class="border-band-black" href="">About</a></li>
				<?php } else { ?>
					<li><a class="border-band-black" href="/about">About</a></li>
				<?php } ?>
					<li><a href="/preguntas-frecuentes">FAQ</a></li>
					<li><a href="/blog">Blog</a></li>
			</ul>

			<ul id="navbar-third" class="nav navbar-nav navbar-right">
				<li class="navbar-socialb">
<div class="g-follow" data-annotation="bubble" data-height="20" data-href="https://plus.google.com/104536835218787209984" data-rel="publisher"></div>

<!-- Place this tag after the last widget tag. -->
<script type="text/javascript">
  window.___gcfg = {lang: 'es'};

  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/platform.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>

				</li>
			</ul>
		</div>
	</div>
</nav>
