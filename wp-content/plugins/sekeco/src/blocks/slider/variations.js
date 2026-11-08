/**
 * Slider variations.
 */
import { __ } from '@wordpress/i18n';

const manualSlide = ( placeholder ) => [
	'core/group',
	{
		className: 'is-style-sekeco-card',
		style: {
			spacing: {
				padding: {
					top: 'var:preset|spacing|40',
					bottom: 'var:preset|spacing|40',
					left: 'var:preset|spacing|40',
					right: 'var:preset|spacing|40',
				},
			},
		},
		layout: { type: 'constrained' },
	},
	[ [ 'core/paragraph', { placeholder } ] ],
];

const variations = [
	{
		name: 'sekeco-slider-manual',
		title: __( 'Manual slides', 'sekeco' ),
		description: __( 'Add any block as a slide.', 'sekeco' ),
		icon: 'images-alt2',
		isDefault: true,
		scope: [ 'inserter', 'transform' ],
		attributes: {
			mode: 'inner',
			perView: 3,
			gap: 24,
			showArrows: true,
			label: __( 'Slider', 'sekeco' ),
		},
		innerBlocks: [
			manualSlide( __( 'Slide one content…', 'sekeco' ) ),
			manualSlide( __( 'Slide two content…', 'sekeco' ) ),
			manualSlide( __( 'Slide three content…', 'sekeco' ) ),
		],
	},
	{
		name: 'sekeco-slider-products',
		title: __( 'Portfolio slider', 'sekeco' ),
		description: __(
			'Automatically query published portfolio items.',
			'sekeco'
		),
		icon: 'screenoptions',
		scope: [ 'inserter', 'transform' ],
		attributes: {
			mode: 'query',
			postType: 'product',
			perPage: 6,
			perView: 3,
			gap: 24,
			showArrows: true,
			label: __( 'Portfolio', 'sekeco' ),
		},
	},
	{
		name: 'sekeco-slider-portfolio',
		title: __( 'Works slider', 'sekeco' ),
		description: __( 'Automatically query published works.', 'sekeco' ),
		icon: 'portfolio',
		scope: [ 'inserter', 'transform' ],
		attributes: {
			mode: 'query',
			postType: 'portfolio',
			perPage: 6,
			perView: 3,
			gap: 24,
			showArrows: true,
			label: __( 'Portfolio', 'sekeco' ),
		},
	},
];

export default variations;
