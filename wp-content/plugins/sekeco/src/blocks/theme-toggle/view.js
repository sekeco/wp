/**
 * Switches data-theme on <html> and remembers the choice.
 * The initial value is set by an inline script in <head> (no flash).
 */
const KEY = 'sekeco-theme';
const root = document.documentElement;

const current = () =>
	'dark' === root.getAttribute( 'data-theme' ) ? 'dark' : 'light';

const label = ( toggle ) =>
	toggle.setAttribute(
		'aria-label',
		'dark' === current() ? 'Switch to light mode' : 'Switch to dark mode'
	);

const apply = ( theme ) => {
	root.setAttribute( 'data-theme', theme );
	root.style.colorScheme = theme;

	try {
		window.localStorage.setItem( KEY, theme );
	} catch {
		// Storage can be blocked; the choice then lasts for this page only.
	}
};

document.querySelectorAll( '[data-sk-theme-toggle]' ).forEach( ( toggle ) => {
	label( toggle );

	toggle.addEventListener( 'click', () => {
		const next = 'dark' === current() ? 'light' : 'dark';
		const swap = () => {
			apply( next );
			document
				.querySelectorAll( '[data-sk-theme-toggle]' )
				.forEach( label );
		};

		if ( document.startViewTransition ) {
			document.startViewTransition( swap );
		} else {
			swap();
		}
	} );
} );
