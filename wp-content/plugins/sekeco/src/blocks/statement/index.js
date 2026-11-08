/**
 * Statement block.
 */
import { registerBlockType } from '@wordpress/blocks';
import {
	InspectorControls,
	RichText,
	useBlockProps,
} from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import './style.scss';
import metadata from './block.json';
import { Icon, blockIcon } from '../../shared/icon';
import { saveNull, DISPLAY_FORMATS } from '../../shared/register';

function Edit( { attributes, setAttributes } ) {
	const { text, linkLabel, linkUrl } = attributes;
	const blockProps = useBlockProps( { className: 'sk-statement' } );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Link', 'sekeco' ) }>
					<TextControl
						__next40pxDefaultSize
						__nextHasNoMarginBottom
						label={ __( 'Link label', 'sekeco' ) }
						value={ linkLabel }
						onChange={ ( value ) =>
							setAttributes( { linkLabel: value } )
						}
					/>
					<TextControl
						__next40pxDefaultSize
						__nextHasNoMarginBottom
						label={ __( 'Link URL', 'sekeco' ) }
						value={ linkUrl }
						onChange={ ( value ) =>
							setAttributes( { linkUrl: value } )
						}
					/>
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				<RichText
					tagName="p"
					className="sk-statement__text"
					value={ text }
					allowedFormats={ DISPLAY_FORMATS }
					placeholder={ __(
						'Write the statement. Italic text is dimmed.',
						'sekeco'
					) }
					onChange={ ( value ) => setAttributes( { text: value } ) }
				/>
				{ linkLabel && (
					<span className="sk-link">
						{ linkLabel }
						<Icon name="arrow-right" size={ 20 } />
					</span>
				) }
			</div>
		</>
	);
}

registerBlockType( metadata.name, {
	icon: blockIcon( 'notes' ),
	edit: Edit,
	save: saveNull,
} );
