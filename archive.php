<?php get_header(); ?>

<?php
if ( have_posts() ) {
$pt = $wp_query->query_vars['post_type'];
$pt_object = get_post_type_object( $pt );
$tit = $pt_object->label;
?>
		<header>
		<h1><?php echo $tit; ?></h1>
		</header>
		<div class="row">
			<section>
<?php while ( have_posts() ) : the_post(); ?>
				<div>
					<h2><?php the_title(); ?></h2>
					<?php the_post_thumbnail($post->ID,'thumbnail',array('class' => 'img-responsive')); ?>
					<?php the_excerpt(); ?>
				</div>
<?php endwhile; ?>
			</section>
		</div>
<?php } ?>
<?php get_footer(); ?>
