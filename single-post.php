<?php get_header();

while ( have_posts() ) : the_post(); ?>

<div id="blog" class="container-full">
<article id="post-<?php the_ID(); ?>" class="container">
	<div class="row">
		<div class="col-sm-7">
			<header><h1 class="parent-tit"><?php the_title() ?></h1></header>
			<?php get_template_part('loop-blog-single'); ?>
		</div><!-- .col-sm-7 -->

	</div><!-- .row -->

</article><!-- .container -->
</div><!-- .container-full -->

<?php endwhile;
get_footer(); ?>
