/**
 * Section block.
 */
import { registerBlockType } from '@wordpress/blocks';
import {
	InspectorControls,
	useBlockProps,
	useInnerBlocksProps,
} from '@wordpress/block-editor';
import { PanelBody, SelectControl, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import './style.scss';
import metadata from './block.json';
import { blockIcon } from '../../shared/icon';
import { saveInner } from '../../shared/register';

const TONES = [
	{ label: __( 'Follow site (light or dark)', 'sekeco' ), value: 'auto' },
	{ label: __( 'Ink (always dark)', 'sekeco' ), value: 'ink' },
	{ label: __( 'Paper (always light)', 'sekeco' ), value: 'paper' },
	{ label: __( 'Surface (raised)', 'sekeco' ), value: 'surface' },
	{ label: __( 'Accent field', 'sekeco' ), value: 'accent' },
];

function Edit( { attributes, setAttributes } ) {
	const { tone, spacing, width, stacked, requires } = attributes;
	const blockProps = useBlockProps( {
		className: `sk-section sk-section--space-${ spacing }${
			'auto' === tone ? '' : ` sk-tone--${ tone }`
		}${ stacked ? ' sk-section--stacked' : '' }`,
	} );
	const innerProps = useInnerBlocksProps(
		{ className: `sk-section__inner sk-section__inner--${ width }` },
		{ templateLock: false }
	);

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Section', 'sekeco' ) }>
					<SelectControl
						__next40pxDefaultSize
						__nextHasNoMarginBottom
						label={ __( 'Tone', 'sekeco' ) }
						value={ tone }
						options={ TONES }
						onChange={ ( value ) =>
							setAttributes( { tone: value } )
						}
					/>
					<SelectControl
						__next40pxDefaultSize
						__nextHasNoMarginBottom
						label={ __( 'Vertical spacing', 'sekeco' ) }
						value={ spacing }
						options={ [
							{ label: __( 'None', 'sekeco' ), value: 'none' },
							{ label: __( 'Small', 'sekeco' ), value: 'sm' },
							{ label: __( 'Medium', 'sekeco' ), value: 'md' },
							{ label: __( 'Large', 'sekeco' ), value: 'lg' },
						] }
						onChange={ ( value ) =>
							setAttributes( { spacing: value } )
						}
					/>
					<SelectControl
						__next40pxDefaultSize
						__nextHasNoMarginBottom
						label={ __( 'Content width', 'sekeco' ) }
						value={ width }
						options={ [
							{
								label: __( 'Narrow', 'sekeco' ),
								value: 'narrow',
							},
							{
								label: __( 'Default', 'sekeco' ),
								value: 'default',
							},
							{ label: __( 'Full', 'sekeco' ), value: 'full' },
						] }
						onChange={ ( value ) =>
							setAttributes( { width: value } )
						}
					/>
					<SelectControl
						__next40pxDefaultSize
						__nextHasNoMarginBottom
						label={ __( 'Show only when published', 'sekeco' ) }
						help={ __(
							'Hides the whole section on the site until this content type has at least one published entry.',
							'sekeco'
						) }
						value={ requires }
						options={ [
							{ label: __( 'Always show', 'sekeco' ), value: '' },
							{
								label: __( 'Works exist', 'sekeco' ),
								value: 'portfolio',
							},
							{
								label: __( 'Portfolio exists', 'sekeco' ),
								value: 'product',
							},
							{
								label: __( 'Team exists', 'sekeco' ),
								value: 'team',
							},
							{
								label: __( 'Journal posts exist', 'sekeco' ),
								value: 'post',
							},
						] }
						onChange={ ( value ) =>
							setAttributes( { requires: value } )
						}
					/>
					<ToggleControl
						__nextHasNoMarginBottom
						label={ __( 'Rounded stacked edge', 'sekeco' ) }
						help={ __(
							'Overlaps the section above with rounded top corners.',
							'sekeco'
						) }
						checked={ stacked }
						onChange={ ( value ) =>
							setAttributes( { stacked: value } )
						}
					/>
				</PanelBody>
			</InspectorControls>
			<section { ...blockProps }>
				<div { ...innerProps } />
			</section>
		</>
	);
}

registerBlockType( metadata.name, {
	icon: blockIcon( 'layers' ),
	edit: Edit,
	save: saveInner,
} );
