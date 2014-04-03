<?php
if ( $band_pts[$band_count] == 'itinerario' ) {
	$item_subtit = get_post_meta( $post->ID, '_quincem_subtit', true );
	$item_desc = get_the_excerpt();

} elseif ( $band_pts[$band_count] == 'badge' ) {
	$item_subtit = get_post_meta( $post->ID, '_quincem_subtit', true );
	$item_desc = "";

} elseif ( $band_pts[$band_count] == 'actividad' ) {
	$item_subtit = "";
	$item_desc = "";

}
?>

<article class="mosac-item aligncenter col-md-<?php echo $col_desktop ?> col-sm-<?php echo $col_tablet ?>">
<div <?php post_class(); ?>>
	<?php the_post_thumbnail('thumbnail',array('class' => 'img-responsive')); ?>
	<div class="caption">
		<h3><?php the_title(); ?></h3>
		<?php // subtitle
		if ( $item_subtit != '' ) { echo "<div class='mosac-item-subtit'>" .$item_subtit. "</div>"; }

		// description
		if ( $item_desc != '' ) { echo "<div class='mosac-item-desc'>" .$item_desc. "</div>"; }
		?>	
	</div>
</div>
</article>

