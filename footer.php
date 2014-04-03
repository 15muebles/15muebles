<?php
if ( is_home() ) { $epi_bg = "#c7e3e3"; }
else { $epi_bg = "#c7e3e3"; }
?>

<div id="epi" class="container-full" style="background-color: <?php echo $epi_bg; ?>;">

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
			<li><img src="<?php echo QUINCEM_BLOGTHEME; ?>/images/quincem-mozilla.png" alt="Mozilla Foundation" /></li>
			<li><img src="<?php echo QUINCEM_BLOGTHEME; ?>/images/quincem-mncars.png" alt="Museo Nacional Centro de Arte Reina Sofia" /></li>
			<li><img src="<?php echo QUINCEM_BLOGTHEME; ?>/images/quincem-15muebles.png" alt="Proyecto 15 muebles" /></li>
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
