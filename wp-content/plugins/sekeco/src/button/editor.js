/**
 * Button size control.
 *
 * Adds a Size panel (Small, Medium, Large) to core/button. The choice is a
 * class on the button (is-size-sm, is-size-lg; medium has none), so the saved
 * content stays native and the theme's buttons.css does the rest. Button
 * styles (Fill, Outline and the gradient looks) use the core Styles panel.
 */
import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import { InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	ToggleGroupControl,
	ToggleGroupControlOption,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';

const SIZE_CLASS = /(?:^|\s)is-size-(sm|lg)(?=\s|$)/g;

const getSize = ( className = '' ) => {
	const match = /(?:^|\s)is-size-(sm|lg)(?=\s|$)/.exec( className );

	return match ? match[ 1 ] : 'md';
};

const withButtonSize = createHigherOrderComponent(
	( BlockEdit ) => ( props ) => {
		if ( 'core/button' !== props.name ) {
			return <BlockEdit { ...props } />;
		}

		const { attributes, setAttributes } = props;
		const className = attributes.className || '';
		const size = getSize( className );

		const onChange = ( value ) => {
			const base = className.replace( SIZE_CLASS, '' ).trim();
			const next = [ base, 'md' === value ? '' : `is-size-${ value }` ]
				.filter( Boolean )
				.join( ' ' );

			setAttributes( { className: next || undefined } );
		};

		return (
			<>
				<BlockEdit { ...props } />
				<InspectorControls>
					<PanelBody title={ __( 'Size', 'sekeco' ) } initialOpen>
						<ToggleGroupControl
							__next40pxDefaultSize
							__nextHasNoMarginBottom
							isBlock
							label={ __( 'Button size', 'sekeco' ) }
							hideLabelFromVision
							value={ size }
							onChange={ onChange }
						>
							<ToggleGroupControlOption
								value="sm"
								label={ __( 'Small', 'sekeco' ) }
							/>
							<ToggleGroupControlOption
								value="md"
								label={ __( 'Medium', 'sekeco' ) }
							/>
							<ToggleGroupControlOption
								value="lg"
								label={ __( 'Large', 'sekeco' ) }
							/>
						</ToggleGroupControl>
					</PanelBody>
				</InspectorControls>
			</>
		);
	},
	'withButtonSize'
);

addFilter( 'editor.BlockEdit', 'sekeco/button-size', withButtonSize );
