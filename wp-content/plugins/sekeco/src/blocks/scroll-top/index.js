/**
 * Scroll to top block.
 */
import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

import './style.scss';
import metadata from './block.json';
import { Icon, blockIcon } from '../../shared/icon';
import { saveNull } from '../../shared/register';

function Edit() {
	const blockProps = useBlockProps( {
		className: 'sk-scrolltop sk-scrolltop--static is-visible',
		type: 'button',
		'aria-label': __( 'Back to top', 'sekeco' ),
	} );

	return (
		<button { ...blockProps }>
			<Icon name="arrow-up" size={ 22 } />
		</button>
	);
}

registerBlockType( metadata.name, {
	icon: blockIcon( 'arrow-up' ),
	edit: Edit,
	save: saveNull,
} );
