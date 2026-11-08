/**
 * Process block (container).
 */
import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';

import './style.scss';
import metadata from './block.json';
import { blockIcon } from '../../shared/icon';
import { saveInner } from '../../shared/register';

function Edit() {
	const blockProps = useBlockProps( { className: 'sk-process' } );
	const innerProps = useInnerBlocksProps( blockProps, {
		allowedBlocks: [ 'sekeco/process-step' ],
		template: [ [ 'sekeco/process-step' ] ],
		orientation: 'horizontal',
	} );

	return <ol { ...innerProps } />;
}

registerBlockType( metadata.name, {
	icon: blockIcon( 'clipboard-list' ),
	edit: Edit,
	save: saveInner,
} );
