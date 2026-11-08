/**
 * Solar Line Duotone icons in the editor.
 *
 * The icon map is printed by PHP (window.sekecoIcons) so it is not bundled
 * once per block. Always read it at render time.
 */
import { SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export const getIcons = () => window.sekecoIcons || {};

export function Icon( { name, size = 24, className = '' } ) {
	const body = getIcons()[ name ];

	if ( ! body ) {
		return null;
	}

	return (
		<svg
			className={ `sekeco-icon-svg ${ className }`.trim() }
			width={ size }
			height={ size }
			viewBox="0 0 24 24"
			fill="none"
			aria-hidden="true"
			focusable="false"
			dangerouslySetInnerHTML={ { __html: body } }
		/>
	);
}

/**
 * Block icon descriptor: rendered lazily, after the icon map exists.
 *
 * @param {string} name Solar icon name.
 * @return {Object} Block icon.
 */
export const blockIcon = ( name ) => ( {
	src: () => <Icon name={ name } />,
} );

export function IconSelect( { value, onChange, label } ) {
	const options = Object.keys( getIcons() ).map( ( name ) => ( {
		label: name.replace( /-/g, ' ' ),
		value: name,
	} ) );

	return (
		<SelectControl
			__next40pxDefaultSize
			__nextHasNoMarginBottom
			label={ label || __( 'Icon', 'sekeco' ) }
			value={ value }
			options={ options }
			onChange={ onChange }
		/>
	);
}
