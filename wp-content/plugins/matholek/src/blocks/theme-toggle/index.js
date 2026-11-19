/**
 * Light/dark toggle block.
 */
import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

import metadata from './block.json';

function Edit() {
	const blockProps = useBlockProps( {
		className: 'mk-toggle',
		type: 'button',
		'aria-label': __( 'Ganti mode terang/gelap', 'matholek' ),
	} );

	return (
		<button { ...blockProps }>
			<span
				className="mk-icon mk-icon--moon mk-toggle__moon"
				aria-hidden="true"
			/>
			<span
				className="mk-icon mk-icon--sun-2 mk-toggle__sun"
				aria-hidden="true"
			/>
		</button>
	);
}

registerBlockType( metadata.name, {
	icon: 'lightbulb',
	edit: Edit,
	save: () => null,
} );
