/**
 * Slider frontend runtime.
 */

const reduceMotion = () =>
	window.matchMedia &&
	window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

const initSlider = ( slider ) => {
	if ( 'true' === slider.dataset.sekecoSliderReady ) {
		return;
	}

	slider.dataset.sekecoSliderReady = 'true';

	const viewport = slider.querySelector( '.sekeco-slider__viewport' );
	const track = slider.querySelector( '.sekeco-slider__track' );

	if ( ! viewport || ! track ) {
		return;
	}

	const slides = Array.from( track.children );

	if ( ! slides.length ) {
		return;
	}

	const prev = slider.querySelector( '[data-sekeco-slider-prev]' );
	const next = slider.querySelector( '[data-sekeco-slider-next]' );
	const gap = parseFloat( window.getComputedStyle( track ).columnGap ) || 0;

	const step = () => slides[ 0 ].getBoundingClientRect().width + gap;

	const update = () => {
		const max = viewport.scrollWidth - viewport.clientWidth;

		if ( prev ) {
			prev.disabled = viewport.scrollLeft <= 1;
		}

		if ( next ) {
			next.disabled = viewport.scrollLeft >= max - 1;
		}
	};

	const scrollBySlide = ( direction ) => {
		viewport.scrollBy( {
			left: step() * direction,
			behavior: reduceMotion() ? 'auto' : 'smooth',
		} );
	};

	slider.classList.add( 'sekeco-slider--js' );

	if ( prev ) {
		prev.addEventListener( 'click', () => scrollBySlide( -1 ) );
	}

	if ( next ) {
		next.addEventListener( 'click', () => scrollBySlide( 1 ) );
	}

	let ticking = false;

	viewport.addEventListener(
		'scroll',
		() => {
			if ( ticking ) {
				return;
			}

			ticking = true;
			window.requestAnimationFrame( () => {
				update();
				ticking = false;
			} );
		},
		{ passive: true }
	);

	viewport.addEventListener( 'keydown', ( event ) => {
		if ( 'ArrowLeft' === event.key ) {
			event.preventDefault();
			scrollBySlide( -1 );
		}

		if ( 'ArrowRight' === event.key ) {
			event.preventDefault();
			scrollBySlide( 1 );
		}
	} );

	window.addEventListener( 'resize', update );
	update();
};

const initAll = () => {
	document
		.querySelectorAll( '.sekeco-slider[data-sekeco-slider]' )
		.forEach( initSlider );
};

if ( 'loading' === document.readyState ) {
	document.addEventListener( 'DOMContentLoaded', initAll );
} else {
	initAll();
}
