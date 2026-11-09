/**
 * Light/dark switch transition (GSAP).
 *
 * The new theme is revealed as a circle that grows from the toggle. The browser
 * snapshots the old and new page (View Transitions); GSAP drives the circle
 * radius through a CSS variable that ::view-transition-new(root) clips with.
 * Browsers without View Transitions, and reduced-motion visitors, switch at once.
 */
import { gsap } from 'gsap';

const root = document.documentElement;
const DURATION = 1.05;

const CLEANUP = [
	'--sk-reveal',
	'--sk-reveal-x',
	'--sk-reveal-y',
	'--sk-reveal-dur',
];

function themeSwitch( swap, origin ) {
	const reduced = window.matchMedia(
		'(prefers-reduced-motion: reduce)'
	).matches;

	if ( reduced || ! document.startViewTransition ) {
		swap();
		return;
	}

	const box = origin ? origin.getBoundingClientRect() : null;
	const x = box ? box.left + box.width / 2 : window.innerWidth / 2;
	const y = box ? box.top + box.height / 2 : window.innerHeight / 2;
	const radius = Math.hypot(
		Math.max( x, window.innerWidth - x ),
		Math.max( y, window.innerHeight - y )
	);

	root.style.setProperty( '--sk-reveal', '0px' );
	root.style.setProperty( '--sk-reveal-x', `${ x }px` );
	root.style.setProperty( '--sk-reveal-y', `${ y }px` );
	root.style.setProperty( '--sk-reveal-dur', `${ DURATION }s` );
	root.classList.add( 'sk-theme-switch' );

	const transition = document.startViewTransition( swap );
	const state = { r: 0 };

	transition.ready
		.then( () => {
			gsap.to( state, {
				r: radius,
				duration: DURATION,
				ease: 'power3.inOut',
				onUpdate: () =>
					root.style.setProperty( '--sk-reveal', `${ state.r }px` ),
			} );
		} )
		.catch( () => {} );

	transition.finished
		.catch( () => {} )
		.then( () => {
			gsap.killTweensOf( state );
			root.classList.remove( 'sk-theme-switch' );
			CLEANUP.forEach( ( name ) => root.style.removeProperty( name ) );
		} );
}

window.sekeco = Object.assign( window.sekeco || {}, { themeSwitch } );
