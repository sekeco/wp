/**
 * Motion controls for core/group and core/cover.
 */
import {
	InspectorControls,
	useBlockEditContext,
} from '@wordpress/block-editor';
import { PanelBody, RangeControl, SelectControl } from '@wordpress/components';
import { createHigherOrderComponent } from '@wordpress/compose';
import { addFilter } from '@wordpress/hooks';
import { __ } from '@wordpress/i18n';

import {
	MOTION_ATTRIBUTES,
	MOTION_OPTIONS,
	SUPPORTED_BLOCKS,
	TRIGGER_OPTIONS,
} from './attributes';

addFilter(
	'blocks.registerBlockType',
	'sekeco/motion-attributes',
	( settings, name ) => {
		if ( ! SUPPORTED_BLOCKS.includes( name ) ) {
			return settings;
		}

		return {
			...settings,
			attributes: {
				...settings.attributes,
				...MOTION_ATTRIBUTES,
			},
		};
	}
);

const withMotionPanel = createHigherOrderComponent( ( BlockEdit ) => {
	return ( props ) => {
		const { name } = useBlockEditContext();

		if ( ! SUPPORTED_BLOCKS.includes( name ) ) {
			return <BlockEdit { ...props } />;
		}

		const { attributes, setAttributes } = props;
		const motion = attributes.sekecoMotion || 'none';

		return (
			<>
				<BlockEdit { ...props } />
				<InspectorControls>
					<PanelBody
						title={ __( 'Motion', 'sekeco' ) }
						initialOpen={ false }
					>
						<SelectControl
							label={ __( 'Animation', 'sekeco' ) }
							value={ motion }
							options={ MOTION_OPTIONS }
							onChange={ ( value ) =>
								setAttributes( { sekecoMotion: value } )
							}
						/>
						<SelectControl
							label={ __( 'Trigger', 'sekeco' ) }
							value={ attributes.sekecoTrigger || 'scroll' }
							options={ TRIGGER_OPTIONS }
							onChange={ ( value ) =>
								setAttributes( { sekecoTrigger: value } )
							}
						/>
						<RangeControl
							label={ __( 'Duration (ms)', 'sekeco' ) }
							value={ attributes.sekecoDuration ?? 700 }
							min={ 100 }
							max={ 2000 }
							step={ 50 }
							onChange={ ( value ) =>
								setAttributes( { sekecoDuration: value } )
							}
						/>
						<RangeControl
							label={ __( 'Delay (ms)', 'sekeco' ) }
							value={ attributes.sekecoDelay ?? 0 }
							min={ 0 }
							max={ 1500 }
							step={ 50 }
							onChange={ ( value ) =>
								setAttributes( { sekecoDelay: value } )
							}
						/>
						{ motion !== 'none' && (
							<p>
								{ __(
									'Motion is applied to the whole Group/Cover when it enters the viewport on the frontend.',
									'sekeco'
								) }
							</p>
						) }
					</PanelBody>
				</InspectorControls>
			</>
		);
	};
}, 'withMotionPanel' );

addFilter( 'editor.BlockEdit', 'sekeco/motion-panel', withMotionPanel );
