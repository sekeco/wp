/**
 * Section header block.
 */
import { registerBlockType } from '@wordpress/blocks';
import {
	InspectorControls,
	RichText,
	useBlockProps,
} from '@wordpress/block-editor';
import { PanelBody, SelectControl, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import './style.scss';
import metadata from './block.json';
import { Icon, blockIcon } from '../../shared/icon';
import { saveNull, DISPLAY_FORMATS } from '../../shared/register';

function Edit( { attributes, setAttributes } ) {
	const { kicker, title, lead, linkLabel, linkUrl, level } = attributes;
	const blockProps = useBlockProps( { className: 'sk-section-header' } );
	const Tag = `h${ level }`;

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Header', 'sekeco' ) }>
					<SelectControl
						__next40pxDefaultSize
						__nextHasNoMarginBottom
						label={ __( 'Heading level', 'sekeco' ) }
						value={ String( level ) }
						options={ [ 1, 2, 3 ].map( ( value ) => ( {
							label: `H${ value }`,
							value: String( value ),
						} ) ) }
						onChange={ ( value ) =>
							setAttributes( { level: Number( value ) } )
						}
					/>
					<TextControl
						__next40pxDefaultSize
						__nextHasNoMarginBottom
						label={ __( 'Link label', 'sekeco' ) }
						value={ linkLabel }
						onChange={ ( value ) =>
							setAttributes( { linkLabel: value } )
						}
					/>
					<TextControl
						__next40pxDefaultSize
						__nextHasNoMarginBottom
						label={ __( 'Link URL', 'sekeco' ) }
						value={ linkUrl }
						onChange={ ( value ) =>
							setAttributes( { linkUrl: value } )
						}
					/>
				</PanelBody>
			</InspectorControls>
			<header { ...blockProps }>
				<RichText
					tagName="p"
					className="sk-kicker sk-section-header__kicker"
					value={ kicker }
					allowedFormats={ [] }
					placeholder={ __( 'SMALL LABEL (optional)', 'sekeco' ) }
					onChange={ ( value ) => setAttributes( { kicker: value } ) }
				/>
				<RichText
					tagName={ Tag }
					className="sk-section-header__title"
					value={ title }
					allowedFormats={ DISPLAY_FORMATS }
					placeholder={ __( 'Section title', 'sekeco' ) }
					onChange={ ( value ) => setAttributes( { title: value } ) }
				/>
				<div className="sk-section-header__aside">
					<RichText
						tagName="p"
						className="sk-section-header__lead"
						value={ lead }
						allowedFormats={ [] }
						placeholder={ __( 'Short lead', 'sekeco' ) }
						onChange={ ( value ) =>
							setAttributes( { lead: value } )
						}
					/>
					{ linkLabel && (
						<span className="sk-link">
							{ linkLabel }
							<Icon name="arrow-right" size={ 20 } />
						</span>
					) }
				</div>
			</header>
		</>
	);
}

registerBlockType( metadata.name, {
	icon: blockIcon( 'align-left' ),
	edit: Edit,
	save: saveNull,
} );
