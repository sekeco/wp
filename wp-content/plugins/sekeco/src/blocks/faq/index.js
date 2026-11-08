/**
 * FAQ block (container).
 */
import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';

import './style.scss';
import metadata from './block.json';
import { blockIcon } from '../../shared/icon';
import { saveInner } from '../../shared/register';

function Edit() {
	const blockProps = useBlockProps( { className: 'sk-faq' } );
	const innerProps = useInnerBlocksProps( blockProps, {
		allowedBlocks: [ 'sekeco/faq-item' ],
		template: [ [ 'sekeco/faq-item' ] ],
	} );

	return <div { ...innerProps } />;
}

registerBlockType( metadata.name, {
	icon: blockIcon( 'chat-round-dots' ),
	edit: Edit,
	save: saveInner,
} );
