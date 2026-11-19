/**
 * Switches data-theme on <html> and remembers the choice on this device.
 */
const KEY = 'matholek-theme';
const root = document.documentElement;

const isDark = () => 'dark' === root.getAttribute( 'data-theme' );

const label = ( toggle ) =>
	toggle.setAttribute(
		'aria-label',
		isDark() ? 'Ganti ke mode terang' : 'Ganti ke mode gelap'
	);

document.querySelectorAll( '[data-mk-theme-toggle]' ).forEach( ( toggle ) => {
	label( toggle );

	toggle.addEventListener( 'click', () => {
		const next = isDark() ? 'light' : 'dark';

		root.setAttribute( 'data-theme', next );
		root.style.colorScheme = next;

		try {
			window.localStorage.setItem( KEY, next );
		} catch {
			// Storage can be blocked; the choice then lasts for this page only.
		}

		document.querySelectorAll( '[data-mk-theme-toggle]' ).forEach( label );
	} );
} );
