class Carousel {

	constructor() {
		this.init();
	}

	init() {
		$( '.fade-carousel' ).slick({
			dots: true,
			arrows: false,
			autoplay: true,
			infinite: true,
			speed: 500,
			fade: true,
			cssEase: 'linear'
		});
	}
}

export default Carousel;
