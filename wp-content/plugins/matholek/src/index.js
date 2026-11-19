/**
 * Editor entry: show `matholek/since` bound values in the editor.
 */
import { registerBlockBindingsSource } from '@wordpress/blocks';

registerBlockBindingsSource( {
	name: 'matholek/since',
	getValues( { bindings } ) {
		const values = {};

		for ( const [ attribute, { args = {} } ] of Object.entries(
			bindings
		) ) {
			const year = parseInt( args.year, 10 );
			values[ attribute ] = year
				? `${ new Date().getFullYear() - year }${ args.suffix || '' }`
				: '';
		}

		return values;
	},
} );
