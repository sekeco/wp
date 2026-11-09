/**
 * Testimonials block (container).
 */
import { registerBlockType } from '@wordpress/blocks';
import {
	InspectorControls,
	useBlockProps,
	useInnerBlocksProps,
} from '@wordpress/block-editor';
import { PanelBody, RangeControl, SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import './style.scss';
import metadata from './block.json';
import { blockIcon } from '../../shared/icon';
import { saveInner } from '../../shared/register';

const LAYOUTS = [
	{ label: __( 'Slider (one large quote)', 'sekeco' ), value: 'slider' },
	{ label: __( 'Cards (grid)', 'sekeco' ), value: 'cards' },
	{ label: __( 'Marquee (endless row)', 'sekeco' ), value: 'marquee' },
];

const THREE = [
	[ 'sekeco/testimonial' ],
	[ 'sekeco/testimonial' ],
	[ 'sekeco/testimonial' ],
];

function Edit( { attributes, setAttributes } ) {
	const { layout, duration } = attributes;
	// The editor lays every layout out as a grid so each quote can be edited in place.
	const blockProps = useBlockProps( {
		className: `sk-testimonials sk-testimonials--${ layout } is-editor`,
	} );
	const innerProps = useInnerBlocksProps( blockProps, {
		allowedBlocks: [ 'sekeco/testimonial' ],
		template: THREE,
		orientation: 'horizontal',
	} );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Testimonials', 'sekeco' ) }>
					<SelectControl
						__next40pxDefaultSize
						__nextHasNoMarginBottom
						label={ __( 'Layout', 'sekeco' ) }
						value={ layout }
						options={ LAYOUTS }
						onChange={ ( value ) =>
							setAttributes( { layout: value } )
						}
					/>
					{ 'marquee' === layout && (
						<RangeControl
							__next40pxDefaultSize
							__nextHasNoMarginBottom
							label={ __( 'Seconds per loop', 'sekeco' ) }
							help={ __(
								'Higher is slower. Still for visitors who prefer reduced motion.',
								'sekeco'
							) }
							min={ 20 }
							max={ 180 }
							value={ duration }
							onChange={ ( value ) =>
								setAttributes( { duration: value || 60 } )
							}
						/>
					) }
				</PanelBody>
			</InspectorControls>
			<div { ...innerProps } />
		</>
	);
}

registerBlockType( metadata.name, {
	icon: blockIcon( 'hand-heart' ),
	edit: Edit,
	save: saveInner,
	variations: [
		{
			name: 'slider',
			title: __( 'Testimonials: Slider', 'sekeco' ),
			description: __(
				'One large quote at a time, with arrows and dots.',
				'sekeco'
			),
			icon: blockIcon( 'hand-heart' ),
			attributes: { layout: 'slider' },
			innerBlocks: THREE,
			isDefault: true,
			isActive: ( attributes ) => 'slider' === attributes.layout,
		},
		{
			name: 'cards',
			title: __( 'Testimonials: Cards', 'sekeco' ),
			description: __( 'A grid of quote cards.', 'sekeco' ),
			icon: blockIcon( 'hand-heart' ),
			attributes: { layout: 'cards' },
			innerBlocks: THREE,
			isActive: ( attributes ) => 'cards' === attributes.layout,
		},
		{
			name: 'marquee',
			title: __( 'Testimonials: Marquee', 'sekeco' ),
			description: __(
				'An endless, slow row of quote cards. Pauses on hover.',
				'sekeco'
			),
			icon: blockIcon( 'hand-heart' ),
			attributes: { layout: 'marquee' },
			innerBlocks: THREE,
			isActive: ( attributes ) => 'marquee' === attributes.layout,
		},
	],
} );
