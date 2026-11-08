/**
 * Stat block.
 */
import { registerBlockType } from '@wordpress/blocks';
import {
	InspectorControls,
	RichText,
	useBlockProps,
} from '@wordpress/block-editor';
import { PanelBody, SelectControl, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import metadata from './block.json';
import { blockIcon } from '../../shared/icon';
import { saveNull } from '../../shared/register';

const SOURCES = [
	{ label: __( 'Count of Works', 'sekeco' ), value: 'portfolio' },
	{ label: __( 'Count of Portfolio', 'sekeco' ), value: 'product' },
	{ label: __( 'Count of Team', 'sekeco' ), value: 'team' },
	{ label: __( 'Count of Journal posts', 'sekeco' ), value: 'post' },
	{ label: __( 'Custom value', 'sekeco' ), value: 'custom' },
];

function Edit( { attributes, setAttributes } ) {
	const { source, value, suffix, label } = attributes;
	const blockProps = useBlockProps( { className: 'sk-stat' } );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Stat', 'sekeco' ) }>
					<SelectControl
						__next40pxDefaultSize
						__nextHasNoMarginBottom
						label={ __( 'Source', 'sekeco' ) }
						value={ source }
						options={ SOURCES }
						onChange={ ( next ) =>
							setAttributes( { source: next } )
						}
					/>
					{ 'custom' === source && (
						<TextControl
							__next40pxDefaultSize
							__nextHasNoMarginBottom
							label={ __( 'Value', 'sekeco' ) }
							value={ value }
							onChange={ ( next ) =>
								setAttributes( { value: next } )
							}
						/>
					) }
					<TextControl
						__next40pxDefaultSize
						__nextHasNoMarginBottom
						label={ __( 'Suffix (for example +)', 'sekeco' ) }
						value={ suffix }
						onChange={ ( next ) =>
							setAttributes( { suffix: next } )
						}
					/>
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				<span className="sk-stat__value">
					{ 'custom' === source
						? value || '0'
						: __( 'Auto', 'sekeco' ) }
					{ suffix }
				</span>
				<RichText
					tagName="span"
					className="sk-stat__label"
					value={ label }
					allowedFormats={ [] }
					placeholder={ __( 'Label', 'sekeco' ) }
					onChange={ ( next ) => setAttributes( { label: next } ) }
				/>
			</div>
		</>
	);
}

registerBlockType( metadata.name, {
	icon: blockIcon( 'diagram-up' ),
	edit: Edit,
	save: saveNull,
} );
