/**
 * Testimonials slider.
 *
 * Without JavaScript the track is a horizontal scroll-snap row. This adds the
 * arrows and dots, keeps them in step with the scroll position and lets the
 * arrow keys move between slides. No autoplay.
 */
const reduced = window.matchMedia( '(prefers-reduced-motion: reduce)' );

function init( root ) {
	const track = root.querySelector( '.sk-testimonials__track' );
	const nav = root.querySelector( '.sk-testimonials__nav' );

	if ( ! track || ! nav ) {
		return;
	}

	const slides = Array.from( track.children );

	if ( slides.length < 2 ) {
		return;
	}

	const dots = nav.querySelector( '.sk-testimonials__dots' );
	const prev = nav.querySelector( '[data-dir="-1"]' );
	const next = nav.querySelector( '[data-dir="1"]' );
	let active = 0;

	track.setAttribute( 'tabindex', '0' );
	track.setAttribute( 'role', 'region' );
	track.setAttribute( 'aria-roledescription', 'carousel' );
	track.setAttribute(
		'aria-label',
		root.getAttribute( 'aria-label' ) || 'Testimonials'
	);

	const buttons = slides.map( ( slide, index ) => {
		const dot = document.createElement( 'button' );

		dot.type = 'button';
		dot.className = 'sk-testimonials__dot';
		dot.setAttribute( 'aria-label', `${ index + 1 } / ${ slides.length }` );
		dot.addEventListener( 'click', () => go( index ) );
		dots.appendChild( dot );
		slide.setAttribute( 'role', 'group' );
		slide.setAttribute( 'aria-roledescription', 'slide' );

		return dot;
	} );

	const paint = () => {
		buttons.forEach( ( dot, index ) => {
			if ( index === active ) {
				dot.setAttribute( 'aria-current', 'true' );
			} else {
				dot.removeAttribute( 'aria-current' );
			}
		} );
		prev.disabled = active === 0;
		next.disabled = active === slides.length - 1;
	};

	const go = ( index ) => {
		const target = Math.max( 0, Math.min( slides.length - 1, index ) );

		track.scrollTo( {
			left: slides[ target ].offsetLeft,
			behavior: reduced.matches ? 'auto' : 'smooth',
		} );
	};

	prev.addEventListener( 'click', () => go( active - 1 ) );
	next.addEventListener( 'click', () => go( active + 1 ) );

	track.addEventListener( 'keydown', ( event ) => {
		if ( 'ArrowRight' === event.key ) {
			event.preventDefault();
			go( active + 1 );
		} else if ( 'ArrowLeft' === event.key ) {
			event.preventDefault();
			go( active - 1 );
		}
	} );

	const observer = new window.IntersectionObserver(
		( entries ) => {
			entries.forEach( ( entry ) => {
				if ( entry.isIntersecting ) {
					active = slides.indexOf( entry.target );
					paint();
				}
			} );
		},
		{ root: track, threshold: 0.6 }
	);

	slides.forEach( ( slide ) => observer.observe( slide ) );

	nav.hidden = false;
	paint();
}

document.querySelectorAll( '[data-sk-slider]' ).forEach( init );
