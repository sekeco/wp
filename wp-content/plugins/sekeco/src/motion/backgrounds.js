/**
 * Front end: start the background picture of every Hero and CTA that has one.
 */
import { mountBackground } from '../shared/backgrounds/mount';

const start = () => {
	document
		.querySelectorAll( 'canvas.sk-bg[data-sk-bg]' )
		.forEach( ( canvas ) => {
			if ( 'true' === canvas.dataset.skBgReady ) {
				return;
			}

			canvas.dataset.skBgReady = 'true';
			mountBackground( canvas, canvas.dataset.skBg );
		} );
};

if ( 'loading' === document.readyState ) {
	document.addEventListener( 'DOMContentLoaded', start );
} else {
	start();
}
