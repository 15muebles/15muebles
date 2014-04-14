<?php
/* Template name: About */
get_header();

if ( have_posts() ) {
while ( have_posts() ) : the_post();

	// containers
	$pages_loop = "";
	$pages_nav = "<ul class='list-unstyled quincem-smooth'>";

	// children pages query
	$args = array(
		'post_type' => 'page',
		'orderby' => 'menu_order',
		'order' => 'ASC',
		'post_parent' => $post->ID,
		'posts_per_page' => -1
	);
	$children = get_posts($args);	

	// parent page
	$parent_slug = $wp_query->query_vars['pagename'];
	$parent_tit = get_the_title();
	$pages_nav .= "<li><a href='#" .$parent_slug. "'>" .$parent_tit. "</a></li>";

	// children pages
	if( count($children) > 0 ) {
		foreach ( $children as $child ) {
			//print_r($child);
			$page_slug = $child->post_name;
			$page_tit = "<h2 class='child-tit'>" .$child->post_title. "</h2>";
			$page_desc = apply_filters( 'the_content', $child->post_content );
			include "loop-about.php";

			// navbar
			$pages_nav .= "<li><a href='#" .$page_slug. "'>" .$child->post_title. "</a></li>";

		} // end loop children

		$pages_nav .= "</ul>";
	} else {
		$pages_nav = "";
	} //end if children pages
	?>

<div id="<?php echo $parent_slug ?>" class="container-full">
<div class="container">
	<header class="row">
		<div class="col-md-10 col-sm-10">
			<h1 class="parent-tit"><?php echo $parent_tit; ?></h1>
		</div>
	</header>

	<section class="row page-desc">
		<div class="col-md-6 col-sm-7">
			<?php the_content(); ?>
		</div>
		<nav id="about-nav" class="col-md-4 col-sm-3 hidden-xs">
			<?php echo $pages_nav; ?>
		</nav>
	</section><!-- #<?php echo $parent_slug; ?> -->

</div><!-- .container -->
</div><!-- .container-full -->


<?php endwhile;
} // end if posts

echo $pages_loop;
?>

<?php get_footer(); ?>
