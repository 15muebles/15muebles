<?php get_header(); ?>

<section id="top" class="aligncenter">
	<div class="row">
		<div class="col-md-4 col-md-offset-3">
			<img class="img-responsive" src="<?php echo QUINCEM_BLOGTHEME; ?>/images/quincem-imago.png" alt="<?php echo QUINCEM_BLOGNAME. " | " . QUINCEM_BLOGDESC; ?>" />
		</div>
	</div>
	<div class="row">
		<div class="bg-rombo col-md-2 col-md-offset-2">
			<h2>Descubre</h2>
			<p>Textito</p>
		</div>
		<div class="bg-rombo col-md-2">
			<h2>Descubre</h2>
			<p>Textito</p>
		</div>
		<div class="bg-rombo col-md-2">
			<h2>Descubre</h2>
			<p>Textito</p>
		</div>
	</div>
	<div class="row">
		<div class="col-md-2 col-md-offset-4">
			<a href="">About</a>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4 col-md-offset-3">
			<ul class="list-inline">
				<li>Patrocina</li>
				<li>Patrocina</li>
				<li>Patrocina</li>
			</ul>
		</div>
	</div>
</section>


<?php
// descubre, aprende, haz bands
$band_pts = array("itinerario","badge","actividad");
$band_ids = array("descubre","aprende","haz");
$band_bgs = array("#a8d4d4","#b8dbdb","#c7e3e3");
$band_cols = array(5,5,4);
$band_tits = array("Descubre","Aprende","Haz");
$band_descs = array("Description...","Description...","Description...");

// BEGIN bands loop
$band_count = 0;
foreach ( $band_pts as $band_pt ) {

	$col = intval(10 / $band_cols[$band_count]);

	$args = array(
		'posts_per_page' => -1,
		'post_type' => $band_pt,
	);
	$the_query = new WP_Query( $args );

	if ( $the_query->have_posts() ) { ?>

		<section id="<?php echo $band_ids[$band_count] ?>">
			<header>
				<h2 class="sec-tit"><?php echo $band_tits[$band_count]; ?></h2>
				<div class="sec-desc"><?php echo $band_descs[$band_count]; ?></div>
			</header>
		<?php
		// BEGIN *THIS* band loop
		$thisband_count = 0;
		while ( $the_query->have_posts() ) : $the_query->the_post();
			if ( $thisband_count == 0 ) { echo '<div class="row">'; }
			if ( $thisband_count == $band_cols[$band_count] ) { $thisband_count == 0; }
			$thisband_count++;
		?>

<div class="aligncenter col-md-<?php echo $col ?>">
<div class="thumbnail">
	<img data-src="holder.js/300x200" alt="..." />
	<div class="caption">
		<h3><?php the_title(); ?></h3>
		<p><?php the_excerpt(); ?></p>
		<p><a href="#" class="btn btn-primary" role="button">Button</a> <a href="#" class="btn btn-default" role="button">Button</a></p>
	</div>
</div>
</div>
		<?php
		if ( $thisband_count == $band_cols[$band_count] ) { echo "</div>"; }
		endwhile;
		// END *THIS* band loop

		if ( $thisband_count != $band_cols[$band_count] ) { echo "</div>"; }
		?>
		</section>

	<?php } // end if have posts
	$band_count++;

}
// END bands loop
?>


<?php get_footer(); ?>
