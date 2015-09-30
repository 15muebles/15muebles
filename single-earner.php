<?php
get_header();

// earner data
$earner_name = get_post_meta( $post->ID, '_quincem_earner_name',true );
$earner_mail = get_post_meta( $post->ID, '_quincem_earner_mail',true );
$earner_evidence = get_post_meta( $post->ID, '_quincem_earner_material',true );
$earner_date = $post->post_date;
$earner_actividad = get_post_meta( $post->ID, '_quincem_earner_actividad',true );
$earner_badge = get_post_meta( $post->ID, '_quincem_earner_badge',true );

// badge data
$args = array(
	'post_type' => 'badge',
	'include' => $earner_badge
);
$badges = get_posts($args);
foreach ( $badges as $badge ) {
	$earner_badge_tit = $badge->post_title;
	$earner_badge_slug = $badge->post_name;
	$badge_perma = get_permalink();
	$badge_json = "http://ciudad-escuela.org/openbadges/badge-" .$badge->post_name. ".json";
	$badge_img = "http://ciudad-escuela.org/openbadges/images/badge-" .$badge->post_name. ".png";		
	$earner_json_url = "http://ciudad-escuela.org/openbadges/assertions/badge-" .$badge->post_name. "-" .$post->ID. ".json";
	//$earner_json_url = "http://ciudad-escuela.org/openbadges/assertions/badge-pedagogias-abiertas-201.json";
	//$earner_json_url = "http://poof.hksr.us/jeysjksz";
	$earner_json_path = $_SERVER['DOCUMENT_ROOT'] . "/openbadges/assertions/badge-" .$badge->post_name. "-" .$post->ID. ".json";
}

// OBI email converter vars
$email_to_id_url = "http://backpack.openbadges.org/displayer/convert/email"; 
$email_to_id_params = "email=" .$earner_mail;

// get earner's backpack ID
// convert earner mail to mozilla backpack ID
$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER, 0 );
//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL, $email_to_id_url);
curl_setopt($ch,CURLOPT_POST, 1);
curl_setopt($ch,CURLOPT_POSTFIELDS, $email_to_id_params);
// receive server response
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$earner_json = curl_exec($ch);
curl_close($ch);

$earner_data = json_decode($earner_json,true);

// if user has already a backpack
if ( $earner_data['status'] == 'okay' ) {
	$backpack_id = $earner_data['userId'];

	// get earner's public badges
	// to see if our badge has been added to his/her backpack
	$backpack_groups_json = file_get_contents("http://beta.openbadges.org/displayer/" .$backpack_id. "/groups.json");
	$backpack_groups = json_decode($backpack_groups_json,true);
	foreach ( $backpack_groups['groups'] as $group ) { // groups loop
		$backpack_group_json = file_get_contents("http://beta.openbadges.org/displayer/" .$backpack_id. "/group/" .$group['groupId']. ".json");
		$backpack_group = json_decode($backpack_group_json,true);

		foreach ( $backpack_group['badges'] as $backpack_badge ) {
			if ( $backpack_badge['hostedUrl'] == $earner_json_url ) { $badge_in_backpack = true; break; }
		}
		if ( isset($badge_in_backpack) ) break;
	} // end groups loop
} // end if user has already a backpack

// if badge is in backpack publicly
if ( isset($badge_in_backpack) ) {
	$args = array(
		'post_type' => 'earner',
		'meta_query' => array(
			array(
				'key' => '_quincem_earner_mail',
				'value' => $earner_mail,
				'compare' => 'LIKE'
			)
		)
	);
	$earners = get_posts($args);
	$earner_badges_out = "<p>" .$earner_name. " ha ganado los siguientes badges de Ciudad Escuela:</p><ul>";
	foreach ( $earners as $this_earner ) {
		$this_earner_badge = get_post_meta( $this_earner->ID, '_quincem_earner_badge', true );
		$this_earner_badge_perma = get_permalink($this_earner_badge);
		$this_earner_badge_tit = get_the_title($this_earner_badge);
		$earner_badges_out .= "<li><a href='" .$this_earner_badge_perma. "'>" .$this_earner_badge_tit. "</a></li>";
	}
	$earner_badges_out .= "</ul>";
	$backpack_out = "
		<p>" .$earner_name. " ganó el badge " .$earner_badge_tit. " de Ciudad Escuela el " .$earner_date. " por su participación en la actividad " .$earner_actividad. ".</p>
		<p>Puedes consultar el <strong><a href='" .$earner_evidence. "'>material que " .$earner_name. " produjo y liberó</a></strong> para ganar el badge.</p>
		<p>También puedes consultar el <a href='" .$badge_perma. "'>listado completo de personas que han ganado el badge " .$earner_badge_tit. "</a>.</p>
		" .$earner_badges_out. "	
	";

// if badge is not in backpack or is not public
} else {
	$backpack_out = "
		<p>Si eres " .$earner_name. " puedes <a class='btn btn-default' id='backpack-connect' href='#'>añadir este badge a tu mochila</a>.</p>
		<p>Una vez lo hayas añadido puedes visitar <a href='http://backpack.openbadges.org/'>tu mochila</a> para mostrar tu badge fuera de Ciudad Escuela: crea una colección, márcala como pública y comparte tu badge en redes sociales.</p>
	";
} // end if badge is already in backpack


// earner single output
if ( have_posts() ) {
while ( have_posts() ) : the_post();

	// parent page
	$parent_tit = get_the_title(); ?>
	<script src="https://backpack.openbadges.org/issuer.js"></script>
	<script>
		jQuery(document).ready(function() {
			var earnerJsonUrl = "<?php echo $earner_json_url; ?>";
			var assertions = new Array(earnerJsonUrl);
			jQuery("#backpack-connect").click(function() {
				OpenBadges.issue(assertions, function(errors, successes) {
					if ( successes == earnerJsonUrl ) {
						jQuery("#backpack-out").empty().text("¡Enhorabuena! Has añadido con éxito este badge a tu mochila.");
					}
				});
			});
		});
	</script>

<div id="earner" class="container-full">
<div class="container">
	<header class="row">
		<div class="col-md-12 col-sm-12">
			<h1 class="parent-tit"><?php echo $parent_tit; ?></h1>
		</div>
	</header>

	<section class="row page-desc">
		<div class="col-md-8 col-sm-9">
			<?php
			echo "<div id='backpack-out'>" .$backpack_out. "</div>";
			the_content(); ?>
		</div>
	</section><!-- .page-desc -->

</div><!-- .container -->
</div><!-- .container-full -->


<?php endwhile;
} // end if posts
?>

<?php get_footer(); ?>
