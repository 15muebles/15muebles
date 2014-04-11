<?php get_header();

// custom post types info
global $wp_post_types;

// descubre, aprende, haz bands
$band_pts = array("itinerario","badge","actividad");
$band_tits = array("Descubre","Aprende","Haz");
$rombo_classes = array("rombo col-md-2 col-md-offset-2 col-sm-2 col-sm-offset-2","rombo col-md-2 col-sm-2","rombo col-md-2 col-sm-2");
?>

<div id="top" class="container-full">
<div class="container">
<header class="aligncenter">
	<div class="row hair">
		<div class="col-md-4 col-md-offset-3 col-sm-4 col-sm-offset-3 col-xs-6 col-xs-offset-2">
			<img id="quincem-imago" src="<?php echo QUINCEM_BLOGTHEME; ?>/images/quincem-imago.png" alt="<?php echo QUINCEM_BLOGNAME. " | " . QUINCEM_BLOGDESC; ?>" />
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
			<h2 class="rombo-tit quincem-smooth"><a href="#<?php echo $band_pt; ?>"><?php echo $wp_post_types[$band_pt]->labels->parent; ?></a></h2>
			<p><small><?php echo $wp_post_types[$band_pt]->description; ?></small></p>
		</div>
		<?php $rombo_count++;
		} ?>
	</div>
	<div class="row hair">
		<div class="col-md-2 col-md-offset-4">
			<a class="border-band-white" href="">About</a>
		</div>
	</div>
	<div class="row patrocina">
		<div class="col-md-4 col-md-offset-3">
			<ul class="list-inline">
				<li><img class="patrocina-sec" src="<?php echo QUINCEM_BLOGTHEME; ?>/images/quincem-mozilla.png" alt="Mozilla Foundation" /></li>
				<li><img class="patrocina-main" src="<?php echo QUINCEM_BLOGTHEME; ?>/images/quincem-15muebles.png" alt="Proyecto 15 muebles" /></li>
				<li><img class="patrocina-sec" src="<?php echo QUINCEM_BLOGTHEME; ?>/images/quincem-mncars.png" alt="Museo Nacional Centro de Arte Reina Sofia" /></li>
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
	$band_tit = $wp_post_types[$band_pt]->labels->parent; 
	$band_subtit = $wp_post_types[$band_pt]->labels->name;
	$band_desc = $wp_post_types[$band_pt]->description;

	// IF ITINERARIOS OR BADGES
	if ( $band_pt != 'actividad' ) {

		// loop args
		if ( $band_pt == 'badge' )  {
		$args = array(
			'posts_per_page' => -1,
			'post_type' => $band_pt,
			'orderby' => 'title',
			'order' => 'ASC',
		);

		} elseif ( $band_pt == 'itinerario' ) {
		$args = array(
			'posts_per_page' => -1,
			'post_type' => $band_pt,
			'orderby' => 'menu_order',
			'order' => 'ASC',
		);
		}

		$the_query = new WP_Query( $args );
		if ( $the_query->have_posts() ) { ?>

		<div id="<?php echo $band_pt; ?>" class="container-full">
		<div class="container">
		<section>
			<header class="sec-header row hair">
			<div class="col-md-10 col-sm-10">
				<div class="sec-tit">
					<h2><?php echo $band_tit; ?></h2>
					<div class="sec-subtit"><?php echo $band_subtit; ?></div>
				</div>
				<div class="sec-desc"><p><?php echo $band_desc; ?></p></div>
			</div>
			</header><!-- .sec-header .row .hair-->
			<div class="mosac row hair">
			<?php
			// BEGIN *THIS* band loop
			$tablet_count = 0;
			$desktop_count = 0;
			while ( $the_query->have_posts() ) : $the_query->the_post();
				if ( $tablet_count == 3 ) { $tablet_count = 0; echo '<div class="clearfix visible-sm"></div>';  }
				if ( $desktop_count == 5 ) { $desktop_count = 0; echo '<div class="clearfix visible-md visible-lg"></div>';  }
				$tablet_count++; $desktop_count++;
				include "loop-mosac.php";

			endwhile;
			// END *THIS* band loop
			?>
			</div><!-- .mosac .row .hair -->

		</section>
		</div><!-- .container -->
		</div><!-- .container-full -->

		<?php } // end if have posts


	// ACTIVIDADES
	} elseif ( $band_pt == 'actividad' ) {
		$current = time();
		// future actividades
		$act_tits[] = "Próximas";
		$act_args[] = array(
			'posts_per_page' => -1,
			'post_type' => $band_pt,
			'orderby' => 'meta_value_num',
			'meta_key' => '_quincem_date_begin',
			'order' => 'ASC',
			'meta_query' => array(
				array(
					'key' => '_quincem_date_begin',
					'value' => $current,
					'compare' => '>'
				)
			)
		);
		// current actividades
		$act_tits[] = "En curso";
		$act_args[] = array(
			'posts_per_page' => -1,
			'post_type' => $band_pt,
			'orderby' => 'meta_value_num',
			'meta_key' => '_quincem_date_begin',
			'order' => 'ASC',
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key' => '_quincem_date_begin',
					'value' => $current,
					'compare' => '<'
				),
				array(
					'key' => '_quincem_date_end',
					'value' => $current,
					'compare' => '>'
				)
			)
		);
		// past actividades
		$act_tits[] = "Pasadas";
		$act_args[] = array(
			'posts_per_page' => -1,
			'post_type' => $band_pt,
			'orderby' => 'meta_value_num',
			'meta_key' => '_quincem_date_begin',
			'order' => 'ASC',
			'meta_query' => array(
				array(
					'key' => '_quincem_date_end',
					'value' => $current,
					'compare' => '<'
				)
			)
		); ?>


		<div id="<?php echo $band_pt; ?>" class="container-full">
		<div class="container">
			<header class="sec-header row hair">
			<div class="col-md-10 col-sm-10">
				<div class="sec-tit">
					<h2><?php echo $band_tit; ?></h2>
					<div class="sec-subtit"><?php echo $band_subtit; ?></div>
				</div>
				<div class="sec-desc"><p><?php echo $band_desc; ?></p></div>
			</div>
			</header><!-- .sec-header .row .hair-->

			<?php // actividades loops
			$act_count = 0;
			foreach ( $act_args as $args ) {

				$the_query = new WP_Query( $args );
				if ( $the_query->have_posts() ) { ?>

				<section>
				<div class="row">
					<header class="col-md-10 col-sm-10">
					<h3 class="sec-part-tit"><?php echo $act_tits[$act_count]; ?></h3>
					</header>
				</div><!-- .row .hair -->
				<div class="mosac row hair">
				<?php $tablet_count = 0;
				$desktop_count = 0;
				while ( $the_query->have_posts() ) : $the_query->the_post();
					if ( $tablet_count == 3 ) { $tablet_count = 0; echo '<div class="clearfix visible-sm"></div>';  }
					if ( $desktop_count == 5 ) { $desktop_count = 0; echo '<div class="clearfix visible-md visible-lg"></div>';  }
					$tablet_count++; $desktop_count++;
					include "loop-mosac.php";

				endwhile; ?>

				</div><!-- .mosac .row .hair -->
				</section>

			<?php 	} // end if have posts
				$act_count++;
			} // end actividades loop ?>

		</div><!-- .container -->
		</div><!-- .container-full -->

	<?php } // END if ACTIVIDADES

	$band_count++;
}
// END bands loop
?>

<?php get_footer(); ?>
