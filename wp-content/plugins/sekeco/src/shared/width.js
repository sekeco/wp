/**
 * Content width control shared by Section, Hero and CTA.
 *
 * Narrow  the theme's contentSize (theme.json settings.layout.contentSize)
 * Default the block's own column (1240px)
 * Wide    the theme's wideSize (theme.json settings.layout.wideSize)
 * Full    the whole screen
 *
 * The choice is the `width` attribute; sk-w--narrow|wide|full is the class that
 * the block's stylesheet reads (the default adds none).
 */
import { SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export const widthClass = ( width ) =>
	[ 'narrow', 'wide', 'full' ].includes( width ) ? ` sk-w--${ width }` : '';

export function WidthControl( { value, onChange } ) {
	return (
		<SelectControl
			__next40pxDefaultSize
			__nextHasNoMarginBottom
			label={ __( 'Content width', 'sekeco' ) }
			help={ __(
				'Narrow follows the theme content size, Wide the theme wide size, Full uses the whole screen.',
				'sekeco'
			) }
			value={ value || 'default' }
			options={ [
				{ label: __( 'Narrow', 'sekeco' ), value: 'narrow' },
				{ label: __( 'Default', 'sekeco' ), value: 'default' },
				{ label: __( 'Wide', 'sekeco' ), value: 'wide' },
				{ label: __( 'Full', 'sekeco' ), value: 'full' },
			] }
			onChange={ onChange }
		/>
	);
}
