<?php
$rel_tit = $rel->post_title;
$rel_subtit = get_post_meta( $rel->ID, '_quincem_subtit', true );;
?>
	<div class="rel-item aligncenter col-md-3">
		<div class="thumbnail">
			<?php echo get_the_post_thumbnail($rel->ID,'thumbnail',array('class' => 'img-responsive')); ?>
			<div class="caption">
				<h3 class="rel-item-tit"><?php echo $rel_tit ?></h3>
				<?php // subtitle
				if ( $rel_subtit != '' ) { echo "<div class='rel-item-subtit'>" .$rel_subtit. "</div>"; }

				// date
				if ( $rel_date_begin != '' && $rel_date_end != '' ) { echo "<div class='rel-item-date'>" .$rel_date_out. "</div>"; } ?>
			</div>
		</div>
	</div>
