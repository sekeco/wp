/**
 * Capability block.
 */
import { registerBlockType } from '@wordpress/blocks';
import {
	InspectorControls,
	RichText,
	useBlockProps,
} from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import metadata from './block.json';
import { Icon, IconSelect, blockIcon } from '../../shared/icon';
import { saveNull } from '../../shared/register';
import { HeadingLevelControl } from '../../shared/heading';

function Edit( { attributes, setAttributes } ) {
	const { icon, title, description, tags, linkUrl, level } = attributes;
	const blockProps = useBlockProps( { className: 'sk-capability sk-glow' } );
	const tagList = tags
		.split( ',' )
		.map( ( tag ) => tag.trim() )
		.filter( Boolean );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Capability', 'sekeco' ) }>
					<HeadingLevelControl
						value={ level }
						min={ 2 }
						max={ 4 }
						onChange={ ( value ) =>
							setAttributes( { level: value } )
						}
					/>
					<IconSelect
						value={ icon }
						onChange={ ( value ) =>
							setAttributes( { icon: value } )
						}
					/>
					<TextControl
						__next40pxDefaultSize
						__nextHasNoMarginBottom
						label={ __( 'Tags (comma separated)', 'sekeco' ) }
						value={ tags }
						onChange={ ( value ) =>
							setAttributes( { tags: value } )
						}
					/>
					<TextControl
						__next40pxDefaultSize
						__nextHasNoMarginBottom
						label={ __( 'Link URL (optional)', 'sekeco' ) }
						value={ linkUrl }
						onChange={ ( value ) =>
							setAttributes( { linkUrl: value } )
						}
					/>
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				<span className="sk-capability__icon">
					<Icon name={ icon } size={ 24 } />
				</span>
				<RichText
					tagName={ `h${ level }` }
					className="sk-capability__title"
					value={ title }
					allowedFormats={ [] }
					placeholder={ __( 'Service name', 'sekeco' ) }
					onChange={ ( value ) => setAttributes( { title: value } ) }
				/>
				<div className="sk-capability__text">
					<RichText
						tagName="p"
						className="sk-capability__desc"
						value={ description }
						allowedFormats={ [ 'core/bold', 'core/italic' ] }
						placeholder={ __(
							'What it covers, in one or two sentences',
							'sekeco'
						) }
						onChange={ ( value ) =>
							setAttributes( { description: value } )
						}
					/>
					{ tagList.length > 0 && (
						<ul className="sk-capability__tags">
							{ tagList.map( ( tag ) => (
								<li key={ tag }>{ tag }</li>
							) ) }
						</ul>
					) }
				</div>
			</div>
		</>
	);
}

registerBlockType( metadata.name, {
	icon: blockIcon( 'code-square' ),
	edit: Edit,
	save: saveNull,
} );
