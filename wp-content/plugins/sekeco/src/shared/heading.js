/**
 * Heading level control shared by blocks that print a heading.
 *
 * The level is a content decision (the page outline), so every block that
 * renders a heading offers it instead of fixing one.
 */
import { SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export function HeadingLevelControl( { value, onChange, min = 1, max = 4 } ) {
	const options = [];

	for ( let level = min; level <= max; level++ ) {
		options.push( { label: `H${ level }`, value: String( level ) } );
	}

	return (
		<SelectControl
			__next40pxDefaultSize
			__nextHasNoMarginBottom
			label={ __( 'Heading level', 'sekeco' ) }
			help={ __(
				'Keep one H1 per page and do not skip levels.',
				'sekeco'
			) }
			value={ String( value ) }
			options={ options }
			onChange={ ( next ) => onChange( Number( next ) ) }
		/>
	);
}
