/**
 * Pointer interactions, ported to vanilla JS + GSAP from:
 * - React Bits "Spotlight Card" (soft spotlight, proximity fade, press flare)
 * - React Bits "Glare Hover" (a sheen sweeps across the card)
 * - React Bits "Tilted Card" (3D tilt toward the pointer)
 * - Primary buttons: the same neutral light on the pill, and a small lean toward the pointer
 * Border Beam (Magic UI) is pure CSS in the CTA block.
 *
 * Only fine pointers get these. Spotlight is user-driven so it also runs with
 * reduced motion; glare and tilt are skipped there.
 */
import { gsap } from 'gsap';

const fine = () =>
	window.matchMedia( '(hover: hover) and (pointer: fine)' ).matches;
const reduced = () =>
	window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

/* ------------------------------------------------------------------ */
/* Spotlight                                                           */
/* ------------------------------------------------------------------ */

const PROXIMITY = 80;
const SMOOTHING = 0.3;

function spotlight( reduce ) {
	const items = Array.from( document.querySelectorAll( '.sk-glow' ) ).map(
		( el ) => ( {
			el,
			x: 0,
			y: 0,
			p: 0,
			flare: 0,
			pressed: false,
			visible: true,
			raf: 0,
			last: 0,
		} )
	);

	if ( ! items.length ) {
		return;
	}

	const pointer = { x: 0, y: 0, active: false };

	const tick = ( item, now ) => {
		item.raf = 0;

		const dt = Math.min(
			0.05,
			Math.max( 0.001, ( now - item.last ) / 1000 )
		);
		item.last = now;

		const rect = item.el.getBoundingClientRect();
		let tx = item.x;
		let ty = item.y;
		let target = 0;

		if ( pointer.active ) {
			tx = pointer.x - rect.left;
			ty = pointer.y - rect.top;

			const gap = Math.hypot(
				Math.max( 0, -tx, tx - rect.width ),
				Math.max( 0, -ty, ty - rect.height )
			);

			if ( gap === 0 ) {
				target = 1;
			} else {
				target = Math.max( 0, 1 - gap / PROXIMITY ) ** 2;
			}
		}

		// The first frame jumps to the pointer instead of sliding in from 0,0.
		if ( item.p < 0.02 && target > 0 ) {
			item.x = tx;
			item.y = ty;
		}

		const follow = reduce
			? 1
			: 1 - Math.exp( -dt / ( 0.015 + SMOOTHING * 0.22 ) );
		item.x += ( tx - item.x ) * follow;
		item.y += ( ty - item.y ) * follow;

		const fade = reduce
			? 1
			: 1 - Math.exp( -dt / ( target > item.p ? 0.1 : 0.32 ) );
		item.p += ( target - item.p ) * fade;

		if ( Math.abs( target - item.p ) < 0.002 ) {
			item.p = target;
		}

		const press = item.pressed && ! reduce ? 1 : 0;
		item.flare +=
			( press - item.flare ) *
			( 1 - Math.exp( -dt / ( press ? 0.05 : 0.28 ) ) );

		if ( Math.abs( press - item.flare ) < 0.002 ) {
			item.flare = press;
		}

		const style = item.el.style;
		style.setProperty( '--sl-x', `${ item.x.toFixed( 1 ) }px` );
		style.setProperty( '--sl-y', `${ item.y.toFixed( 1 ) }px` );
		style.setProperty( '--sl-p', item.p.toFixed( 3 ) );
		style.setProperty( '--sl-flare', item.flare.toFixed( 3 ) );

		const moving =
			Math.abs( tx - item.x ) > 0.1 || Math.abs( ty - item.y ) > 0.1;
		const settled = ! moving && item.p === target && item.flare === press;

		if ( ! settled ) {
			item.raf = window.requestAnimationFrame( ( t ) => tick( item, t ) );
		}
	};

	const wake = ( item ) => {
		if ( item.raf || ! item.visible ) {
			return;
		}

		item.last = performance.now();
		item.raf = window.requestAnimationFrame( ( t ) => tick( item, t ) );
	};

	const observer = new window.IntersectionObserver( ( entries ) => {
		entries.forEach( ( entry ) => {
			const item = items.find( ( i ) => i.el === entry.target );

			if ( item ) {
				item.visible = entry.isIntersecting;
			}
		} );
	} );

	items.forEach( ( item ) => {
		observer.observe( item.el );
		item.el.addEventListener( 'pointerdown', () => {
			item.pressed = true;
			wake( item );
		} );
	} );

	let queued = false;

	const refresh = () => {
		queued = false;
		items.forEach( ( item ) => {
			if ( item.visible && ( pointer.active || item.p > 0 ) ) {
				wake( item );
			}
		} );
	};

	const schedule = () => {
		if ( ! queued ) {
			queued = true;
			window.requestAnimationFrame( refresh );
		}
	};

	window.addEventListener(
		'pointermove',
		( event ) => {
			if ( 'touch' === event.pointerType ) {
				return;
			}

			pointer.x = event.clientX;
			pointer.y = event.clientY;
			pointer.active = true;
			schedule();
		},
		{ passive: true }
	);

	const release = () => {
		items.forEach( ( item ) => {
			item.pressed = false;
		} );
		schedule();
	};

	window.addEventListener( 'pointerup', release );
	window.addEventListener( 'pointercancel', release );
	window.addEventListener( 'scroll', schedule, {
		passive: true,
		capture: true,
	} );
	document.addEventListener( 'pointerout', ( event ) => {
		if ( ! event.relatedTarget ) {
			pointer.active = false;
			schedule();
		}
	} );
}

/* ------------------------------------------------------------------ */
/* Glare hover                                                         */
/* ------------------------------------------------------------------ */

function glare() {
	document
		.querySelectorAll( '.sk-card--has-image .sk-card__link' )
		.forEach( ( link ) => {
			const wrap = document.createElement( 'span' );
			const band = document.createElement( 'span' );

			wrap.className = 'sk-glare';
			wrap.setAttribute( 'aria-hidden', 'true' );
			band.className = 'sk-glare__band';
			wrap.appendChild( band );
			link.appendChild( wrap );

			link.addEventListener( 'pointerenter', () => {
				gsap.killTweensOf( band );
				gsap.fromTo(
					band,
					{ x: 0, opacity: 1 },
					{
						x: () => link.clientWidth * 2.2,
						duration: 0.9,
						ease: 'power2.inOut',
						onComplete: () =>
							gsap.set( band, { opacity: 0, x: 0 } ),
					}
				);
			} );
		} );
}

/* ------------------------------------------------------------------ */
/* Tilt                                                                */
/* ------------------------------------------------------------------ */

const TILT = 5;

function tilt() {
	document
		.querySelectorAll( '.sk-projects__item .sk-card__link' )
		.forEach( ( link ) => {
			const rotateX = gsap.quickTo( link, 'rotationX', {
				duration: 0.5,
				ease: 'power3.out',
			} );
			const rotateY = gsap.quickTo( link, 'rotationY', {
				duration: 0.5,
				ease: 'power3.out',
			} );

			gsap.set( link, {
				transformPerspective: 1000,
				transformOrigin: 'center',
			} );

			link.addEventListener( 'pointermove', ( event ) => {
				const box = link.getBoundingClientRect();
				const px = ( event.clientX - box.left ) / box.width - 0.5;
				const py = ( event.clientY - box.top ) / box.height - 0.5;

				rotateX( -py * TILT * 2 );
				rotateY( px * TILT * 2 );
			} );

			link.addEventListener( 'pointerleave', () => {
				rotateX( 0 );
				rotateY( 0 );
			} );
		} );
}

/* ------------------------------------------------------------------ */
/* Primary buttons                                                     */
/* ------------------------------------------------------------------ */

const LEAN_X = 8;
const LEAN_Y = 6;

/**
 * The theme draws the light from --bx and --by and fades it with --bp (set on
 * :hover and :focus-visible). Here the pointer position is written to the pill
 * and, unless motion is reduced, the pill leans a few pixels toward it.
 *
 * @param {boolean} reduce Whether reduced motion is requested.
 */
function buttons( reduce ) {
	document
		.querySelectorAll(
			'.wp-block-button:not(.is-style-outline) > .wp-block-button__link, .sekeco-form__submit, .sekeco-form__next'
		)
		.forEach( ( el ) => {
			const moveX = reduce
				? null
				: gsap.quickTo( el, 'x', { duration: 0.5, ease: 'power3.out' } );
			const moveY = reduce
				? null
				: gsap.quickTo( el, 'y', { duration: 0.5, ease: 'power3.out' } );

			el.addEventListener( 'pointermove', ( event ) => {
				const box = el.getBoundingClientRect();

				if ( ! box.width || ! box.height ) {
					return;
				}

				const left = box.left - Number( gsap.getProperty( el, 'x' ) || 0 );
				const top = box.top - Number( gsap.getProperty( el, 'y' ) || 0 );
				const px = event.clientX - left;
				const py = event.clientY - top;

				el.style.setProperty( '--bx', `${ px.toFixed( 1 ) }px` );
				el.style.setProperty( '--by', `${ py.toFixed( 1 ) }px` );

				if ( moveX ) {
					moveX( ( px / box.width - 0.5 ) * LEAN_X );
					moveY( ( py / box.height - 0.5 ) * LEAN_Y );
				}
			} );

			el.addEventListener( 'pointerleave', () => {
				if ( moveX ) {
					moveX( 0 );
					moveY( 0 );
				}
			} );
		} );
}

export function initInteractions() {
	if ( ! fine() ) {
		return;
	}

	const reduce = reduced();

	spotlight( reduce );
	buttons( reduce );

	if ( reduce ) {
		return;
	}

	glare();
	tilt();
}
