<?php
if( $pt == 'itinerario' ) {
	$rel_item_date_begin = "";
	$rel_item_date_end = "";
	$rel_item_subtit = get_post_meta( $rel->ID, '_quincem_subtit', true );
}elseif( $pt == 'badge' ) {
	$rel_item_date_begin = get_post_meta( $rel->ID, '_quincem_date_begin', true );
	$rel_item_date_end = get_post_meta( $rel->ID, '_quincem_date_end', true );
	$rel_item_date_out = date('d\/m',$rel_item_date_begin). "-" .date('d\/m',$rel_item_date_end);
	$rel_item_subtit = get_post_meta( $rel->ID, '_quincem_escenario', true );
} elseif ( $pt == 'actividad' ) {
	$rel_item_date_begin = "";
	$rel_item_date_end = "";
	$rel_item_subtit = get_post_meta( $rel->ID, '_quincem_subtit', true );
}

$rel_item_perma = get_permalink($rel->ID);
$rel_item_name = get_the_title($rel->ID);
$rel_item_tit = "<h3 class='rel-item-tit'><a href='" .$rel_item_perma. "' title='" .$rel_item_name. "' rel='bookmark'>" .$rel_item_name. "</a></h3>";
if ( has_post_thumbnail() ) { $rel_item_logo = "<a href='" .$rel_item_perma. "' title='" .$rel_item_name. "' rel='bookmark'>" .get_the_post_thumbnail($rel->ID,$rel_item_img_size,array('class' => 'img-responsive')). "</a>"; } else { $rel_item_logo = ""; }
?>
	<div class="rel-item aligncenter <?php echo $rel_item_class; ?>">
		<div class="thumbnail">
			<?php echo $rel_item_logo; ?>
			<div class="caption">
				<?php echo $rel_item_tit; ?>
				<?php // subtitle
				if ( $rel_item_subtit != '' ) { echo "<div class='rel-item-subtit'>" .$rel_item_subtit. "</div>"; }

				// date
				if ( $rel_item_date_begin != '' && $rel_item_date_end != '' ) { echo "<div class='rel-item-date'>" .$rel_item_date_out. "</div>"; } ?>
			</div>
		</div>
	</div>
