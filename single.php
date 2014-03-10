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

			<section>
			<div>
				<header><h2>Cómo conseguir el badget</h2></header>
				<?php echo get_post_meta( $post->ID, '_quincem_badge_como', true ); ?>
			</div>
			</section>

			<section>
			<div>
				<header><h2>Material de trabajo</h2></header>
				<?php echo get_post_meta( $post->ID, '_quincem_material', true ); ?>
			</div>
			</section>
		</div>
	</article>

<?php endwhile; ?>

<div>
	<header><h2>Actividades</h2></header>
	<ul>
<?php // loop actividades
$actividades_array = get_post_meta( $post->ID, '_quincem_actividades', false );
$actividades_string = implode(",",$actividades_aray);
$args = array(
	'post_type' => 'actividad',
	'posts_per_page' => -1,
	'orderby' => 'title',
	'include' => $actividades_string
);
$actividades = get_posts($args);
foreach ( $actividades as $actividad ) {
	echo "<li><a href='" .get_permalink($actividad->ID). "'>" .$actividad->post_title. "</a></li>";
}
?>
	</ul>

<?php get_footer(); ?>
