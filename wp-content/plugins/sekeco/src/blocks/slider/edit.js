/**
 * Slider editor UI.
 */
import {
	InnerBlocks,
	InspectorControls,
	useBlockProps,
	useInnerBlocksProps,
} from '@wordpress/block-editor';
import {
	PanelBody,
	RangeControl,
	SelectControl,
	TextControl,
	ToggleControl,
} from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import { __ } from '@wordpress/i18n';

import './editor.scss';

const TEMPLATE = [
	[
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
		[
			[
				'core/paragraph',
				{
					placeholder: __( 'Slide one content…', 'sekeco' ),
				},
			],
		],
	],
	[
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
		[
			[
				'core/paragraph',
				{
					placeholder: __( 'Slide two content…', 'sekeco' ),
				},
			],
		],
	],
	[
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
		[
			[
				'core/paragraph',
				{
					placeholder: __( 'Slide three content…', 'sekeco' ),
				},
			],
		],
	],
];

const MODE_OPTIONS = [
	{ label: __( 'Manual slides', 'sekeco' ), value: 'inner' },
	{ label: __( 'Query posts', 'sekeco' ), value: 'query' },
];

const POST_TYPE_OPTIONS = [
	{ label: __( 'Portfolio', 'sekeco' ), value: 'product' },
	{ label: __( 'Works', 'sekeco' ), value: 'portfolio' },
	{ label: __( 'Posts', 'sekeco' ), value: 'post' },
];

export default function Edit( { attributes, setAttributes } ) {
	const { mode, perView, gap, showArrows, postType, perPage, label } =
		attributes;

	const blockProps = useBlockProps( {
		className: 'sekeco-slider-editor',
		style: {
			'--sekeco-slider-per-view': perView,
			'--sekeco-slider-gap': `${ gap }px`,
		},
	} );

	const innerBlocksProps = useInnerBlocksProps(
		{
			className: 'sekeco-slider__track sekeco-slider__track--editor',
		},
		{
			template: TEMPLATE,
			templateLock: false,
			renderAppender: InnerBlocks.ButtonBlockAppender,
		}
	);

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Slider', 'sekeco' ) } initialOpen>
					<SelectControl
						label={ __( 'Source', 'sekeco' ) }
						value={ mode }
						options={ MODE_OPTIONS }
						onChange={ ( value ) =>
							setAttributes( { mode: value } )
						}
					/>
					<RangeControl
						label={ __( 'Slides per view', 'sekeco' ) }
						value={ perView }
						min={ 1 }
						max={ 4 }
						step={ 1 }
						onChange={ ( value ) =>
							setAttributes( { perView: value } )
						}
					/>
					<RangeControl
						label={ __( 'Gap (px)', 'sekeco' ) }
						value={ gap }
						min={ 0 }
						max={ 80 }
						step={ 4 }
						onChange={ ( value ) =>
							setAttributes( { gap: value } )
						}
					/>
					<ToggleControl
						label={ __( 'Show arrow controls', 'sekeco' ) }
						checked={ !! showArrows }
						onChange={ ( value ) =>
							setAttributes( { showArrows: value } )
						}
					/>
					<TextControl
						label={ __( 'Accessible label', 'sekeco' ) }
						value={ label || '' }
						onChange={ ( value ) =>
							setAttributes( { label: value } )
						}
					/>
					{ mode === 'query' && (
						<>
							<SelectControl
								label={ __( 'Post type', 'sekeco' ) }
								value={ postType }
								options={ POST_TYPE_OPTIONS }
								onChange={ ( value ) =>
									setAttributes( { postType: value } )
								}
							/>
							<RangeControl
								label={ __( 'Number of slides', 'sekeco' ) }
								value={ perPage }
								min={ 1 }
								max={ 12 }
								step={ 1 }
								onChange={ ( value ) =>
									setAttributes( { perPage: value } )
								}
								help={ __(
									'Only published posts are included.',
									'sekeco'
								) }
							/>
							<p>
								{ __(
									'Query mode renders server-side. Use Preview to test the frontend slider.',
									'sekeco'
								) }
							</p>
						</>
					) }
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				{ mode === 'inner' ? (
					<div className="sekeco-slider__viewport sekeco-slider__viewport--editor">
						<div { ...innerBlocksProps } />
					</div>
				) : (
					<div className="sekeco-slider__query-preview">
						<ServerSideRender
							block="sekeco/slider"
							attributes={ attributes }
						/>
					</div>
				) }
			</div>
		</>
	);
}
