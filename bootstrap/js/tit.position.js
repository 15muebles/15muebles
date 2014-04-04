jQuery(window).on("load resize",function(){

	if (jQuery(window).width() <= 767) { // mobile device
		jQuery('.sec-header .sec-tit').each(function() {
			jQuery(this).css({"width": "auto"});
			jQuery(this).next().css({"margin-left": "0"});
		});

	} else {
		jQuery('.sec-header .sec-tit').each(function() {
			titWidth = jQuery(this).children('h2').width() + 10;
			jQuery(this).css({"width": titWidth +"px"});
			jQuery(this).next().css({"margin-left": titWidth +"px"});
		});

	}
});
