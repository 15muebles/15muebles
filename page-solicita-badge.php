<?php ob_start();
/* Template name: Solicita badge */
get_header();

if ( have_posts() ) {
while ( have_posts() ) : the_post();

	// parent page
	$parent_slug = $wp_query->query_vars['pagename'];
	$parent_tit = get_the_title();

?>

<div id="<?php echo $parent_slug ?>" class="container-full">
<div class="container">
	<header class="row">
		<div class="col-md-12 col-sm-12">
			<h1 class="parent-tit"><?php echo $parent_tit; ?></h1>
		</div>
	</header>

	<section class="row page-desc">
		<div class="col-md-8 col-sm-9">
			<?php the_content();
			quincem_insert_earner();
			?>
		</div>
	</section><!-- #<?php echo $parent_slug; ?> -->

</div><!-- .container -->
</div><!-- .container-full -->


<?php endwhile;
} // end if posts
?>

<?php get_footer(); ?>
