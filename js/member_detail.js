
$(function () {

	$('.l-main .container-primary .tiles').slick({
		// infinite: true,
		arrows: true,
		slidesToShow: 4,
		slidesToScroll: 1,
		autoplay: true,
		autoplaySpeed: 4000,
		responsive: [{
			breakpoint: 576,
			settings: {
				slidesToShow: 1
			}
		}],
	});

});
