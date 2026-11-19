/**
 * Copy the self-hosted font files into the Matholek theme.
 *
 * Manrope (variable, latin + latin-ext) for text, Amiri 400 (arabic)
 * for Arabic accents. Run `bun run fonts` after updating the font packages.
 */
import { copyFileSync, mkdirSync } from 'node:fs';
import { dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = resolve( dirname( fileURLToPath( import.meta.url ) ), '..' );
const dest = resolve( root, '../../themes/matholek/assets/fonts' );

const FILES = [
	'@fontsource-variable/manrope/files/manrope-latin-wght-normal.woff2',
	'@fontsource-variable/manrope/files/manrope-latin-ext-wght-normal.woff2',
	'@fontsource/amiri/files/amiri-arabic-400-normal.woff2',
];

mkdirSync( dest, { recursive: true } );

for ( const file of FILES ) {
	copyFileSync(
		resolve( root, 'node_modules', file ),
		resolve( dest, file.split( '/' ).pop() )
	);
}

// eslint-disable-next-line no-console
console.log( `Copied ${ FILES.length } fonts.` );
