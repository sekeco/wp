/**
 * Marquee block.
 */
import { registerBlockType } from '@wordpress/blocks';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import {
	PanelBody,
	RangeControl,
	TextareaControl,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import './style.scss';
import metadata from './block.json';
import { blockIcon } from '../../shared/icon';
import { saveNull } from '../../shared/register';

const words = ( items ) =>
	items
		.split( '\n' )
		.map( ( item ) => item.trim() )
		.filter( Boolean );

function Edit( { attributes, setAttributes } ) {
	const { items, duration } = attributes;
	const list = words( items );
	const blockProps = useBlockProps( { className: 'sk-marquee' } );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Marquee', 'sekeco' ) }>
					<TextareaControl
						__nextHasNoMarginBottom
						label={ __( 'Words (one per line)', 'sekeco' ) }
						value={ items }
						rows={ 6 }
						onChange={ ( value ) =>
							setAttributes( { items: value } )
						}
					/>
					<RangeControl
						__next40pxDefaultSize
						__nextHasNoMarginBottom
						label={ __( 'Seconds per loop', 'sekeco' ) }
						min={ 10 }
						max={ 120 }
						value={ duration }
						onChange={ ( value ) =>
							setAttributes( { duration: value || 40 } )
						}
					/>
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				<ul className="sk-marquee__track is-static">
					{ ( list.length
						? list
						: [ __( 'Add words in the sidebar', 'sekeco' ) ]
					).map( ( word, index ) => (
						<li key={ `${ word }-${ index }` }>{ word }</li>
					) ) }
				</ul>
			</div>
		</>
	);
}

registerBlockType( metadata.name, {
	icon: blockIcon( 'repeat' ),
	edit: Edit,
	save: saveNull,
} );
