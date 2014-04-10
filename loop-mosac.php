<?php

// vars depending on the custom post type
if ( $band_pts[$band_count] == 'itinerario' || $pt == 'itinerario' ) {
	$item_subtit = "";
	$item_date_begin = "";
	$item_date_end = "";
	$item_icon_id = get_post_meta( $post->ID, '_quincem_icono_id',true );	
	if ( $item_icon_id != '' ) {
		$item_icon = wp_get_attachment_image( $item_icon_id, 'icon' );
		$item_icons_out = "<ul class='list-inline'><li>" .$item_icon. "</li></ul>";
	}
	if ( is_single() ) { $item_desc = get_the_content(); }
	else { $item_desc = get_the_excerpt(); }
	$item_img_size = "medium";

} elseif ( $band_pts[$band_count] == 'badge' || $pt == 'badge' ) {
	$item_subtit = get_post_meta( $post->ID, '_quincem_subtit', true );

	$item_date_begin = "";
	$item_date_end = "";
	$item_desc = "";
	$item_icons_out = "";
	$item_img_size = array(75,75);

} elseif ( $band_pts[$band_count] == 'actividad' || $pt == 'actividad' ) {
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

	$item_img_size = "medium";

}

// common vars for all custom post types
if ( is_single() ) {
	$item_tit = "<h1 class='mosac-item-tit'>" .get_the_title(). "</h1>";
	if ( has_post_thumbnail() ) { $item_logo = get_the_post_thumbnail($post->ID,'thumbnail',array('class' => 'img-responsive')); } else { $item_logo = ""; }
} else {
	$item_perma = get_permalink();
	$item_name = get_the_title();
	$item_tit = "<h3 class='mosac-item-tit'><a href='" .$item_perma. "' title='" .$item_name. "' rel='bookmark'>" .$item_name. "</a></h3>";
	if ( has_post_thumbnail() ) {
		$item_logo = "<a href='" .$item_perma. "' title='" .$item_name. "' rel='bookmark'>" .get_the_post_thumbnail($post->ID,$item_img_size,array('class' => 'img-responsive')). "</a>"; } else { $item_logo = "";
	}
}

?>

<article class="mosac-item aligncenter col-md-<?php echo $col_desktop ?> col-sm-<?php echo $col_tablet ?>">
<div <?php post_class(); ?>>
	<?php echo $item_logo; ?>
	<div class="caption">
		<?php echo $item_tit; ?>
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

