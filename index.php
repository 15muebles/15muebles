<?php get_header(); ?>

<div class="container">
<header class="aligncenter">
	<div class="row hair">
		<div class="col-md-4 col-md-offset-3">
			<img class="img-responsive" src="<?php echo QUINCEM_BLOGTHEME; ?>/images/quincem-imago.png" alt="<?php echo QUINCEM_BLOGNAME. " | " . QUINCEM_BLOGDESC; ?>" />
			<h1 class="hideout"><?php echo QUINCEM_BLOGNAME ?></h1>
			<div class="hideout"><strong><?php echo QUINCEM_BLOGDESC ?></strong></div>
		</div>
	</div>
</header>
<section class="aligncenter">
	<div class="row hair">
		<div class="rombo col-md-2 col-md-offset-2">
			<h2 class="rombo-tit">Descubre</h2>
			<p><small>Cada itinerario como una suerte de viaje de <strong>descubrimiento</strong>: lugares, prácticas, infraestructuras o formas de asociación que trabajan ya por una ciudad común.</small></p>
		</div>
		<div class="rombo col-md-2">
			<h2 class="rombo-tit">Aprende</h2>
			<p><small>Cada itinerario se compone a su vez de una serie de unidades de <strong>aprendizaje</strong>. Éstos son nuestros badges.</small></p>
		</div>
		<div class="rombo col-md-2">
			<h2 class="rombo-tit">Haz</h2>
			<p><small>La consecución de un badge requiere la participación en una serie de actividades. El denominador común en todas será que estaremos <strong>haciendo</strong> ciudad.</small></p>
		</div>
	</div>
	<div class="row hair">
		<div class="col-md-2 col-md-offset-4">
			<a class="border-band" href="">About</a>
		</div>
	</div>
	<div id ="patrocina" class="row">
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
// descubre, aprende, haz bands
$band_pts = array("itinerario","badge","actividad");
$band_ids = array("descubre","aprende","haz");
$band_bgs = array("#a8d4d4","#b8dbdb","#c7e3e3");
$band_cols = array(5,5,4);
$band_tits = array("Descubre","Aprende","Haz");
$band_subtits = array("Itinerarios","Badges","Actividades");
$band_descs = array(
		"En 15Muebles hemos usado los Open Badges para diseñar cinco itinerarios pedagógicos en torno a la ciudad. Con los itinerarios queremos mostrar otros relatos sobre la ciudad posible. Ensayamos imaginarios, herramientas, juegos y lenguajes urbanos que nos gustaría sirvieran para empoderar otras políticas. Así planteamos cada itinerario como una suerte de viaje de descubrimiento: a lugares, prácticas, recursos, infraestructuras o formas de asociación que trabajan ya por una ciudad común.",
		"Description...",
		"Description...");

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

		<div id="<?php echo $band_ids[$band_count] ?>" class="container-full">
		<div class="container">
		<section>
			<header class="sec-header">
				<div class="sec-tit">
					<h2><?php echo $band_tits[$band_count]; ?></h2>
					<div class="sec-subtit"><?php echo $band_subtits[$band_count]; ?></div>
				</div>
				<div class="sec-desc"><p><?php echo $band_descs[$band_count]; ?></p></div>
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
		</div><!-- .container -->
		</div><!-- .container-full -->

	<?php } // end if have posts
	$band_count++;

}
// END bands loop
?>


<?php get_footer(); ?>
