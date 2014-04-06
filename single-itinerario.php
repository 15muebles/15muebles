<?php get_header();

	// pt vars
	//global $pt;
	$pt = $wp_query->query_vars['post_type'];
	$band_tit = $wp_post_types[$pt]->labels->parent;
	$band_subtit = $wp_post_types[$pt]->labels->name;
	$band_desc = $wp_post_types[$pt]->description;
	$band_class = $pt;

	$col_desktop = 3;
	$col_tablet = 3;

?>

<div id="<?php echo $band_class; ?>" class="container-full">
<div class="container">
	<div class="sec-header row">
		<div class="sec-tit">
			<h2><?php echo $band_tit; ?></h2>
			<div class="sec-subtit"><?php echo $band_subtit; ?></div>
		</div>
		<div class="sec-desc"><p><?php echo $band_desc; ?></p></div>
	</div><!-- .sec-header .row .hair-->
	<div class="mosac row hair">
	<?php while ( have_posts() ) : the_post(); ?>
	<?php include "loop-mosac.php";
	endwhile; ?>


<?php // related content: modulos actividades
$modulos_array = get_post_meta( $post->ID, '_quincem_badges', false );
$modulos_string = implode(",",$modulos_array[0]);
$args = array(
	'post_type' => 'badge',
	'posts_per_page' => -1,
	'orderby' => 'title',
	'include' => $modulos_string
);
$modulos = get_posts($args);
if ( count($modulos) > 0 ) { ?>

	<section class="col-md-6 col-md-offset-1 col-sm-8">
		<header class="row hair">
			<h2 class="col-md-10 rel-tit">Badges del itinerario</h2>
		</header>
		<div class="mosac row hair">

			<?php // related content loop
			foreach ( $modulos as $rel ) {
				//print_r($rel);
				include "loop-related.php";
			} ?>

		</div><!-- .mosac .row .hair -->
	</section><!-- .row -->

<?php } // end if related content
?>

</div><!-- .container -->
</div><!-- .container-full -->

<?php get_footer(); ?>
