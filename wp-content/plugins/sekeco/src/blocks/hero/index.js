/**
 * Hero block.
 */
import { registerBlockType } from '@wordpress/blocks';
import {
	InspectorControls,
	RichText,
	useBlockProps,
	useInnerBlocksProps,
} from '@wordpress/block-editor';
import { PanelBody, RangeControl, SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import './style.scss';
import metadata from './block.json';
import { blockIcon } from '../../shared/icon';
import { saveInner, DISPLAY_FORMATS } from '../../shared/register';

const TEMPLATE = [
	[
		'core/buttons',
		{},
		[
			[ 'core/button', { text: 'Start a project', url: '/contact/' } ],
			[
				'core/button',
				{
					text: 'See our work',
					url: '/works/',
					className: 'is-style-outline',
				},
			],
		],
	],
];

function Edit( { attributes, setAttributes } ) {
	const { kicker, heading, lead, tone, reel, reelCount } = attributes;
	const blockProps = useBlockProps( {
		className: `sk-hero${ 'auto' === tone ? '' : ` sk-tone--${ tone }` }`,
	} );
	const innerProps = useInnerBlocksProps(
		{ className: 'sk-hero__actions' },
		{
			template: TEMPLATE,
			allowedBlocks: [ 'core/buttons' ],
			templateLock: false,
		}
	);

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Hero', 'sekeco' ) }>
					<SelectControl
						__next40pxDefaultSize
						__nextHasNoMarginBottom
						label={ __( 'Tone', 'sekeco' ) }
						value={ tone }
						options={ [
							{
								label: __( 'Ink (always dark)', 'sekeco' ),
								value: 'ink',
							},
							{
								label: __( 'Follow site', 'sekeco' ),
								value: 'auto',
							},
							{
								label: __( 'Paper (always light)', 'sekeco' ),
								value: 'paper',
							},
							{
								label: __( 'Accent field', 'sekeco' ),
								value: 'accent',
							},
						] }
						onChange={ ( value ) =>
							setAttributes( { tone: value } )
						}
					/>
					<SelectControl
						__next40pxDefaultSize
						__nextHasNoMarginBottom
						label={ __(
							'Project mosaic under the headline',
							'sekeco'
						) }
						value={ reel }
						options={ [
							{
								label: __( 'Works', 'sekeco' ),
								value: 'portfolio',
							},
							{
								label: __( 'Portfolio', 'sekeco' ),
								value: 'product',
							},
							{ label: __( 'None', 'sekeco' ), value: 'none' },
						] }
						onChange={ ( value ) =>
							setAttributes( { reel: value } )
						}
					/>
					{ 'none' !== reel && (
						<RangeControl
							__next40pxDefaultSize
							__nextHasNoMarginBottom
							label={ __( 'Mosaic entries', 'sekeco' ) }
							min={ 3 }
							max={ 12 }
							value={ reelCount }
							onChange={ ( value ) =>
								setAttributes( { reelCount: value || 5 } )
							}
						/>
					) }
				</PanelBody>
			</InspectorControls>
			<section { ...blockProps }>
				<div className="sk-hero__stage">
					<RichText
						tagName="p"
						className="sk-kicker sk-hero__kicker"
						value={ kicker }
						allowedFormats={ [] }
						placeholder={ __( 'SMALL LABEL', 'sekeco' ) }
						onChange={ ( value ) =>
							setAttributes( { kicker: value } )
						}
					/>
					<RichText
						tagName="h1"
						className="sk-hero__title"
						value={ heading }
						allowedFormats={ DISPLAY_FORMATS }
						placeholder={ __(
							'Headline. Italic text is dimmed.',
							'sekeco'
						) }
						onChange={ ( value ) =>
							setAttributes( { heading: value } )
						}
					/>
					<div className="sk-hero__foot">
						<RichText
							tagName="p"
							className="sk-hero__lead"
							value={ lead }
							allowedFormats={ [] }
							placeholder={ __( 'Short lead', 'sekeco' ) }
							onChange={ ( value ) =>
								setAttributes( { lead: value } )
							}
						/>
						<div { ...innerProps } />
					</div>
				</div>
				{ 'none' !== reel && (
					<p className="sk-hero__reel-note">
						{ __(
							'The mosaic of latest entries appears here on the site.',
							'sekeco'
						) }
					</p>
				) }
			</section>
		</>
	);
}

registerBlockType( metadata.name, {
	icon: blockIcon( 'rocket' ),
	edit: Edit,
	save: saveInner,
} );
