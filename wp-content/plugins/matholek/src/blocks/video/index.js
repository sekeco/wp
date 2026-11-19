/**
 * Video play button block.
 */
import { registerBlockType } from '@wordpress/blocks';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import metadata from './block.json';

function Edit( { attributes, setAttributes } ) {
	const { url, label } = attributes;
	const blockProps = useBlockProps( { className: 'mk-video' } );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Video', 'matholek' ) }>
					<TextControl
						__next40pxDefaultSize
						__nextHasNoMarginBottom
						type="url"
						label={ __( 'URL video (YouTube)', 'matholek' ) }
						help={ __(
							'Dibuka di tab baru. Kosongkan bila belum ada; tombol tetap tampil.',
							'matholek'
						) }
						value={ url }
						onChange={ ( next ) => setAttributes( { url: next } ) }
					/>
					<TextControl
						__next40pxDefaultSize
						__nextHasNoMarginBottom
						label={ __( 'Label pembaca layar', 'matholek' ) }
						placeholder={ __( 'Putar video', 'matholek' ) }
						value={ label }
						onChange={ ( next ) =>
							setAttributes( { label: next } )
						}
					/>
				</PanelBody>
			</InspectorControls>
			<span { ...blockProps }>
				<span className="mk-video__play" aria-hidden="true">
					▶
				</span>
			</span>
		</>
	);
}

registerBlockType( metadata.name, {
	icon: 'video-alt3',
	edit: Edit,
	save: () => null,
} );
