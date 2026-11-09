/**
 * Theme toggle block.
 */
import { registerBlockType } from '@wordpress/blocks';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, SelectControl, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import './style.scss';
import metadata from './block.json';
import { Icon, blockIcon } from '../../shared/icon';
import { saveNull } from '../../shared/register';

function Edit( { attributes, setAttributes } ) {
	const { variant, floating } = attributes;
	const blockProps = useBlockProps( {
		className: `sk-theme-toggle sk-theme-toggle--${ variant }`,
		type: 'button',
		'aria-label': __( 'Switch between light and dark', 'sekeco' ),
	} );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Theme toggle', 'sekeco' ) }>
					<SelectControl
						__next40pxDefaultSize
						__nextHasNoMarginBottom
						label={ __( 'Style', 'sekeco' ) }
						value={ variant }
						options={ [
							{ label: __( 'Icon', 'sekeco' ), value: 'icon' },
							{
								label: __( 'Pill with labels', 'sekeco' ),
								value: 'pill',
							},
							{
								label: __( 'Vertical rail', 'sekeco' ),
								value: 'rail',
							},
						] }
						onChange={ ( value ) =>
							setAttributes( { variant: value } )
						}
					/>
					{ 'icon' === variant && (
						<ToggleControl
							__nextHasNoMarginBottom
							label={ __( 'Float above search', 'sekeco' ) }
							help={ __(
								'Pins the icon to the lower-left corner above the search button on desktop. Hidden on small screens, where the menu has its own switch.',
								'sekeco'
							) }
							checked={ !! floating }
							onChange={ ( value ) =>
								setAttributes( { floating: value } )
							}
						/>
					) }
				</PanelBody>
			</InspectorControls>
			<button { ...blockProps }>
				{ 'icon' === variant ? (
					<>
						<Icon
							name="sun-2"
							size={ 22 }
							className="sk-theme-toggle__sun"
						/>
						<Icon
							name="moon"
							size={ 22 }
							className="sk-theme-toggle__moon"
						/>
					</>
				) : (
					<>
						<span className="sk-theme-toggle__opt" data-mode="dark">
							<Icon name="moon" size={ 18 } />
							{ __( 'Dark', 'sekeco' ) }
						</span>
						<span
							className="sk-theme-toggle__opt"
							data-mode="light"
						>
							<Icon name="sun-2" size={ 18 } />
							{ __( 'Light', 'sekeco' ) }
						</span>
					</>
				) }
			</button>
		</>
	);
}

registerBlockType( metadata.name, {
	icon: blockIcon( 'sun-2' ),
	edit: Edit,
	save: saveNull,
} );
