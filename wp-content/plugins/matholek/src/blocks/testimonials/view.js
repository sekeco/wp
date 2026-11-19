/**
 * Testimonial slider: next/prev, dots, autoplay every 6 s.
 * Autoplay waits while the pointer or focus is inside, and never runs for
 * reduced motion.
 */
const DELAY = 6000;
const still = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

document.querySelectorAll( '[data-mk-slider]' ).forEach( ( slider ) => {
	const slides = [ ...slider.querySelectorAll( '.mk-slide' ) ];
	const dots = [ ...slider.querySelectorAll( '.mk-testi__dot' ) ];
	let index = 0;
	let held = false;

	if ( slides.length < 2 ) {
		return;
	}

	const go = ( next ) => {
		index = ( next + slides.length ) % slides.length;
		slides.forEach( ( slide, i ) => {
			const on = i === index;
			slide.classList.toggle( 'is-active', on );
			slide.toggleAttribute( 'inert', ! on );
		} );
		dots.forEach( ( dot, i ) =>
			dot.classList.toggle( 'is-active', i === index )
		);
	};

	slider.classList.add( 'is-ready' );
	go( 0 );

	slider
		.querySelector( '[data-mk-next]' )
		.addEventListener( 'click', () => go( index + 1 ) );
	slider
		.querySelector( '[data-mk-prev]' )
		.addEventListener( 'click', () => go( index - 1 ) );

	if ( still ) {
		return;
	}

	const hold = ( value ) => () => ( held = value );
	slider.addEventListener( 'pointerenter', hold( true ) );
	slider.addEventListener( 'pointerleave', hold( false ) );
	slider.addEventListener( 'focusin', hold( true ) );
	slider.addEventListener( 'focusout', hold( false ) );

	window.setInterval( () => held || go( index + 1 ), DELAY );
} );
