<?php get_header();

	// pt vars
	//global $pt;
	$pt = $wp_query->query_vars['post_type'];
	$band_tit = $wp_post_types[$pt]->labels->parent;
	$band_subtit = $wp_post_types[$pt]->labels->name;
	$band_desc = $wp_post_types[$pt]->description;
	$band_class = $pt;

	$col_desktop = 4;
	$col_tablet = 4;

?>

<div id="<?php echo $band_class; ?>" class="container-full">
<div class="container">
	<div class="sec-header row hair">
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


<?php // related content
if ( $pt == 'itinerario' ) {
	$rel_tit = "Badges del itinerario";
	$rel_array = get_post_meta( $post->ID, '_quincem_badges', false );
	$rel_ids = implode(",",$rel_array[0]);
	$args = array(
		'post_type' => 'badge',
		'posts_per_page' => -1,
		'orderby' => 'title',
		'include' => $rel_ids
	);

} elseif ( $pt == 'badge' ) {
	$rel_tit = "Actividades posibles";
	$rel_array = get_post_meta( $post->ID, '_quincem_actividades', false );
	$rel_ids = implode(",",$rel_array[0]);
	$args = array(
		'post_type' => 'actividad',
		'posts_per_page' => -1,
		'orderby' => 'title',
		'include' => $rel_ids
	);

} elseif ( $pt == 'actividad' ) {
	$rel_tit = "Badges de la actividad";
	$rel_array = get_post_meta( $post->ID, '_quincem_badges', false );
	$rel_ids = implode(",",$rel_array[0]);
	$args = array(
		'post_type' => 'badge',
		'posts_per_page' => -1,
		'orderby' => 'title',
		'include' => $rel_ids
	);

}

$rel_items = get_posts($args);
if ( count($rel_items) > 0 ) { ?>

	<section class="col-md-6 col-sm-6">
		<header class="row hair">
			<h2 class="col-md-10 rel-tit"><?php echo $rel_tit; ?></h2>
		</header>
		<div class="mosac row hair">

			<?php // related content loop
			foreach ( $rel_items as $rel ) {
				include "loop-related.php";
			} ?>

		</div><!-- .mosac .row .hair -->
	</section><!-- .row -->

<?php } // end if related content
?>

</div><!-- .container -->
</div><!-- .container-full -->

<?php get_footer(); ?>
