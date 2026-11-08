/**
 * Shows the scroll-to-top button after a screen of scrolling and draws how far
 * down the page the visitor is as a ring around it.
 */
const button = document.querySelector( '[data-sk-scroll-top]' );

if ( button ) {
	const reduced = window.matchMedia( '(prefers-reduced-motion: reduce)' );
	let queued = false;

	button.hidden = false;

	const update = () => {
		queued = false;

		const max = document.documentElement.scrollHeight - window.innerHeight;
		const progress = max > 0 ? Math.min( 1, window.scrollY / max ) : 0;

		button.style.setProperty( '--sk-progress', progress.toFixed( 4 ) );
		button.classList.toggle( 'is-visible', window.scrollY > 480 );
	};

	window.addEventListener(
		'scroll',
		() => {
			if ( ! queued ) {
				queued = true;
				window.requestAnimationFrame( update );
			}
		},
		{ passive: true }
	);
	window.addEventListener( 'resize', update );
	update();

	button.addEventListener( 'click', () => {
		window.scrollTo( {
			top: 0,
			behavior: reduced.matches ? 'auto' : 'smooth',
		} );
	} );
}
