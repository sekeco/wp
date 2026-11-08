/**
 * Form block registration.
 */
import { registerBlockType } from '@wordpress/blocks';

import './style.scss';
import Edit from './edit';
import save from './save';
import metadata from './block.json';
import { blockIcon } from '../../shared/icon';

registerBlockType( metadata.name, {
	icon: blockIcon( 'document-add' ),
	edit: Edit,
	save,
} );
