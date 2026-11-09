/**
 * Light/dark switch transition: Sunrise (GSAP).
 *
 * The new theme rises from the bottom of the screen behind a curved horizon
 * with a thin glow on its edge: warm going to light, cool going to dark. The
 * browser snapshots the page (View Transitions); GSAP drives the horizon height
 * through a CSS variable that ::view-transition-new(root) clips with, and the
 * same variable lifts a glow element captured as its own transition group.
 * Browsers without View Transitions, and reduced-motion visitors, switch at once.
 */
import { gsap } from 'gsap';

const root = document.documentElement;
const DURATION = 1.5;
const VARS = [ '--sk-h', '--sk-r', '--sk-sag', '--sk-dur' ];

let running = false;

function themeSwitch( swap, next ) {
	const reduced = window.matchMedia(
		'(prefers-reduced-motion: reduce)'
	).matches;

	if ( running ) {
		return;
	}

	if ( reduced || ! document.startViewTransition ) {
		swap();
		return;
	}

	running = true;

	const width = window.innerWidth;
	const height = window.innerHeight;
	const radius = Math.max( width, height ) * 1.3;
	const sag = radius - Math.sqrt( radius * radius - ( width / 2 ) ** 2 );
	const start = height + 70;
	const end = -sag - 70;
	const toLight = 'light' === next;

	const dawn = document.createElement( 'div' );
	dawn.className = 'sk-dawn';
	dawn.setAttribute( 'aria-hidden', 'true' );
	dawn.style.setProperty(
		'--sk-rim',
		toLight ? 'rgb(255 228 176 / 0.9)' : 'rgb(196 204 255 / 0.85)'
	);
	dawn.style.setProperty(
		'--sk-glow',
		toLight ? 'rgb(255 160 90 / 0.5)' : 'rgb(110 120 255 / 0.5)'
	);
	document.body.append( dawn );

	root.style.setProperty( '--sk-h', `${ start }px` );
	root.style.setProperty( '--sk-r', `${ radius }px` );
	root.style.setProperty( '--sk-sag', `${ sag }px` );
	root.style.setProperty( '--sk-dur', `${ DURATION }s` );
	root.classList.add( 'sk-theme-switch' );

	const transition = document.startViewTransition( swap );
	const state = { h: start };

	transition.ready
		.then( () => {
			gsap.to( state, {
				h: end,
				duration: DURATION,
				ease: 'power2.inOut',
				onUpdate: () =>
					root.style.setProperty( '--sk-h', `${ state.h }px` ),
			} );
		} )
		.catch( () => {} );

	transition.finished
		.catch( () => {} )
		.then( () => {
			gsap.killTweensOf( state );
			root.classList.remove( 'sk-theme-switch' );
			VARS.forEach( ( name ) => root.style.removeProperty( name ) );
			dawn.remove();
			running = false;
		} );
}

window.sekeco = Object.assign( window.sekeco || {}, { themeSwitch } );
