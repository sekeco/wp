/**
 * Frontend reveal runtime for the Sekeco motion extension.
 */
import './style.scss';
import './runtime';

const reduceMotion = () =>
	window.matchMedia &&
	window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

const reveal = ( element ) => element.classList.add( 'is-visible' );

const revealAfterPaint = ( element ) => {
	window.requestAnimationFrame( () => {
		window.requestAnimationFrame( () => reveal( element ) );
	} );
};

const initMotion = () => {
	document.querySelectorAll( '.sekeco-motion' ).forEach( ( element ) => {
		if ( 'true' === element.dataset.sekecoMotionReady ) {
			return;
		}

		element.dataset.sekecoMotionReady = 'true';

		if ( reduceMotion() ) {
			reveal( element );
			return;
		}

		element.classList.add( 'sekeco-motion--js' );

		const trigger = element.dataset.sekecoTrigger || 'scroll';

		if ( 'load' === trigger || ! ( 'IntersectionObserver' in window ) ) {
			revealAfterPaint( element );
			return;
		}

		const observer = new window.IntersectionObserver(
			( entries, instance ) => {
				entries.forEach( ( entry ) => {
					if ( ! entry.isIntersecting ) {
						return;
					}

					revealAfterPaint( element );
					instance.disconnect();
				} );
			},
			{ threshold: 0.12, rootMargin: '0px 0px -6% 0px' }
		);

		observer.observe( element );

		// Above-the-fold sections should not wait for a scroll event.
		window.requestAnimationFrame( () => {
			if (
				element.getBoundingClientRect().top <
				window.innerHeight * 0.9
			) {
				revealAfterPaint( element );
				observer.disconnect();
			}
		} );
	} );
};

if ( 'loading' === document.readyState ) {
	document.addEventListener( 'DOMContentLoaded', initMotion );
} else {
	initMotion();
}
