/**
 * Editor entry point for the Sekeco plugin.
 */
import { registerPlugin } from '@wordpress/plugins';

import './motion/editor';
import MetaPanels from './meta/panels';

registerPlugin( 'sekeco-meta-panels', {
	render: MetaPanels,
} );
