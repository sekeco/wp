/**
 * Mounts a background picture on a canvas.
 *
 * Works on the front end and inside the block editor (the canvas may live in an
 * iframe, so everything is taken from the canvas's own window). Time comes from
 * the GSAP ticker; the picture renders at half resolution, at 30fps, only while
 * visible, and once (no loop) when the visitor prefers reduced motion. The
 * shader reads whether the copy is light or dark from the block's text colour,
 * so it follows the site's light and dark mode and the block's tone.
 */
import { gsap } from 'gsap';
import { HEAD, SHADERS } from './shaders';

const VERTEX = 'attribute vec2 p;void main(){gl_Position=vec4(p,0.,1.);}';

const noop = () => {};

function isLightText( canvas ) {
	const win = canvas.ownerDocument.defaultView;
	const host = canvas.parentElement || canvas;
	const match = /rgba?\(\s*([\d.]+)[ ,]+([\d.]+)[ ,]+([\d.]+)/.exec(
		win.getComputedStyle( host ).color
	);

	if ( ! match ) {
		return true;
	}

	return (
		( +match[ 1 ] * 0.299 + +match[ 2 ] * 0.587 + +match[ 3 ] * 0.114 ) /
			255 >
		0.5
	);
}

export function mountBackground( canvas, type ) {
	const body = SHADERS[ type ];

	if ( ! body || ! canvas ) {
		return noop;
	}

	const win = canvas.ownerDocument.defaultView;
	const gl = canvas.getContext( 'webgl', {
		antialias: false,
		alpha: false,
		powerPreference: 'low-power',
	} );

	if ( ! gl ) {
		return noop;
	}

	const compile = ( kind, source ) => {
		const shader = gl.createShader( kind );
		gl.shaderSource( shader, source );
		gl.compileShader( shader );
		return shader;
	};

	const program = gl.createProgram();
	gl.attachShader( program, compile( gl.VERTEX_SHADER, VERTEX ) );
	gl.attachShader( program, compile( gl.FRAGMENT_SHADER, HEAD + body ) );
	gl.linkProgram( program );

	if ( ! gl.getProgramParameter( program, gl.LINK_STATUS ) ) {
		return noop;
	}

	gl.useProgram( program );
	gl.bindBuffer( gl.ARRAY_BUFFER, gl.createBuffer() );
	gl.bufferData(
		gl.ARRAY_BUFFER,
		new Float32Array( [ -1, -1, 1, -1, -1, 1, 1, 1 ] ),
		gl.STATIC_DRAW
	);
	const loc = gl.getAttribLocation( program, 'p' );
	gl.enableVertexAttribArray( loc );
	gl.vertexAttribPointer( loc, 2, gl.FLOAT, false, 0, 0 );

	const uR = gl.getUniformLocation( program, 'R' );
	const uT = gl.getUniformLocation( program, 'T' );
	const uD = gl.getUniformLocation( program, 'D' );

	const reduce = win.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	let time = 14 + Math.random() * 60;
	let dark = isLightText( canvas ) ? 1 : 0;
	let visible = true;
	let skip = false;

	const draw = () => {
		const w = Math.max( 2, Math.round( canvas.clientWidth * 0.5 ) );
		const h = Math.max( 2, Math.round( canvas.clientHeight * 0.5 ) );

		if ( canvas.width !== w || canvas.height !== h ) {
			canvas.width = w;
			canvas.height = h;
			gl.viewport( 0, 0, w, h );
		}

		gl.uniform2f( uR, w, h );
		gl.uniform1f( uT, time );
		gl.uniform1f( uD, dark );
		gl.drawArrays( gl.TRIANGLE_STRIP, 0, 4 );
	};

	const tick = ( _time, deltaTime ) => {
		skip = ! skip;

		if ( skip || ! visible || canvas.ownerDocument.hidden ) {
			return;
		}

		time += Math.min( 100, deltaTime ) / 1000;
		draw();
	};

	draw();
	canvas.classList.add( 'is-ready' );

	if ( ! reduce ) {
		gsap.ticker.add( tick );
	}

	const resize = new win.ResizeObserver( draw );
	resize.observe( canvas );

	const view = new win.IntersectionObserver(
		( entries ) => {
			visible = entries[ entries.length - 1 ].isIntersecting;
		},
		{ rootMargin: '120px' }
	);
	view.observe( canvas );

	const theme = new win.MutationObserver( () => {
		dark = isLightText( canvas ) ? 1 : 0;
		draw();
	} );
	theme.observe( canvas.ownerDocument.documentElement, {
		attributes: true,
		attributeFilter: [ 'data-theme', 'class' ],
	} );

	return () => {
		gsap.ticker.remove( tick );
		resize.disconnect();
		view.disconnect();
		theme.disconnect();
		const lose = gl.getExtension( 'WEBGL_lose_context' );

		if ( lose ) {
			lose.loseContext();
		}
	};
}
