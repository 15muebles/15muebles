<?php get_header();

$band_tit = $wp_query->query_vars['pagename'];
$band_desc = get_the_content(); ?>

<div id="blog" class="container-full">
<div class="container">
	<div class="row hair">
			<header class="col-sm-10"><h1 class="parent-tit"><?php echo $band_tit; ?></h1></header>
	</div>
	<div class="row">
		<div class="col-md-7 col-sm-7">
			<?php if ( have_posts() ) {
				while ( have_posts() ) : the_post();
					get_template_part('loop-blog');
				endwhile;
				// Previous/next page navigation.
				the_posts_pagination( array(
					'prev_text'          => '«',
					'next_text'          => '»',
					'before_page_number' => '',
				) );

			} else { echo "No hay contenido."; } ?>
		</div><!-- .col-md-6 .col-sm-7 -->

		<?php if ( is_active_sidebar( 'blog_right' ) ) : ?>
		<aside id="blog_right" class="widget-area col-md-3 col-sm-3" role="complementary">
			<?php dynamic_sidebar( 'blog_right' ); ?>
		</aside><!-- #blog_right -->
		<?php endif; ?>

	</div><!-- .row -->

</div><!-- .container -->
</div><!-- .container-full -->

<?php get_footer(); ?>
