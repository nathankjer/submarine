jQuery(document).ready(function( $ ) {	
	var owl = $('.owl-carousel');
	var owlCarouselTimeout = 1000;
	owl.owlCarousel({
		items:1,
		loop:true,
		margin:0,
		autoplay:true,
		autoplayTimeout:3000,
		autoplayHoverPause:true
	});
});