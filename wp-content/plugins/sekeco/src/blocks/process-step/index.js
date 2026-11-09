/**
 * Process step block.
 */
import { registerBlockType } from '@wordpress/blocks';
import {
	InspectorControls,
	RichText,
	useBlockProps,
} from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import metadata from './block.json';
import { blockIcon } from '../../shared/icon';
import { saveNull } from '../../shared/register';
import { HeadingLevelControl } from '../../shared/heading';

function Edit( { attributes, setAttributes } ) {
	const { title, description, level } = attributes;
	const blockProps = useBlockProps( {
		className: 'sk-process-step sk-glow',
	} );

	return (
		<li { ...blockProps }>
			<InspectorControls>
				<PanelBody title={ __( 'Step', 'sekeco' ) }>
					<HeadingLevelControl
						value={ level }
						min={ 2 }
						max={ 4 }
						onChange={ ( value ) =>
							setAttributes( { level: value } )
						}
					/>
				</PanelBody>
			</InspectorControls>
			<RichText
				tagName={ `h${ level }` }
				className="sk-process-step__title"
				value={ title }
				allowedFormats={ [] }
				placeholder={ __( 'Step name', 'sekeco' ) }
				onChange={ ( value ) => setAttributes( { title: value } ) }
			/>
			<RichText
				tagName="p"
				className="sk-process-step__desc"
				value={ description }
				allowedFormats={ [ 'core/bold', 'core/italic' ] }
				placeholder={ __( 'What happens in this step', 'sekeco' ) }
				onChange={ ( value ) =>
					setAttributes( { description: value } )
				}
			/>
		</li>
	);
}

registerBlockType( metadata.name, {
	icon: blockIcon( 'clipboard-list' ),
	edit: Edit,
	save: saveNull,
} );
