<?php get_header(); ?>

<?php
if ( have_posts() ) { ?>
		<header>
		<h1><?php echo $wp_query->queried_object->name; ?></h1>
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
