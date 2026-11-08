/**
 * Stats block (container).
 */
import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';

import './style.scss';
import metadata from './block.json';
import { blockIcon } from '../../shared/icon';
import { saveInner } from '../../shared/register';

const TEMPLATE = [
	[ 'sekeco/stat', { source: 'portfolio', label: 'Works shipped' } ],
	[
		'sekeco/stat',
		{ source: 'product', label: 'Products in the portfolio' },
	],
	[ 'sekeco/stat', { source: 'team', label: 'People on the team' } ],
];

function Edit() {
	const blockProps = useBlockProps( { className: 'sk-stats' } );
	const innerProps = useInnerBlocksProps( blockProps, {
		allowedBlocks: [ 'sekeco/stat' ],
		template: TEMPLATE,
		orientation: 'horizontal',
	} );

	return <div { ...innerProps } />;
}

registerBlockType( metadata.name, {
	icon: blockIcon( 'diagram-up' ),
	edit: Edit,
	save: saveInner,
} );
