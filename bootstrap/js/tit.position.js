jQuery(window).load(function() {
	jQuery('.sec-header .sec-tit').each(
		function() {
			titWidth = jQuery(this).children('h2').width() + 10;
			jQuery(this).css({"width": titWidth +"px"});
			jQuery(this).next().css({"margin-left": titWidth +"px"});
		}
	);
});
