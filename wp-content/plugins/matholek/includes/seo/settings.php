<?php
/**
 * SEO settings: site-wide fallbacks for the description and share image.
 *
 * @package Matholek
 */

defined( 'ABSPATH' ) || exit;

/**
 * Stored SEO options merged with defaults.
 *
 * @return array{description:string,image_id:int,twitter:string}
 */
function matholek_seo_options() {
	$stored = get_option( 'matholek_seo', array() );

	return wp_parse_args(
		is_array( $stored ) ? $stored : array(),
		array(
			'description' => '',
			'image_id'    => 0,
			'twitter'     => '',
		)
	);
}

/**
 * Sanitize the SEO options on save.
 *
 * @param mixed $input Raw submitted value.
 * @return array
 */
function matholek_seo_sanitize( $input ) {
	$input   = is_array( $input ) ? $input : array();
	$twitter = isset( $input['twitter'] ) ? sanitize_text_field( $input['twitter'] ) : '';

	return array(
		'description' => isset( $input['description'] ) ? sanitize_textarea_field( $input['description'] ) : '',
		'image_id'    => isset( $input['image_id'] ) ? absint( $input['image_id'] ) : 0,
		'twitter'     => ltrim( $twitter, '@' ),
	);
}

/**
 * Register the setting and its page under Settings.
 *
 * @return void
 */
function matholek_seo_register_settings() {
	register_setting(
		'matholek_seo',
		'matholek_seo',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'matholek_seo_sanitize',
			'default'           => array(),
		)
	);
}
add_action( 'admin_init', 'matholek_seo_register_settings' );

/**
 * Add the Settings → SEO Matholek page.
 *
 * @return void
 */
function matholek_seo_menu() {
	add_options_page(
		__( 'SEO Matholek', 'matholek' ),
		__( 'SEO Matholek', 'matholek' ),
		'manage_options',
		'matholek-seo',
		'matholek_seo_render_page'
	);
}
add_action( 'admin_menu', 'matholek_seo_menu' );

/**
 * Load the media library on the SEO page only.
 *
 * @param string $hook Current admin page hook.
 * @return void
 */
function matholek_seo_admin_assets( $hook ) {
	if ( 'settings_page_matholek-seo' === $hook ) {
		wp_enqueue_media();
	}
}
add_action( 'admin_enqueue_scripts', 'matholek_seo_admin_assets' );

/**
 * Render the settings page.
 *
 * @return void
 */
function matholek_seo_render_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$options = matholek_seo_options();
	$preview = $options['image_id'] ? wp_get_attachment_image_url( $options['image_id'], 'medium' ) : '';
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'SEO Matholek', 'matholek' ); ?></h1>
		<p><?php esc_html_e( 'Deskripsi dan gambar bagikan cadangan untuk halaman yang tidak punya sendiri.', 'matholek' ); ?></p>
		<form method="post" action="options.php">
			<?php settings_fields( 'matholek_seo' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="matholek-seo-description"><?php esc_html_e( 'Deskripsi bawaan', 'matholek' ); ?></label></th>
					<td>
						<textarea id="matholek-seo-description" name="matholek_seo[description]" rows="3" class="large-text"><?php echo esc_textarea( $options['description'] ); ?></textarea>
						<p class="description"><?php esc_html_e( 'Kosongkan untuk memakai tagline situs.', 'matholek' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Gambar bagikan bawaan', 'matholek' ); ?></th>
					<td>
						<input type="hidden" id="matholek-seo-image" name="matholek_seo[image_id]" value="<?php echo esc_attr( (string) $options['image_id'] ); ?>">
						<p><img id="matholek-seo-preview" src="<?php echo esc_url( $preview ); ?>" alt="" style="max-width:320px;height:auto;<?php echo $preview ? '' : 'display:none;'; ?>"></p>
						<button type="button" class="button" id="matholek-seo-pick"><?php esc_html_e( 'Pilih gambar', 'matholek' ); ?></button>
						<button type="button" class="button-link" id="matholek-seo-clear"><?php esc_html_e( 'Hapus', 'matholek' ); ?></button>
						<p class="description"><?php esc_html_e( 'Disarankan 1200 × 630 px. Jika kosong, memakai ikon situs.', 'matholek' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="matholek-seo-twitter"><?php esc_html_e( 'Akun X / Twitter', 'matholek' ); ?></label></th>
					<td><input type="text" id="matholek-seo-twitter" name="matholek_seo[twitter]" value="<?php echo esc_attr( $options['twitter'] ); ?>" class="regular-text" placeholder="pimkajen"></td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<script>
	( function () {
		var field = document.getElementById( 'matholek-seo-image' );
		var preview = document.getElementById( 'matholek-seo-preview' );
		var frame;

		document.getElementById( 'matholek-seo-pick' ).addEventListener( 'click', function () {
			if ( ! frame ) {
				frame = wp.media( { library: { type: 'image' }, multiple: false } );
				frame.on( 'select', function () {
					var item = frame.state().get( 'selection' ).first().toJSON();
					field.value = item.id;
					preview.src = ( item.sizes && item.sizes.medium ? item.sizes.medium.url : item.url );
					preview.style.display = '';
				} );
			}
			frame.open();
		} );

		document.getElementById( 'matholek-seo-clear' ).addEventListener( 'click', function () {
			field.value = '';
			preview.removeAttribute( 'src' );
			preview.style.display = 'none';
		} );
	}() );
	</script>
	<?php
}
