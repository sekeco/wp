/**
 * Filterable Query editor UI.
 */
import {
	InnerBlocks,
	InspectorControls,
	useBlockProps,
	useInnerBlocksProps,
} from '@wordpress/block-editor';
import {
	PanelBody,
	SelectControl,
	TextControl,
	ToggleControl,
} from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';

import './editor.scss';
import { PORTFOLIO_QUERY_TEMPLATE } from './template';

const FALLBACK_TAXONOMIES = [
	{ label: __( 'Categories', 'sekeco' ), value: 'category' },
	{ label: __( 'Tags', 'sekeco' ), value: 'post_tag' },
];

export default function Edit( { attributes, setAttributes } ) {
	const { taxonomy, showAll, allLabel, filterLabel } = attributes;

	const taxonomies = useSelect(
		( select ) => select( 'core' ).getTaxonomies( { per_page: -1 } ) || [],
		[]
	);

	const terms = useSelect(
		( select ) =>
			select( 'core' ).getEntityRecords( 'taxonomy', taxonomy, {
				per_page: 50,
				orderby: 'name',
				order: 'asc',
			} ) || [],
		[ taxonomy ]
	);

	const taxonomyOptions = taxonomies.length
		? taxonomies.map( ( item ) => ( {
				label: item.name,
				value: item.slug,
			} ) )
		: FALLBACK_TAXONOMIES;

	if ( ! taxonomyOptions.some( ( item ) => item.value === taxonomy ) ) {
		taxonomyOptions.unshift( {
			label: taxonomy,
			value: taxonomy,
		} );
	}

	const blockProps = useBlockProps( {
		className: 'sekeco-filterable-query sekeco-filterable-query--editor',
	} );

	const innerBlocksProps = useInnerBlocksProps(
		{ className: 'sekeco-filterable-query__results' },
		{
			allowedBlocks: [ 'core/query' ],
			template: PORTFOLIO_QUERY_TEMPLATE,
			templateLock: false,
			renderAppender: InnerBlocks.ButtonBlockAppender,
		}
	);

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Filter', 'sekeco' ) } initialOpen>
					<SelectControl
						label={ __( 'Taxonomy', 'sekeco' ) }
						value={ taxonomy }
						options={ taxonomyOptions }
						onChange={ ( value ) =>
							setAttributes( { taxonomy: value } )
						}
					/>
					<ToggleControl
						label={ __( 'Show “All” button', 'sekeco' ) }
						checked={ !! showAll }
						onChange={ ( value ) =>
							setAttributes( { showAll: value } )
						}
					/>
					<TextControl
						label={ __( '“All” label', 'sekeco' ) }
						value={ allLabel || '' }
						onChange={ ( value ) =>
							setAttributes( { allLabel: value } )
						}
					/>
					<TextControl
						label={ __( 'Filter group label', 'sekeco' ) }
						value={ filterLabel || '' }
						onChange={ ( value ) =>
							setAttributes( { filterLabel: value } )
						}
					/>
					<p>
						{ __(
							'The Query Loop inside must include a Post Terms block for the selected taxonomy.',
							'sekeco'
						) }
					</p>
					<p>
						{ __(
							'Filtering happens client-side, so use a high per-page count and no pagination.',
							'sekeco'
						) }
					</p>
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				<div
					className="sekeco-filterable-query__filters sekeco-filterable-query__filters--editor"
					aria-hidden="true"
				>
					{ showAll && (
						<span className="sekeco-filterable-query__filter is-active">
							{ allLabel || __( 'All', 'sekeco' ) }
						</span>
					) }
					{ terms.map( ( term ) => (
						<span
							key={ term.id }
							className="sekeco-filterable-query__filter"
						>
							{ term.name }
						</span>
					) ) }
				</div>

				<div { ...innerBlocksProps } />
			</div>
		</>
	);
}
