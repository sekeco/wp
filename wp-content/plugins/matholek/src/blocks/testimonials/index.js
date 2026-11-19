/**
 * Testimonial slider block. In the editor every slide is listed for editing.
 */
import { registerBlockType } from '@wordpress/blocks';
import {
	InnerBlocks,
	RichText,
	useBlockProps,
	useInnerBlocksProps,
} from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

import metadata from './block.json';

const TEMPLATE = [
	[ 'matholek/testimonial' ],
	[ 'matholek/testimonial' ],
	[ 'matholek/testimonial' ],
];

function Edit( { attributes, setAttributes } ) {
	const blockProps = useBlockProps( { className: 'mk-testi is-editing' } );
	const innerBlocksProps = useInnerBlocksProps(
		{ className: 'mk-testi__track' },
		{
			allowedBlocks: [ 'matholek/testimonial' ],
			template: TEMPLATE,
		}
	);

	return (
		<div { ...blockProps }>
			<RichText
				tagName="p"
				className="mk-testi__label"
				value={ attributes.label }
				allowedFormats={ [] }
				placeholder={ __( 'Label', 'matholek' ) }
				onChange={ ( label ) => setAttributes( { label } ) }
			/>
			<div { ...innerBlocksProps } />
		</div>
	);
}

registerBlockType( metadata.name, {
	icon: 'format-quote',
	edit: Edit,
	save: () => <InnerBlocks.Content />,
} );
