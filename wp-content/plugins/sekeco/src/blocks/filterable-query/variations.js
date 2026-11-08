/**
 * Filterable Query variations.
 */
import { __ } from '@wordpress/i18n';

import { PORTFOLIO_QUERY_TEMPLATE } from './template';

const variations = [
	{
		name: 'sekeco-filterable-query-portfolio',
		title: __( 'Works filter', 'sekeco' ),
		description: __(
			'A Works Query Loop with category filter buttons.',
			'sekeco'
		),
		icon: 'filter',
		isDefault: true,
		scope: [ 'inserter', 'transform' ],
		attributes: {
			taxonomy: 'category',
			showAll: true,
			allLabel: __( 'All', 'sekeco' ),
			filterLabel: __( 'Filter by category', 'sekeco' ),
		},
		innerBlocks: PORTFOLIO_QUERY_TEMPLATE,
	},
];

export default variations;
