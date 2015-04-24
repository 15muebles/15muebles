<?php
$img_size = "larger";
if ( has_post_thumbnail() ) {
	$blog_img = "<figure>".get_the_post_thumbnail($post->ID,$img_size,array('class' => 'img-responsive'))."</figure>";
} else { $blog_img = ""; }

$blog_date = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
	$blog_date = '<time class="entry-date published" datetime="%1$s">%2$s</time> (última modif. <time class="updated" datetime="%3$s">%4$s</time>)';
}
$blog_date = sprintf( $blog_date,
	esc_attr( get_the_date( 'c' ) ),
	get_the_date(),
	esc_attr( get_the_modified_date( 'c' ) ),
	get_the_modified_date()
);

$blog_author = "Por ".get_the_author_link();
$categories_list = get_the_category_list(', ');
$tags_list = get_the_tag_list( ', ', ', ' );
?>
<article id="post-<?php the_ID(); ?>" class="blog-article">
		<?php echo $blog_img; ?>
		<header class="blog-header">
			<h2 class="blog-tit"><?php the_title(); ?></h2>
			<?php // meta
			echo "<div class='blog-meta'>";
				if ( $categories_list && twentyfifteen_categorized_blog() ) {
					printf( '<span class="blog-cat-links">%1$s</span>',
						$categories_list
					);
				}
				if ( $tags_list ) {
					printf( '<span class="blog-tag-links">%1$s</span>',
						$tags_list
					);
				}
				echo " <span class='glyphicon glyphicon-star' aria-hidden='true'></span> ".$blog_date;
				echo " <span class='glyphicon glyphicon-star' aria-hidden='true'></span> ".$blog_author;

			echo "</div>"; ?>
		</header>

		<section class="page-desc">
		<?php the_content(); ?>
		</section>

</article><!-- .row .hentry -->


