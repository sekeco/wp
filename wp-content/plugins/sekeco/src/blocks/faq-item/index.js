/**
 * FAQ item block.
 */
import { registerBlockType } from '@wordpress/blocks';
import {
	RichText,
	useBlockProps,
	useInnerBlocksProps,
} from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

import metadata from './block.json';
import { Icon, blockIcon } from '../../shared/icon';
import { saveInner } from '../../shared/register';

function Edit( { attributes, setAttributes } ) {
	const { question } = attributes;
	const blockProps = useBlockProps( { className: 'sk-faq-item' } );
	const innerProps = useInnerBlocksProps(
		{ className: 'sk-faq-item__answer' },
		{ template: [ [ 'core/paragraph' ] ] }
	);

	return (
		<div { ...blockProps }>
			<div
				className="sk-faq-item__summary"
				style={ {
					display: 'flex',
					justifyContent: 'space-between',
					alignItems: 'center',
					gap: 24,
					paddingBlock: 20,
				} }
			>
				<RichText
					tagName="p"
					className="sk-faq-item__question"
					value={ question }
					allowedFormats={ [] }
					placeholder={ __( 'Question', 'sekeco' ) }
					onChange={ ( value ) =>
						setAttributes( { question: value } )
					}
					style={ {
						margin: 0,
						fontFamily: 'var(--wp--preset--font-family--display)',
						fontSize: '1.5rem',
						fontWeight: 500,
					} }
				/>
				<span className="sk-faq-item__icon">
					<Icon name="alt-arrow-down" size={ 22 } />
				</span>
			</div>
			<div { ...innerProps } />
		</div>
	);
}

registerBlockType( metadata.name, {
	icon: blockIcon( 'chat-round-dots' ),
	edit: Edit,
	save: saveInner,
} );
