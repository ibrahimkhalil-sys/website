
$(function () {

	$('.container-business-primary.achievement .tiles').slick({
		// infinite: true,
		arrows: true,
		slidesToShow: 2,
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
