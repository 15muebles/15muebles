<?php
$rel_perma = get_permalink();
$rel_name = get_the_title();
$rel_tit = "<h3 class='rel-item-tit'><a href='" .$rel_perma. "' title='" .$rel_name. "' rel='bookmark'>" .$rel_name. "</a></h3>";
$rel_subtit = get_post_meta( $rel->ID, '_quincem_subtit', true );
if ( has_post_thumbnail() ) { $rel_logo = "<a href='" .$rel_perma. "' title='" .$rel_name. "' rel='bookmark'>" .get_the_post_thumbnail($rel->ID,'thumbnail',array('class' => 'img-responsive')). "</a>"; } else { $rel_logo = ""; }
?>
	<div class="rel-item aligncenter col-md-3">
		<div class="thumbnail">
			<?php echo $rel_logo; ?>
			<div class="caption">
				<?php echo $rel_tit; ?>
				<?php // subtitle
				if ( $rel_subtit != '' ) { echo "<div class='rel-item-subtit'>" .$rel_subtit. "</div>"; }

				// date
				if ( $rel_date_begin != '' && $rel_date_end != '' ) { echo "<div class='rel-item-date'>" .$rel_date_out. "</div>"; } ?>
			</div>
		</div>
	</div>
