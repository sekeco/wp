/**
 * Sekeco motion runtime (GSAP).
 *
 * One authored moment per surface: the hero headline rises from a mask,
 * statements light up word by word, project images drift against the scroll,
 * counters tick once, and component groups reveal in one batch. Everything
 * starts from a visible default; pre-reveal states are only set under
 * html.sekeco-js and are released by a CSS failsafe if this file never runs.
 */
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { SplitText } from 'gsap/SplitText';

const root = document.documentElement;
const EASE = 'expo.out';

const REVEAL = [
	'.sk-section-header',
	'.sk-capability',
	'.sk-process-step',
	'.sk-stat',
	'.sk-faq-item',
	'.sk-projects--grid .sk-projects__item',
	'.sk-projects--list .sk-row',
	'.sk-marquee',
	'.sk-cta__inner > *',
	'.sk-statement .sk-link',
].join( ',' );

const reduced = () =>
	window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

const release = () => root.classList.add( 'sekeco-ready' );

function heroIntro() {
	document.querySelectorAll( '.sk-hero' ).forEach( ( hero ) => {
		const title = hero.querySelector( '.sk-hero__title' );
		const rest = hero.querySelectorAll(
			'.sk-hero__lead, .sk-hero__actions'
		);
		const cards = hero.querySelectorAll(
			'.sk-hero__reel .sk-projects__item'
		);
		const timeline = gsap.timeline( { defaults: { ease: EASE } } );

		if ( title ) {
			const split = new SplitText( title, {
				type: 'lines',
				mask: 'lines',
				linesClass: 'sk-line',
			} );

			title.style.visibility = 'visible';
			timeline.from( split.lines, {
				yPercent: 110,
				duration: 1.2,
				stagger: 0.09,
			} );
		}

		timeline
			.from(
				rest,
				{ y: 24, opacity: 0, duration: 0.9, stagger: 0.1 },
				title ? '-=0.7' : 0
			)
			.from(
				cards,
				{ y: 80, opacity: 0, duration: 1.1, stagger: 0.08 },
				'-=0.7'
			);
	} );
}

function statements() {
	document.querySelectorAll( '[data-sk-words]' ).forEach( ( node ) => {
		const split = new SplitText( node, {
			type: 'words',
			wordsClass: 'sk-word',
		} );

		gsap.fromTo(
			split.words,
			{ opacity: 0.16 },
			{
				opacity: 1,
				ease: 'none',
				stagger: 0.12,
				scrollTrigger: {
					trigger: node,
					start: 'top 82%',
					end: 'bottom 52%',
					scrub: true,
				},
			}
		);
	} );
}

function parallax() {
	document.querySelectorAll( '[data-sk-parallax]' ).forEach( ( media ) => {
		const img = media.querySelector( '.sk-card__img' );

		if ( ! img ) {
			return;
		}

		const amount = parseFloat( media.dataset.skParallax ) || 0.1;

		gsap.fromTo(
			img,
			{ yPercent: -amount * 100 },
			{
				yPercent: amount * 100,
				ease: 'none',
				scrollTrigger: {
					trigger: media,
					start: 'top bottom',
					end: 'bottom top',
					scrub: true,
				},
			}
		);
	} );
}

function counters() {
	document.querySelectorAll( '[data-sk-count]' ).forEach( ( node ) => {
		const target = parseInt( node.dataset.skCount, 10 );

		if ( Number.isNaN( target ) ) {
			return;
		}

		const state = { value: 0 };

		ScrollTrigger.create( {
			trigger: node,
			start: 'top 90%',
			once: true,
			onEnter: () => {
				gsap.to( state, {
					value: target,
					duration: 1.6,
					ease: 'power3.out',
					onUpdate: () => {
						node.textContent = String( Math.round( state.value ) );
					},
					onComplete: () => {
						node.textContent = String( target );
					},
				} );
			},
		} );
	} );
}

function reveals() {
	const targets = gsap.utils
		.toArray( REVEAL )
		.filter( ( node ) => ! node.closest( '.sk-hero' ) );

	// Same task as release(): the CSS pre-state hands over to inline state.
	gsap.set( targets, { opacity: 0, y: 32 } );

	ScrollTrigger.batch( targets, {
		start: 'top 90%',
		once: true,
		onEnter: ( batch ) =>
			gsap.to( batch, {
				opacity: 1,
				y: 0,
				duration: 1,
				ease: EASE,
				stagger: 0.08,
				overwrite: true,
				clearProps: 'opacity,transform',
			} ),
	} );
}

function header() {
	const bar = document.querySelector( '.sekeco-header' );

	if ( ! bar ) {
		return;
	}

	let last = window.scrollY;
	let ticking = false;

	const update = () => {
		const y = window.scrollY;
		const open = document.querySelector(
			'.wp-block-navigation__responsive-container.is-menu-open'
		);

		bar.classList.toggle( 'is-scrolled', y > 24 );
		bar.classList.toggle( 'is-hidden', ! open && y > 240 && y > last + 4 );

		if ( y < last - 4 || y < 240 ) {
			bar.classList.remove( 'is-hidden' );
		}

		last = y;
		ticking = false;
	};

	window.addEventListener(
		'scroll',
		() => {
			if ( ! ticking ) {
				ticking = true;
				window.requestAnimationFrame( update );
			}
		},
		{ passive: true }
	);
	update();
}

function init() {
	if ( ! root.classList.contains( 'sekeco-js' ) ) {
		root.classList.add( 'sekeco-js' );
	}

	header();

	if ( reduced() ) {
		release();
		return;
	}

	gsap.registerPlugin( ScrollTrigger, SplitText );

	try {
		heroIntro();
		statements();
		parallax();
		counters();
		reveals();
	} catch ( error ) {
		// Never leave content hidden because a motion step failed.
		// eslint-disable-next-line no-console
		console.error( 'Sekeco motion', error );
	}

	release();
	window.addEventListener( 'load', () => ScrollTrigger.refresh() );

	if ( document.fonts && document.fonts.ready ) {
		document.fonts.ready.then( () => ScrollTrigger.refresh() );
	}
}

if ( 'loading' === document.readyState ) {
	document.addEventListener( 'DOMContentLoaded', init );
} else {
	init();
}
