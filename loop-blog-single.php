<?php
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

$blog_author = get_the_author_link();
$categories_list = get_the_category_list(', ');
$tags_list = get_the_tag_list( ', ', ', ' );

			// meta
			echo "<div class='blog-meta'>
				<div class='blog-meta-item'>CONTEXTO: ";
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
				echo "</div>
				<div class='blog-meta-item'>FECHA: ".$blog_date."</div>
				<div class='blog-meta-item'>AUTOR: ".$blog_author."</div>
			</div>"; ?>

		<section class="page-desc">
		<?php
			/* translators: %s: Name of current post */
			the_content();

			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentyfifteen' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'twentyfifteen' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );
		?>
		</section>
