<?php
if ( $band_pts[$band_count] == 'itinerario' ) {
	$item_tit = get_the_title();
	$item_subtit = get_post_meta( $post->ID, '_quincem_subtit', true );
	$item_date_begin = "";
	$item_date_end = "";
	$item_desc = get_the_excerpt();
	$item_icon = get_post_meta( $post->ID, '_quincem_icono',true );
	$item_icons_out = "<ul class='list-inline'><li><img src='" .$item_icon. "' alt='" .$item_tit. ". " .$item_subtit. "' /></li></ul>";

} elseif ( $band_pts[$band_count] == 'badge' ) {
	$item_tit = get_the_title();
	$item_subtit = get_post_meta( $post->ID, '_quincem_subtit', true );

	$item_date_begin = "";
	$item_date_end = "";
	$item_desc = "";
	$item_icons_out = "";

} elseif ( $band_pts[$band_count] == 'actividad' ) {
	$item_tit = get_the_title();
	$item_subtit = get_post_meta( $post->ID, '_quincem_escenario', true );

	$item_date_begin = get_post_meta( $post->ID, '_quincem_date_begin', true );
	$item_date_end = get_post_meta( $post->ID, '_quincem_date_end', true );
	$item_date_out = date('d\/m',$item_date_begin). "-" .date('d\/m',$item_date_end);

	$item_desc = get_the_excerpt();


	$second_loop_args = array(
		'post_type' => 'badge',
		'meta_query' => array(
			array(
				'key' => '_quincem_actividades',
				'value' => '"' .$post->ID. '"',
				'compare' => 'LIKE'
			)
		)
	);
	$badges = get_posts($second_loop_args);
	if ( count($badges) > 0 ) {
		$item_icons_out = "<ul class='list-inline'>";
		foreach ( $badges as $badge ) {
			$badge_icon = get_post_meta( $badge->ID, '_quincem_icono',true );
			$badge_tit = get_the_title($badge->ID);
			$item_icons_out .= "<li><img src='" .$badge_icon. "' alt='" .$badge_tit. "' /></li>";
		}
		$item_icons_out .= "</ul>";
	 } else { $item_icons_out = ""; }

}
?>

<article class="mosac-item aligncenter col-md-<?php echo $col_desktop ?> col-sm-<?php echo $col_tablet ?>">
<div <?php post_class(); ?>>
	<?php the_post_thumbnail('thumbnail',array('class' => 'img-responsive')); ?>
	<div class="caption">
		<h3><?php echo $item_tit; ?></h3>
		<?php // subtitle
		if ( $item_subtit != '' ) { echo "<div class='mosac-item-subtit'>" .$item_subtit. "</div>"; }

		// date
		if ( $item_date_begin != '' && $item_date_end != '' ) { echo "<div class='mosac-item-date'>" .$item_date_out. "</div>"; }
 
		// description
		if ( $item_desc != '' ) { echo "<div class='mosac-item-desc'>" .$item_desc. "</div>"; }

		// icons
		if ( $item_icons_out != '' ) { echo "<div class='mosac-item-icons'>" .$item_icons_out. "</div>"; }
		?>	
	</div>
</div>
</article>

