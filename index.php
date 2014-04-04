<?php get_header();

// custom post types info
global $wp_post_types;

// descubre, aprende, haz bands
$band_pts = array("itinerario","badge","actividad");
$band_ids = array("descubre","aprende","haz");
$band_tits = array("Descubre","Aprende","Haz");
//$band_subtits = array("Itinerarios","Badges","Actividades");
//$band_bgs = array("#a8d4d4","#b8dbdb","#c7e3e3");
$band_cols = array(
	array(
		'desktop' => 5,
		'tablet' => 3
	),
	array(
		'desktop' => 5,
		'tablet' => 3
	),
	array(
		'desktop' => 5,
		'tablet' => 3
	),
);
$rombo_classes = array("rombo col-md-2 col-md-offset-2 col-sm-2 col-sm-offset-2","rombo col-md-2 col-sm-2","rombo col-md-2 col-sm-2");
?>

<div class="container">
<header class="aligncenter">
	<div class="row hair">
		<div class="col-md-4 col-md-offset-3 col-sm-4 col-sm-offset-3 col-xs-6 col-xs-offset-2">
			<img class="img-responsive" src="<?php echo QUINCEM_BLOGTHEME; ?>/images/quincem-imago.png" alt="<?php echo QUINCEM_BLOGNAME. " | " . QUINCEM_BLOGDESC; ?>" />
			<h1 class="hideout"><?php echo QUINCEM_BLOGNAME ?></h1>
			<div class="hideout"><strong><?php echo QUINCEM_BLOGDESC ?></strong></div>
		</div>
	</div>
</header>
<section class="aligncenter">
	<div class="row hair">
		<?php
		$rombo_count = 0;
		foreach ( $band_pts as $band_pt ) { ?>
		<div class="<?echo $rombo_classes[$rombo_count]; ?>">
			<h2 class="rombo-tit"><?php echo $band_tits[$rombo_count]; ?></h2>
			<p><small><?php echo $wp_post_types[$band_pt]->description; ?></small></p>
		</div>
		<?php $rombo_count++;
		} ?>
	</div>
	<div class="row hair">
		<div class="col-md-2 col-md-offset-4">
			<a class="border-band" href="">About</a>
		</div>
	</div>
	<div class="row patrocina">
		<div class="col-md-4 col-md-offset-3">
			<ul class="list-inline">
				<li><img src="<?php echo QUINCEM_BLOGTHEME; ?>/images/quincem-mozilla.png" alt="Mozilla Foundation" /></li>
				<li><img src="<?php echo QUINCEM_BLOGTHEME; ?>/images/quincem-mncars.png" alt="Museo Nacional Centro de Arte Reina Sofia" /></li>
				<li><img src="<?php echo QUINCEM_BLOGTHEME; ?>/images/quincem-15muebles.png" alt="Proyecto 15 muebles" /></li>
			</ul>
		</div>
	</div>
</section>
</div><!-- .container -->

</div><!-- .container-full -->

<?php
// BEGIN bands loop
$band_count = 0;
foreach ( $band_pts as $band_pt ) {

	// pt vars
	$band_desc = $wp_post_types[$band_pt]->description;
	$band_subtit = $wp_post_types[$band_pt]->labels->name;

	//$col = intval(10 / $band_cols[$band_count]);
	$col_desktop = intval(10 / $band_cols[$band_count]['desktop']);
	$col_tablet = intval(10 / $band_cols[$band_count]['tablet']);

	// loop args
	if ( $band_pt == 'actividad' ) {
		$current = time();
		$args = array(
			'posts_per_page' => -1,
			'post_type' => $band_pt,
			'orderby' => 'meta_value_num',
			'meta_key' => '_quincem_date_begin',
			'order' => 'ASC',
			'meta_query' => array(
				array(
					'key' => '_quincem_date_end',
					'value' => $current,
					'compare' => '>'
				)
			)
		);

	} else {
		$args = array(
			'posts_per_page' => -1,
			'post_type' => $band_pt,
		);

	}

	$the_query = new WP_Query( $args );

	if ( $the_query->have_posts() ) { ?>

		<div id="<?php echo $band_ids[$band_count] ?>" class="container-full">
		<div class="container">
		<section>
			<header class="sec-header">
				<div class="sec-tit">
					<h2><?php echo $band_tits[$band_count]; ?></h2>
					<div class="sec-subtit"><?php echo $band_subtit; ?></div>
				</div>
				<div class="sec-desc"><p><?php echo $band_desc; ?></p></div>
			</header>
			<div class="mosac">
		<?php
		// BEGIN *THIS* band loop
//		$thisband_count = 0;
		while ( $the_query->have_posts() ) : $the_query->the_post();
//			if ( $thisband_count == 0 || $thisband_count == $band_cols[$band_count]['desktop'] ) { $thisband_count = 0; echo '<div class="row">';  }
			$thisband_count++;

			include "loop-mosac.php";

//			if ( $thisband_count == $band_cols[$band_count]['desktop'] ) { echo "</div>"; }
		endwhile;
		// END *THIS* band loop

//		if ( $thisband_count != $band_cols[$band_count]['desktop'] ) { echo "</div>"; }
		?>
			</div><!-- .mosac -->
		</section>
		</div><!-- .container -->
		</div><!-- .container-full -->

	<?php } // end if have posts
	$band_count++;

}
// END bands loop

?>


<?php get_footer(); ?>
