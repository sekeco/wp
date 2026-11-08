/**
 * Full-screen search block.
 */
import { registerBlockType } from '@wordpress/blocks';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import './style.scss';
import metadata from './block.json';
import { Icon, blockIcon } from '../../shared/icon';
import { saveNull } from '../../shared/register';

function Edit( { attributes, setAttributes } ) {
	const { floating, placeholder } = attributes;
	const blockProps = useBlockProps( {
		className: 'sk-search__trigger sk-search__trigger--inline',
		type: 'button',
		'aria-label': __( 'Search', 'sekeco' ),
	} );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Search', 'sekeco' ) }>
					<ToggleControl
						__nextHasNoMarginBottom
						label={ __( 'Float at the bottom left', 'sekeco' ) }
						help={ __(
							'Off places the button inline where you insert it.',
							'sekeco'
						) }
						checked={ floating }
						onChange={ ( value ) =>
							setAttributes( { floating: value } )
						}
					/>
					<TextControl
						__next40pxDefaultSize
						__nextHasNoMarginBottom
						label={ __( 'Input placeholder', 'sekeco' ) }
						value={ placeholder }
						onChange={ ( value ) =>
							setAttributes( { placeholder: value } )
						}
					/>
				</PanelBody>
			</InspectorControls>
			<button { ...blockProps }>
				<Icon name="magnifer" size={ 22 } />
			</button>
		</>
	);
}

registerBlockType( metadata.name, {
	icon: blockIcon( 'magnifer' ),
	edit: Edit,
	save: saveNull,
} );
