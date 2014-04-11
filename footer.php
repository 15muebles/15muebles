<?php
if ( is_home() ) { $epi_class = "home"; }
elseif ( is_single() ) { $epi_class = $wp_query->query_vars['post_type']; }
elseif ( is_page_template('about.php') ) { $epi_class = "about"; }
?>

<div id="epi" class="container-full epi-<?php echo $epi_class; ?>">

<div class="container">
<footer class="aligncenter">
	<div class="row hair">
		<div class="col-md-2 col-md-offset-4 col-sm-2 col-sm-offset-4 col-xs-4 col-xs-offset-3">
			<img class="img-responsive" src="<?php echo QUINCEM_BLOGTHEME; ?>/images/quincem-logo-detalle.png" alt="<?php echo QUINCEM_BLOGNAME; ?>" />
		</div>
	</div><!-- .row -->
	<div class="row patrocina">
	<div class="col-md-4 col-md-offset-3">
		<ul class="list-inline">
			<li><img class="patrocina-sec" src="<?php echo QUINCEM_BLOGTHEME; ?>/images/quincem-mozilla.png" alt="Mozilla Foundation" /></li>
			<li><img class="patrocina-main" src="<?php echo QUINCEM_BLOGTHEME; ?>/images/quincem-15muebles.png" alt="Proyecto 15 muebles" /></li>
			<li><img class="patrocina-sec" src="<?php echo QUINCEM_BLOGTHEME; ?>/images/quincem-mncars.png" alt="Museo Nacional Centro de Arte Reina Sofia" /></li>
		</ul>
	</div>
	</div><!-- .row -->
</footer>
</div><!-- .container -->

</div><!-- .container-full -->

<?php
// get number of queries
//echo "<div style='display: none;'>".get_num_queries()."</div>";
wp_footer(); ?>

</body><!-- end body as main container -->
</html>
