$('.jobs-slider').slick({
	slidesToShow: 2,
	slidesToScroll: 1,
	centerMode: true,
	dots: false,
	focusOnSelect: true,
	variableWidth: true,
	autoplay: true,
	autoPlaySpeed: 2000
});

$('.results-for').slick({
	slidesToShow: 1,
	slidesToScroll: 1,
	arrows: false,
	fade:true,
	asNavFor: '.results-nav'
});

$('.results-nav').slick({
	slidesToShow: 3,
	slidesToScroll: 1,
	asNavFor: '.results-for',
	dots: true,
	centerMode: true,
	focusOnSelect: true,
	variableWidth: true,
	autoplay: true,
	autoPlaySpeed: 3000
});