<?php  ob_start();
get_header();

// pt vars
//global $pt;
$pt = $wp_query->query_vars['post_type'];

// section vars
$band_tit = $wp_post_types[$pt]->labels->parent;
$band_subtit = $wp_post_types[$pt]->labels->name;
$band_desc = $wp_post_types[$pt]->description;
$band_class = $pt;

$issuer_metadatas = array();
$issuer_metadatas['Emisor'] = $post->post_title;
$issuer_metadatas['Descripción'] = $post->post_content;
$issuer_url = get_post_meta($post->ID,'_quincem_issuer_url',true);
if ($issuer_url != '') { $issuer_metadatas['Sitio web'] = "<a href='".$issuer_url."'>".$issuer_url."</a>"; }

$single_img_size = "small";
if ( has_post_thumbnail() ) {
	$single_logo = get_the_post_thumbnail($post->ID,$single_img_size,array('class' => 'img-responsive'));
	$issuer_tit = "<header><h1 class='hideout'>" .get_the_title(). "</h1></header>";
} else {
	$single_logo = "";
	$issuer_tit = "<header><h1>" .get_the_title(). "</h1></header>";
}

// related content
$rel_tit = "Badges propuestos por ".$post->post_title;
$args = array(
	'posts_per_page' => -1,
	'post_type' => 'badge',
	'post_parent' => 0,
	'meta_key' => '_quincem_issuer',
	'meta_value' => $post->ID
);
$badges = get_posts($args);
$not_in = array();
foreach ( $badges as $b ) {
	$args = array(
		'post_type' => 'badge',
		'post_parent' => $b->ID,
		'meta_key' => '_quincem_version',
		'orderby' => 'meta_value_num',
		'order' => 'DESC'

	);
	$children = get_posts($args);
	if ( count($children) == 1 ) { $not_in[] = $b->ID; }
	elseif ( count($children) >= 2 ) {
		$not_in[] = $b->ID;
		$ch_count = 0;
		foreach ( $children as  $ch ) {
			if ( $ch_count != 0 ) { $not_in[] = $ch->ID; }
			$ch_count++;
		}
	}
	
}
$args = array(
	'posts_per_page' => -1,
	'post_type' => 'badge',
	'orderby' => 'menu_order title',
	'order' => 'ASC',
	'post__not_in' => $not_in,
	'meta_key' => '_quincem_issuer',
	'meta_value' => $post->ID
);
$rel_item_img_size = "thumbnail";

$badges = get_posts($args);
if ( count($badges) > 0 ) {
	$rel_items = get_posts($args);
}
?>

<div id="<?php echo $band_class; ?>" class="container-full">
<div class="container">
	<div class="sec-header row hair">
	<div class="col-md-12 col-sm-12">
		<div class="sec-tit">
			<h2><?php echo $band_tit; ?></h2>
			<div class="sec-subtit"><?php echo $band_subtit; ?></div>
		</div>
		<div class="sec-desc"><p><?php echo $band_desc; ?></p></div>
	</div>
	</div><!-- .sec-header .row .hair-->

	<?php while ( have_posts() ) : the_post(); ?>
	<article class="row hentry">
		<?php echo $issuer_tit; ?>
		<section class="issuer-ficha col-md-3 col-sm-3">
			<?php echo $single_logo; ?>
			<header><h3>Ficha del emisor</h3></header>
			<dl class="issuer-metadatas">
				<?php foreach ( $issuer_metadatas as $label => $im ) {
					echo "<dt>".$label."</dt><dd>".$im."</dd>";
				} ?>
			</dl>

		</section><!-- .col-md-3 .col-sm-4  -->

		<div class="col-md-6 col-sm-6">
		<section class="single-desc">
			<header>
				<h2><?php echo $rel_tit; ?></h2>
			</header>
			<div class="mosac row hair">

			<?php // related content loop
			$mobile_count = 0;
			$tablet_count = 0;
			$desktop_count = 0;
			if ( count($badges) > 0 ) {
				foreach ( $rel_items as $rel ) {
					if ( $mobile_count == 2 ) { $mobile_count = 0; echo '<div class="clearfix visible-xs"></div>';  }
					if ( $tablet_count == 3 ) { $tablet_count = 0; echo '<div class="clearfix visible-sm"></div>';  }
					if ( $desktop_count == 4 ) { $desktop_count = 0; echo '<div class="clearfix visible-md visible-lg"></div>';  }
					$tablet_count++;
					$desktop_count++;

					$rel_item_perma = get_permalink($rel->ID);
$rel_item_name = get_the_title($rel->ID);
$rel_item_tit = "<h3 class='rel-item-tit'><a href='" .$rel_item_perma. "' title='" .$rel_item_name. "' rel='bookmark'>" .$rel_item_name. "</a></h3>";
$rel_item_subtit = get_post_meta($rel->ID,'_quincem_badge_subtit',true);
if ( has_post_thumbnail($rel->ID) ) { $rel_item_logo = "<a href='" .$rel_item_perma. "' title='" .$rel_item_name. "' rel='bookmark'>" .get_the_post_thumbnail($rel->ID,$rel_item_img_size,array('class' => 'img-responsive')). "</a>"; } else { $rel_item_logo = ""; }
?>
	<div class="rel-item aligncenter col-md-3 col-sm-4 col-xs-6">
		<div class="thumbnail">
			<?php echo $rel_item_logo; ?>
			<div class="caption">
				<?php echo $rel_item_tit; ?>
				<?php // subtitle
				if ( $rel_item_subtit != '' ) { echo "<div class='rel-item-subtit'>" .$rel_item_subtit. "</div>"; }

				?>
			</div>
		</div>
	</div>
			<?php }
			} else { echo "<p>".$post->post_title." aún no emite ningún badge.</p>"; }?>

			</div><!-- .mosac .row .hair -->
		</section>

		</div><!-- .col-md-6 .col-sm-6 -->

		<aside class="col-md-3 col-sm-3">
			<div class='single-aside'>
				<h3>Contacta con el emisor</h3>
				<?php quincem_contact_issuer($post); ?>
			</div>
		</aside><!-- .col-md-3 .col-sm-3 -->

	</article><!-- .mosac .row .hair -->

	<?php endwhile; // end main loop ?>

</div><!-- .container -->
</div><!-- .container-full -->

<?php get_footer(); ?>
