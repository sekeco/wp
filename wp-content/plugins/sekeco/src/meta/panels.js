/**
 * Native document settings panels for Works, Portfolio, and Team meta.
 */
import { TextControl } from '@wordpress/components';
import { useDispatch, useSelect } from '@wordpress/data';
import { PluginDocumentSettingPanel } from '@wordpress/editor';
import { __ } from '@wordpress/i18n';

const usePostMeta = () => {
	const { postType, meta } = useSelect( ( select ) => {
		const editor = select( 'core/editor' );

		return {
			postType: editor.getCurrentPostType(),
			meta: editor.getEditedPostAttribute( 'meta' ) || {},
		};
	}, [] );

	const { editPost } = useDispatch( 'core/editor' );

	const setMeta = ( key, value ) => {
		editPost( {
			meta: {
				...meta,
				[ key ]: value,
			},
		} );
	};

	return { postType, meta, setMeta };
};

const PortfolioPanel = ( { meta, setMeta } ) => (
	<PluginDocumentSettingPanel
		name="sekeco-portfolio-details"
		title={ __( 'Work details', 'sekeco' ) }
	>
		<TextControl
			label={ __( 'Client', 'sekeco' ) }
			value={ meta.sekeco_portfolio_client || '' }
			onChange={ ( value ) =>
				setMeta( 'sekeco_portfolio_client', value )
			}
		/>
		<TextControl
			label={ __( 'Year', 'sekeco' ) }
			value={ meta.sekeco_portfolio_year || '' }
			onChange={ ( value ) => setMeta( 'sekeco_portfolio_year', value ) }
			help={ __( 'Examples: 2025 or 2023–2025', 'sekeco' ) }
		/>
		<TextControl
			label={ __( 'Role', 'sekeco' ) }
			value={ meta.sekeco_portfolio_role || '' }
			onChange={ ( value ) => setMeta( 'sekeco_portfolio_role', value ) }
		/>
		<TextControl
			type="url"
			label={ __( 'Project URL', 'sekeco' ) }
			value={ meta.sekeco_portfolio_url || '' }
			onChange={ ( value ) => setMeta( 'sekeco_portfolio_url', value ) }
		/>
	</PluginDocumentSettingPanel>
);

const ProductPanel = ( { meta, setMeta } ) => (
	<PluginDocumentSettingPanel
		name="sekeco-product-details"
		title={ __( 'Portfolio details', 'sekeco' ) }
	>
		<TextControl
			type="url"
			label={ __( 'Project URL', 'sekeco' ) }
			value={ meta.sekeco_product_url || '' }
			onChange={ ( value ) => setMeta( 'sekeco_product_url', value ) }
		/>
		<TextControl
			label={ __( 'Status', 'sekeco' ) }
			value={ meta.sekeco_product_status || '' }
			onChange={ ( value ) => setMeta( 'sekeco_product_status', value ) }
			help={ __( 'Examples: Live, Private beta, Open source', 'sekeco' ) }
		/>
		<TextControl
			label={ __( 'Stack', 'sekeco' ) }
			value={ meta.sekeco_product_stack || '' }
			onChange={ ( value ) => setMeta( 'sekeco_product_stack', value ) }
			help={ __( 'Examples: TypeScript, Supabase', 'sekeco' ) }
		/>
		<TextControl
			label={ __( 'Year', 'sekeco' ) }
			value={ meta.sekeco_product_year || '' }
			onChange={ ( value ) => setMeta( 'sekeco_product_year', value ) }
		/>
		<TextControl
			label={ __( 'Repository', 'sekeco' ) }
			value={ meta.sekeco_product_repo || '' }
			onChange={ ( value ) => setMeta( 'sekeco_product_repo', value ) }
		/>
	</PluginDocumentSettingPanel>
);

const TeamPanel = ( { meta, setMeta } ) => (
	<PluginDocumentSettingPanel
		name="sekeco-team-details"
		title={ __( 'Team details', 'sekeco' ) }
	>
		<TextControl
			label={ __( 'Role', 'sekeco' ) }
			value={ meta.sekeco_team_role || '' }
			onChange={ ( value ) => setMeta( 'sekeco_team_role', value ) }
		/>
		<TextControl
			type="email"
			label={ __( 'Email', 'sekeco' ) }
			value={ meta.sekeco_team_email || '' }
			onChange={ ( value ) => setMeta( 'sekeco_team_email', value ) }
		/>
		<TextControl
			type="url"
			label={ __( 'GitHub', 'sekeco' ) }
			value={ meta.sekeco_team_github || '' }
			onChange={ ( value ) => setMeta( 'sekeco_team_github', value ) }
		/>
		<TextControl
			type="url"
			label={ __( 'LinkedIn', 'sekeco' ) }
			value={ meta.sekeco_team_linkedin || '' }
			onChange={ ( value ) => setMeta( 'sekeco_team_linkedin', value ) }
		/>
		<TextControl
			type="url"
			label={ __( 'Instagram', 'sekeco' ) }
			value={ meta.sekeco_team_instagram || '' }
			onChange={ ( value ) => setMeta( 'sekeco_team_instagram', value ) }
		/>
	</PluginDocumentSettingPanel>
);

export default function MetaPanels() {
	const { postType, meta, setMeta } = usePostMeta();

	if ( 'portfolio' === postType ) {
		return <PortfolioPanel meta={ meta } setMeta={ setMeta } />;
	}

	if ( 'product' === postType ) {
		return <ProductPanel meta={ meta } setMeta={ setMeta } />;
	}

	if ( 'team' === postType ) {
		return <TeamPanel meta={ meta } setMeta={ setMeta } />;
	}

	return null;
}
