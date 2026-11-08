/**
 * Capabilities block (container).
 */
import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';

import './style.scss';
import metadata from './block.json';
import { blockIcon } from '../../shared/icon';
import { saveInner } from '../../shared/register';

function Edit() {
	const blockProps = useBlockProps( { className: 'sk-capabilities' } );
	const innerProps = useInnerBlocksProps( blockProps, {
		allowedBlocks: [ 'sekeco/capability' ],
		template: [ [ 'sekeco/capability' ] ],
		renderAppender: undefined,
	} );

	return <div { ...innerProps } />;
}

registerBlockType( metadata.name, {
	icon: blockIcon( 'widget-4' ),
	edit: Edit,
	save: saveInner,
} );
