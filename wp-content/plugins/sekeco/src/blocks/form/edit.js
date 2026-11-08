/**
 * Form block editor UI.
 */
import { useBlockProps } from '@wordpress/block-editor';
import { Placeholder, SelectControl } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';

export default function Edit( { attributes, setAttributes } ) {
	const { formId } = attributes;

	const forms = useSelect(
		( select ) =>
			select( 'core' ).getEntityRecords( 'postType', 'sekeco_form', {
				per_page: 100,
				status: 'publish',
			} ) || [],
		[]
	);

	const options = [
		{ label: __( 'Select a form…', 'sekeco' ), value: 0 },
		...forms.map( ( form ) => ( {
			label: form.title.rendered,
			value: form.id,
		} ) ),
	];

	const blockProps = useBlockProps();

	const select = (
		<SelectControl
			label={ __( 'Form', 'sekeco' ) }
			value={ formId }
			options={ options }
			onChange={ ( value ) =>
				setAttributes( { formId: Number( value ) } )
			}
		/>
	);

	if ( ! formId ) {
		return (
			<div { ...blockProps }>
				<Placeholder
					label={ __( 'Sekeco Form', 'sekeco' ) }
					instructions={ __(
						'Choose a form or survey to embed.',
						'sekeco'
					) }
				>
					{ select }
				</Placeholder>
			</div>
		);
	}

	return (
		<div { ...blockProps }>
			{ select }
			<ServerSideRender block="sekeco/form" attributes={ attributes } />
		</div>
	);
}
