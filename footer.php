<?php
if ( is_home() ) { $epi_class = "home"; }
elseif ( is_single() ) { $epi_class = $wp_query->query_vars['post_type']; }
elseif ( is_page_template('about.php') ) { $epi_class = "about"; }
elseif ( is_404() ) { $epi_class = "e404"; }
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
	<div class="row explica">
	<div class="col-md-8 col-md-offset-1">
		<div><p><strong>El contenido de Ciudad Escuela</strong>, a menos que se indique lo contrario, está disponible para su uso bajo las condiciones de la licencia <a href="http://creativecommons.org/licenses/by-sa/4.0/deed.es_ES">Creative Commons Reconocimiento-CompartirIgual 4.0 Internacional</a>. <strong>El código de la web de Ciudad Escuela</strong> está igualmente disponible para su uso bajo las condiciones de una licencia <a href="https://github.com/skotperez/15muebles/blob/master/LICENSE">GPL2</a>, y puede <a href="https://github.com/skotperez/15muebles">descargarse libremente</a>. La web de Ciudad Escuela funciona usando <a href="http://wordpress.org">WordPress</a>.</p></div>
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
