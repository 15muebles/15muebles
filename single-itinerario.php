<?php get_header(); ?>

<?php
while ( have_posts() ) : the_post();
?>

	<article>
		<header>
		<h1><?php the_title(); ?></h1>
		<?php the_post_thumbnail($post->ID,'thumbnail',array('class' => 'img-responsive')); ?>
		</header>

		<div class="row">
			<section>
			<div>
				<header><h2>Descripción</h2></header>
				<?php the_content(); ?>
			</div>
			</section>

		</div>
	</article>

<?php endwhile; ?>

<div>
	<header><h2>Módulos del itinerario</h2></header>
	<ul>
<?php // modulos actividades
$modulos_array = get_post_meta( $post->ID, '_quincem_modulos', false );
$modulos_string = implode(",",$modulos_aray);
$args = array(
	'post_type' => 'modulo',
	'posts_per_page' => -1,
	'orderby' => 'title',
	'include' => $modulos_string
);
$modulos = get_posts($args);
foreach ( $modulos as $modulo ) {
	echo "<li><a href='" .get_permalink($modulo->ID). "'>" .$modulo->post_title. "</a></li>";
}
?>
	</ul>

<?php get_footer(); ?>
