/**
 * Testimonial slide block.
 */
import { registerBlockType } from '@wordpress/blocks';
import { RichText, useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

import metadata from './block.json';

function Edit( { attributes, setAttributes } ) {
	const { quote, name, meta } = attributes;
	const blockProps = useBlockProps( { className: 'mk-slide' } );

	return (
		<figure { ...blockProps }>
			<RichText
				tagName="blockquote"
				className="mk-slide__quote"
				value={ quote }
				allowedFormats={ [ 'core/bold', 'core/italic' ] }
				placeholder={ __(
					'Kutipan alumni (tanpa tanda petik)…',
					'matholek'
				) }
				onChange={ ( next ) => setAttributes( { quote: next } ) }
			/>
			<figcaption className="mk-slide__by">
				<RichText
					tagName="b"
					value={ name }
					allowedFormats={ [] }
					placeholder={ __( 'Nama alumni', 'matholek' ) }
					onChange={ ( next ) => setAttributes( { name: next } ) }
				/>
				{ ' · ' }
				<RichText
					tagName="span"
					value={ meta }
					allowedFormats={ [] }
					placeholder={ __( 'Angkatan 2005 · Dosen', 'matholek' ) }
					onChange={ ( next ) => setAttributes( { meta: next } ) }
				/>
			</figcaption>
		</figure>
	);
}

registerBlockType( metadata.name, {
	icon: 'format-quote',
	edit: Edit,
	save: () => null,
} );
