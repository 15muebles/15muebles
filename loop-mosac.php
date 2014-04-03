<?php
if ( $band_pts[$band_count] == 'itinerario' ) {
	$item_tit = get_the_title();
	$item_subtit = get_post_meta( $post->ID, '_quincem_subtit', true );
	$item_desc = get_the_excerpt();
	$item_icono = get_post_meta( $post->ID, '_quincem_icono',true );
	$item_iconos_out = "<ul class='list-inline'><li><img src='" .$item_icono. "' alt='" .$item_tit. ". " .$item_subtit. "' /></li></ul>";

} elseif ( $band_pts[$band_count] == 'badge' ) {
	$item_subtit = get_post_meta( $post->ID, '_quincem_subtit', true );
	$item_desc = "";
	$item_iconos_out = "";

} elseif ( $band_pts[$band_count] == 'actividad' ) {
	$item_subtit = "";
	$item_desc = "";
	$item_iconos_out = "";

}
?>

<article class="mosac-item aligncenter col-md-<?php echo $col_desktop ?> col-sm-<?php echo $col_tablet ?>">
<div <?php post_class(); ?>>
	<?php the_post_thumbnail('thumbnail',array('class' => 'img-responsive')); ?>
	<div class="caption">
		<h3><?php echo $item_tit; ?></h3>
		<?php // subtitle
		if ( $item_subtit != '' ) { echo "<div class='mosac-item-subtit'>" .$item_subtit. "</div>"; }

		// description
		if ( $item_desc != '' ) { echo "<div class='mosac-item-desc'>" .$item_desc. "</div>"; }

		// iconos
		if ( $item_iconos_out != '' ) { echo "<div class='mosac-item-iconos'>" .$item_iconos_out. "</div>"; }
		?>	
	</div>
</div>
</article>

