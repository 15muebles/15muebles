jQuery(window).ready(function($) {
	$('.sec-header .sec-tit').each(
		function() {
			titWidth = $(this).children('h2').width() + 10;
			$(this).css({"width": titWidth +"px"});
			$(this).next().css({"margin-left": titWidth +"px"});
		}
	);
});
