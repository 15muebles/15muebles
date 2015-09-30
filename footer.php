<?php
if ( is_front_page() ) { $epi_class = "home"; }
elseif ( is_single() ) { $epi_class = get_post_type(); }
elseif ( is_page() ) { $epi_class = "about"; }
elseif ( is_404() ) { $epi_class = "e404"; }
else { $epi_class = "post"; }
?>

<footer class="aligncenter">
<div id="epi" class="container-full epi-<?php echo $epi_class; ?>">

<div class="container">
	<div class="row">
		<div class="col-md-2 col-md-offset-5 col-sm-4 col-sm-offset-4 col-xs-4 col-xs-offset-4">
			<img class="img-responsive" src="<?php echo QUINCEM_BLOGTHEME; ?>/images/quincem-logo-detalle.png" alt="<?php echo QUINCEM_BLOGNAME; ?>" />
		</div>
	</div><!-- .row -->
</div><!-- .container -->
</div><!-- .container-full -->

<div id="trasepi" class="container-full">
<div class="container">
	<div class="row patrocina">
	<div class="col-md-4 col-md-offset-4">
		<ul class="list-inline">
			<li><img class="patrocina-sec" src="<?php echo QUINCEM_BLOGTHEME; ?>/images/quincem-mozilla.png" alt="Mozilla Foundation" /></li>
			<li><img class="patrocina-main" src="<?php echo QUINCEM_BLOGTHEME; ?>/images/quincem-15muebles.png" alt="Proyecto 15 muebles" /></li>
			<li><img class="patrocina-sec" src="<?php echo QUINCEM_BLOGTHEME; ?>/images/quincem-mncars.png" alt="Museo Nacional Centro de Arte Reina Sofia" /></li>
		</ul>
	</div>
	</div><!-- .row -->
	<div class="row explica">
	<div class="col-md-8 col-md-offset-2">
		<div><p><strong>El contenido de Ciudad Escuela</strong>, a menos que se indique lo contrario, está disponible para su uso bajo las condiciones de la licencia <a href="http://creativecommons.org/licenses/by-sa/4.0/deed.es_ES">Creative Commons Reconocimiento-CompartirIgual 4.0 Internacional</a>. <strong>El código de la web de Ciudad Escuela</strong> está igualmente disponible para su uso bajo las condiciones de una licencia <a href="https://github.com/skotperez/15muebles/blob/master/LICENSE">GPL2</a>, y puede <a href="https://github.com/skotperez/15muebles">descargarse libremente</a>. La web de Ciudad Escuela funciona usando <a href="http://wordpress.org">WordPress</a>.</p>
			<p><a href="http://openbadges.org"><img src="<?php echo QUINCEM_BLOGTHEME; ?>/images/quincem-openbadges.png" alt="Openbadges -- Mozilla Foundation" /></a></p>
		</div>
	</div>
	</div><!-- .row -->
</div><!-- .container -->

</div><!-- .container-full -->
</footer>

<?php
// get number of queries
//echo "<div style='display: none;'>".get_num_queries()."</div>";
wp_footer(); ?>

</body><!-- end body as main container -->
</html>
