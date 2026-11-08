/**
 * Process step block.
 */
import { registerBlockType } from '@wordpress/blocks';
import { RichText, useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

import metadata from './block.json';
import { blockIcon } from '../../shared/icon';
import { saveNull } from '../../shared/register';

function Edit( { attributes, setAttributes } ) {
	const { title, description } = attributes;
	const blockProps = useBlockProps( { className: 'sk-process-step' } );

	return (
		<li { ...blockProps }>
			<RichText
				tagName="h3"
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
