/**
 * Editor pieces for the Background option: the Inspector control and the
 * live canvas shown inside the block.
 */
// WordPress provides it at runtime (wp-element); the build marks it external.
// eslint-disable-next-line import/no-extraneous-dependencies
import { useEffect, useRef } from '@wordpress/element';
import { SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import { mountBackground } from './mount';

export const BACKGROUNDS = [
	{ label: __( 'None', 'sekeco' ), value: 'none' },
	{ label: __( 'Satin (folds of silk)', 'sekeco' ), value: 'satin' },
	{ label: __( 'Dusk (sky and clouds)', 'sekeco' ), value: 'dusk' },
	{ label: __( 'Aurora (curtains of light)', 'sekeco' ), value: 'aurora' },
	{ label: __( 'Nebula (dust and stars)', 'sekeco' ), value: 'nebula' },
];

const BACKGROUND_TYPES = [ 'satin', 'dusk', 'aurora', 'nebula' ];

export const backgroundClass = ( bg ) =>
	BACKGROUND_TYPES.includes( bg ) ? ` sk-bg-on sk-bg--${ bg }` : '';

export function BackgroundControl( { value, onChange } ) {
	return (
		<SelectControl
			__next40pxDefaultSize
			__nextHasNoMarginBottom
			label={ __( 'Background', 'sekeco' ) }
			help={ __(
				'A moving picture behind the copy. It follows light and dark mode and pauses off screen.',
				'sekeco'
			) }
			value={ value || 'none' }
			options={ BACKGROUNDS }
			onChange={ onChange }
		/>
	);
}

export function BackgroundCanvas( { type } ) {
	const ref = useRef();

	useEffect( () => {
		if ( ! ref.current ) {
			return undefined;
		}

		return mountBackground( ref.current, type );
	}, [ type ] );

	if ( ! BACKGROUND_TYPES.includes( type ) ) {
		return null;
	}

	return (
		<canvas
			key={ type }
			ref={ ref }
			className="sk-bg"
			data-sk-bg={ type }
			aria-hidden="true"
		/>
	);
}
