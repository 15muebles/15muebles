jQuery(document).ready(function($) {
	$("#pre-navbar a[href^='#']").on('click', function(e) {
		// prevent default anchor click behavior
		e.preventDefault();

		// store hash and offset
		var hash = this.hash;		
		var offset = Number($(this.hash).offset().top) - 40;

		// animate
		$('html, body').animate({
			scrollTop: offset
			}, 300, function(){

				// when done, add hash to url
				// (default click behaviour)
				window.location.hash = hash;
			});

		// active link
		if ( $(this).parent().hasClass('active') ) {}
		else {
			$('#pre-navbar .active').removeClass('active');
			$(this).parent().toggleClass('active');
		}
	});
});
