/**
 * Donut ratio block.
 */
import { registerBlockType } from '@wordpress/blocks';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, RangeControl, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import metadata from './block.json';

function Edit( { attributes, setAttributes } ) {
	const { value, label } = attributes;
	const blockProps = useBlockProps( { className: 'mk-ratio' } );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Rasio', 'matholek' ) }>
					<RangeControl
						__next40pxDefaultSize
						__nextHasNoMarginBottom
						label={ __( 'Persentase terisi', 'matholek' ) }
						value={ value }
						min={ 0 }
						max={ 100 }
						onChange={ ( next ) =>
							setAttributes( { value: next ?? 0 } )
						}
					/>
					<TextControl
						__next40pxDefaultSize
						__nextHasNoMarginBottom
						label={ __( 'Label pembaca layar', 'matholek' ) }
						value={ label }
						onChange={ ( next ) =>
							setAttributes( { label: next } )
						}
					/>
				</PanelBody>
			</InspectorControls>
			<svg { ...blockProps } viewBox="0 0 36 36" aria-hidden="true">
				<circle
					className="mk-ratio__track"
					cx="18"
					cy="18"
					r="15.9"
					fill="none"
					strokeWidth="4"
				/>
				<circle
					className="mk-ratio__bar"
					cx="18"
					cy="18"
					r="15.9"
					fill="none"
					strokeWidth="4"
					strokeDasharray={ `${ value } ${ 100 - value }` }
					strokeLinecap="round"
				/>
			</svg>
		</>
	);
}

registerBlockType( metadata.name, {
	icon: 'chart-pie',
	edit: Edit,
	save: () => null,
} );
