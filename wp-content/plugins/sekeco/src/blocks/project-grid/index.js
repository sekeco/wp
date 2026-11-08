/**
 * Project grid block.
 */
import { registerBlockType } from '@wordpress/blocks';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import {
	PanelBody,
	RangeControl,
	SelectControl,
	Placeholder,
	TextControl,
	ToggleControl,
} from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';
import ServerSideRender from '@wordpress/server-side-render';
import { __ } from '@wordpress/i18n';

import './style.scss';
import metadata from './block.json';
import { blockIcon } from '../../shared/icon';
import { saveNull } from '../../shared/register';

const TYPES = [
	{ label: __( 'Works', 'sekeco' ), value: 'portfolio' },
	{ label: __( 'Portfolio', 'sekeco' ), value: 'product' },
	{ label: __( 'Team', 'sekeco' ), value: 'team' },
];

function Empty() {
	return (
		<Placeholder
			icon={ blockIcon( 'case-round' ).src }
			label={ __( 'Project grid', 'sekeco' ) }
			instructions={ __(
				'Nothing is published for this selection yet, so the grid is hidden on the site.',
				'sekeco'
			) }
		/>
	);
}

function Edit( { attributes, setAttributes } ) {
	const { postType, layout, columns, perPage, category, filter, crewNote } =
		attributes;
	const blockProps = useBlockProps();
	const categories = useSelect(
		( select ) =>
			select( coreStore ).getEntityRecords( 'taxonomy', 'category', {
				per_page: 100,
				hide_empty: true,
			} ),
		[]
	);

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Content', 'sekeco' ) }>
					<SelectControl
						__next40pxDefaultSize
						__nextHasNoMarginBottom
						label={ __( 'Show', 'sekeco' ) }
						value={ postType }
						options={ TYPES }
						onChange={ ( value ) =>
							setAttributes( { postType: value } )
						}
					/>
					<SelectControl
						__next40pxDefaultSize
						__nextHasNoMarginBottom
						label={ __( 'Category', 'sekeco' ) }
						value={ String( category ) }
						options={ [
							{ label: __( 'All', 'sekeco' ), value: '0' },
							...( categories || [] ).map( ( term ) => ( {
								label: term.name,
								value: String( term.id ),
							} ) ),
						] }
						onChange={ ( value ) =>
							setAttributes( { category: Number( value ) } )
						}
					/>
					<RangeControl
						__next40pxDefaultSize
						__nextHasNoMarginBottom
						label={ __( 'Number of entries', 'sekeco' ) }
						min={ 1 }
						max={ 24 }
						value={ perPage }
						onChange={ ( value ) =>
							setAttributes( { perPage: value || 6 } )
						}
					/>
				</PanelBody>
				<PanelBody title={ __( 'Layout', 'sekeco' ) }>
					<SelectControl
						__next40pxDefaultSize
						__nextHasNoMarginBottom
						label={ __( 'Layout', 'sekeco' ) }
						value={ layout }
						options={ [
							{ label: __( 'Grid', 'sekeco' ), value: 'grid' },
							{
								label: __(
									'Mosaic (wide + narrow rows)',
									'sekeco'
								),
								value: 'mosaic',
							},
							{
								label: __( 'Reel (horizontal)', 'sekeco' ),
								value: 'reel',
							},
							{
								label: __( 'Index list', 'sekeco' ),
								value: 'list',
							},
							...( 'team' === postType
								? [
										{
											label: __(
												'Lead and crew (Team)',
												'sekeco'
											),
											value: 'crew',
										},
								  ]
								: [] ),
						] }
						onChange={ ( value ) =>
							setAttributes( { layout: value } )
						}
					/>
					{ 'crew' === layout && (
						<TextControl
							__next40pxDefaultSize
							__nextHasNoMarginBottom
							label={ __( 'Closing note', 'sekeco' ) }
							help={ __(
								'Shown as the last tile beside the lead card. Leave empty to hide it.',
								'sekeco'
							) }
							value={ crewNote }
							onChange={ ( value ) =>
								setAttributes( { crewNote: value } )
							}
						/>
					) }
					{ ! [ 'list', 'crew' ].includes( layout ) && (
						<ToggleControl
							__nextHasNoMarginBottom
							label={ __( 'Category filter', 'sekeco' ) }
							help={ __(
								'Adds filter buttons above the entries.',
								'sekeco'
							) }
							checked={ filter }
							onChange={ ( value ) =>
								setAttributes( { filter: value } )
							}
						/>
					) }
					{ 'grid' === layout && (
						<RangeControl
							__next40pxDefaultSize
							__nextHasNoMarginBottom
							label={ __( 'Columns', 'sekeco' ) }
							min={ 2 }
							max={ 4 }
							value={ columns }
							onChange={ ( value ) =>
								setAttributes( { columns: value || 3 } )
							}
						/>
					) }
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				<ServerSideRender
					block={ metadata.name }
					attributes={ attributes }
					EmptyResponsePlaceholder={ Empty }
				/>
			</div>
		</>
	);
}

registerBlockType( metadata.name, {
	icon: blockIcon( 'case-round' ),
	edit: Edit,
	save: saveNull,
} );
