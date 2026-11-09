/**
 * CTA block.
 */
import { registerBlockType } from '@wordpress/blocks';
import {
	InspectorControls,
	RichText,
	useBlockProps,
	useInnerBlocksProps,
} from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import './style.scss';
import metadata from './block.json';
import { blockIcon } from '../../shared/icon';
import { saveInner, DISPLAY_FORMATS } from '../../shared/register';
import { WidthControl, widthClass } from '../../shared/width';
import { HeadingLevelControl } from '../../shared/heading';
import {
	BackgroundCanvas,
	BackgroundControl,
	backgroundClass,
} from '../../shared/backgrounds/control';

const TEMPLATE = [
	[
		'core/buttons',
		{},
		[ [ 'core/button', { text: "Let's Collaborate", url: '/contact/' } ] ],
	],
];

function Edit( { attributes, setAttributes } ) {
	const { kicker, heading, lead, tone, width, bg, level } = attributes;
	const blockProps = useBlockProps( {
		className: `sk-cta${
			'auto' === tone ? '' : ` sk-tone--${ tone }`
		}${ widthClass( width ) }${ backgroundClass( bg ) }`,
	} );
	const innerProps = useInnerBlocksProps(
		{ className: 'sk-cta__actions' },
		{ template: TEMPLATE, allowedBlocks: [ 'core/buttons' ] }
	);

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Call to action', 'sekeco' ) }>
					<SelectControl
						__next40pxDefaultSize
						__nextHasNoMarginBottom
						label={ __( 'Tone', 'sekeco' ) }
						value={ tone }
						options={ [
							{
								label: __( 'Accent field', 'sekeco' ),
								value: 'accent',
							},
							{
								label: __( 'Ink (always dark)', 'sekeco' ),
								value: 'ink',
							},
							{
								label: __( 'Paper (always light)', 'sekeco' ),
								value: 'paper',
							},
							{
								label: __( 'Follow site', 'sekeco' ),
								value: 'auto',
							},
						] }
						onChange={ ( value ) =>
							setAttributes( { tone: value } )
						}
					/>
					<WidthControl
						value={ width }
						onChange={ ( value ) =>
							setAttributes( { width: value } )
						}
					/>
					<HeadingLevelControl
						value={ level }
						min={ 1 }
						max={ 4 }
						onChange={ ( value ) =>
							setAttributes( { level: value } )
						}
					/>
					<BackgroundControl
						value={ bg }
						onChange={ ( value ) => setAttributes( { bg: value } ) }
					/>
				</PanelBody>
			</InspectorControls>
			<section { ...blockProps }>
				<BackgroundCanvas type={ bg } />
				<div className="sk-cta__inner">
					<RichText
						tagName="p"
						className="sk-kicker sk-cta__kicker"
						value={ kicker }
						allowedFormats={ [] }
						placeholder={ __( 'SMALL LABEL (optional)', 'sekeco' ) }
						onChange={ ( value ) =>
							setAttributes( { kicker: value } )
						}
					/>
					<RichText
						tagName={ `h${ level }` }
						className="sk-cta__title"
						value={ heading }
						allowedFormats={ DISPLAY_FORMATS }
						placeholder={ __( 'Closing headline', 'sekeco' ) }
						onChange={ ( value ) =>
							setAttributes( { heading: value } )
						}
					/>
					<RichText
						tagName="p"
						className="sk-cta__lead"
						value={ lead }
						allowedFormats={ [] }
						placeholder={ __(
							'One sentence that makes the next step easy',
							'sekeco'
						) }
						onChange={ ( value ) =>
							setAttributes( { lead: value } )
						}
					/>
					<div { ...innerProps } />
				</div>
			</section>
		</>
	);
}

registerBlockType( metadata.name, {
	icon: blockIcon( 'plain' ),
	edit: Edit,
	save: saveInner,
} );
