/**
 * Testimonial block (one quote).
 */
import { registerBlockType } from '@wordpress/blocks';
import {
	MediaUpload,
	MediaUploadCheck,
	RichText,
	useBlockProps,
} from '@wordpress/block-editor';
import { Button } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';
import { __ } from '@wordpress/i18n';

import metadata from './block.json';
import { blockIcon } from '../../shared/icon';
import { saveNull } from '../../shared/register';

const initials = ( name ) =>
	( name || '' )
		.trim()
		.split( /\s+/ )
		.slice( 0, 2 )
		.map( ( word ) => word.charAt( 0 ) )
		.join( '' )
		.toUpperCase();

function Edit( { attributes, setAttributes } ) {
	const { quote, name, role, source, topic, imageId } = attributes;
	const blockProps = useBlockProps( { className: 'sk-testimonial' } );
	const media = useSelect(
		( select ) =>
			imageId
				? select( coreStore ).getMedia( imageId, { context: 'view' } )
				: null,
		[ imageId ]
	);
	const imageUrl =
		media?.media_details?.sizes?.thumbnail?.source_url ||
		media?.source_url ||
		'';

	return (
		<figure { ...blockProps }>
			<p className="sk-testimonial__meta">
				<RichText
					tagName="span"
					className="sk-testimonial__source"
					value={ source }
					allowedFormats={ [] }
					placeholder={ __( 'Source, e.g. a review site', 'sekeco' ) }
					onChange={ ( value ) => setAttributes( { source: value } ) }
				/>
				<RichText
					tagName="span"
					className="sk-testimonial__topic"
					value={ topic }
					allowedFormats={ [] }
					placeholder={ __( 'Topic (optional)', 'sekeco' ) }
					onChange={ ( value ) => setAttributes( { topic: value } ) }
				/>
			</p>
			<blockquote className="sk-testimonial__quote">
				<RichText
					tagName="p"
					value={ quote }
					allowedFormats={ [ 'core/bold', 'core/italic' ] }
					placeholder={ __(
						'The client’s own words. The block stays hidden on the site until this has text.',
						'sekeco'
					) }
					onChange={ ( value ) => setAttributes( { quote: value } ) }
				/>
			</blockquote>
			<figcaption className="sk-testimonial__who">
				<MediaUploadCheck>
					<MediaUpload
						allowedTypes={ [ 'image' ] }
						value={ imageId }
						onSelect={ ( item ) =>
							setAttributes( { imageId: item.id } )
						}
						render={ ( { open } ) => (
							<Button
								className="sk-testimonial__photo"
								onClick={ open }
								label={
									imageUrl
										? __( 'Replace photo', 'sekeco' )
										: __( 'Add photo', 'sekeco' )
								}
								showTooltip
							>
								{ imageUrl ? (
									<img
										className="sk-testimonial__avatar"
										src={ imageUrl }
										alt=""
									/>
								) : (
									<span className="sk-testimonial__avatar sk-testimonial__avatar--mark">
										{ initials( name ) || '+' }
									</span>
								) }
							</Button>
						) }
					/>
				</MediaUploadCheck>
				<span className="sk-testimonial__id">
					<RichText
						tagName="span"
						className="sk-testimonial__name"
						value={ name }
						allowedFormats={ [] }
						placeholder={ __( 'Name', 'sekeco' ) }
						onChange={ ( value ) =>
							setAttributes( { name: value } )
						}
					/>
					<RichText
						tagName="span"
						className="sk-testimonial__role"
						value={ role }
						allowedFormats={ [] }
						placeholder={ __( 'Role, company', 'sekeco' ) }
						onChange={ ( value ) =>
							setAttributes( { role: value } )
						}
					/>
				</span>
				{ imageId ? (
					<Button
						className="sk-testimonial__remove"
						variant="tertiary"
						isDestructive
						size="small"
						onClick={ () => setAttributes( { imageId: 0 } ) }
					>
						{ __( 'Remove photo', 'sekeco' ) }
					</Button>
				) : null }
			</figcaption>
		</figure>
	);
}

registerBlockType( metadata.name, {
	icon: blockIcon( 'hand-heart' ),
	edit: Edit,
	save: saveNull,
	example: {
		attributes: {
			quote: 'Your client’s words go here.',
			name: 'Client name',
			role: 'Role, company',
		},
	},
} );
