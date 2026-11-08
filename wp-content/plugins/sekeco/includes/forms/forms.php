<?php
/**
 * Form and submission content models, meta boxes, and admin UI.
 *
 * @package Sekeco
 */

defined( 'ABSPATH' ) || exit;

/**
 * Supported field types.
 *
 * @return array
 */
function sekeco_form_field_types() {
	return array(
		'text'           => __( 'Short text', 'sekeco' ),
		'email'          => __( 'Email', 'sekeco' ),
		'tel'            => __( 'Phone', 'sekeco' ),
		'url'            => __( 'URL', 'sekeco' ),
		'number'         => __( 'Number', 'sekeco' ),
		'date'           => __( 'Date', 'sekeco' ),
		'time'           => __( 'Time', 'sekeco' ),
		'textarea'       => __( 'Long text', 'sekeco' ),
		'select'         => __( 'Dropdown', 'sekeco' ),
		'radio'          => __( 'Radio buttons', 'sekeco' ),
		'checkbox'       => __( 'Single checkbox', 'sekeco' ),
		'checkbox_group' => __( 'Checkbox group', 'sekeco' ),
	);
}

/**
 * Field types that use an options list.
 *
 * @return array
 */
function sekeco_form_option_types() {
	return array( 'select', 'radio', 'checkbox_group' );
}

/**
 * Register the form and submission post types.
 *
 * @return void
 */
function sekeco_register_form_types() {
	register_post_type(
		'sekeco_form',
		array(
			'labels'              => array(
				'name'               => _x( 'Forms', 'Post type general name', 'sekeco' ),
				'singular_name'      => _x( 'Form', 'Post type singular name', 'sekeco' ),
				'menu_name'          => _x( 'Forms', 'Admin menu text', 'sekeco' ),
				'add_new'            => __( 'Add New', 'sekeco' ),
				'add_new_item'       => __( 'Add New Form', 'sekeco' ),
				'edit_item'          => __( 'Edit Form', 'sekeco' ),
				'view_item'          => __( 'View Form', 'sekeco' ),
				'all_items'          => __( 'Forms', 'sekeco' ),
				'search_items'       => __( 'Search Forms', 'sekeco' ),
				'not_found'          => __( 'No forms found.', 'sekeco' ),
				'not_found_in_trash' => __( 'No forms found in Trash.', 'sekeco' ),
			),
			'public'              => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_rest'        => true,
			'menu_position'       => 23,
			'menu_icon'           => 'dashicons-feedback',
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
			'supports'            => array( 'title' ),
			'rewrite'             => array(
				'slug'       => 'form',
				'with_front' => false,
			),
			'query_var'           => true,
		)
	);

	register_post_type(
		'sekeco_submission',
		array(
			'labels'              => array(
				'name'               => _x( 'Submissions', 'Post type general name', 'sekeco' ),
				'singular_name'      => _x( 'Submission', 'Post type singular name', 'sekeco' ),
				'menu_name'          => _x( 'Submissions', 'Admin menu text', 'sekeco' ),
				'edit_item'          => __( 'Submission', 'sekeco' ),
				'all_items'          => __( 'Submissions', 'sekeco' ),
				'search_items'       => __( 'Search Submissions', 'sekeco' ),
				'not_found'          => __( 'No submissions yet.', 'sekeco' ),
				'not_found_in_trash' => __( 'No submissions found in Trash.', 'sekeco' ),
			),
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => 'edit.php?post_type=sekeco_form',
			'show_in_rest'        => false,
			'menu_position'       => 24,
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
			'supports'            => array( 'title' ),
			'exclude_from_search' => true,
		)
	);
}
add_action( 'init', 'sekeco_register_form_types' );

/**
 * Sanitize the field definitions array.
 *
 * @param mixed $value Raw fields.
 * @return array
 */
function sekeco_sanitize_form_fields( $value ) {
	if ( ! is_array( $value ) ) {
		return array();
	}

	$types = array_keys( sekeco_form_field_types() );
	$out   = array();

	foreach ( $value as $row ) {
		if ( ! is_array( $row ) ) {
			continue;
		}

		$label = isset( $row['label'] ) ? sanitize_text_field( $row['label'] ) : '';

		if ( '' === $label ) {
			continue;
		}

		$type = isset( $row['type'] ) && in_array( $row['type'], $types, true )
			? $row['type']
			: 'text';

		$options = array();

		if ( isset( $row['options'] ) && is_string( $row['options'] ) ) {
			$lines = preg_split( '/[\r\n,]+/', $row['options'] );

			foreach ( (array) $lines as $option ) {
				$option = sanitize_text_field( trim( $option ) );

				if ( '' !== $option ) {
					$options[] = $option;
				}
			}
		}

		$width = isset( $row['width'] ) && 'half' === $row['width'] ? 'half' : 'full';

		$out[] = array(
			'label'       => $label,
			'type'        => $type,
			'required'    => empty( $row['required'] ) ? 0 : 1,
			'options'     => implode( "\n", $options ),
			'placeholder' => isset( $row['placeholder'] ) ? sanitize_text_field( $row['placeholder'] ) : '',
			'help'        => isset( $row['help'] ) ? sanitize_text_field( $row['help'] ) : '',
			'width'       => $width,
		);
	}

	return $out;
}

/**
 * Sanitize the display mode.
 *
 * @param mixed $value Raw mode.
 * @return string
 */
function sekeco_sanitize_form_mode( $value ) {
	return 'survey' === $value ? 'survey' : 'form';
}

/**
 * Register form meta.
 *
 * @return void
 */
function sekeco_register_form_meta() {
	$fields = array(
		'_sekeco_form_fields'      => 'sekeco_sanitize_form_fields',
		'_sekeco_form_mode'        => 'sekeco_sanitize_form_mode',
		'_sekeco_form_submit'      => 'sanitize_text_field',
		'_sekeco_form_success'     => 'sanitize_text_field',
		'_sekeco_form_description' => 'sanitize_textarea_field',
		'_sekeco_form_redirect'    => 'esc_url_raw',
		'_sekeco_form_notify'      => 'sanitize_email',
	);

	foreach ( $fields as $key => $sanitize ) {
		register_post_meta(
			'sekeco_form',
			$key,
			array(
				'single'            => true,
				'show_in_rest'      => false,
				'sanitize_callback' => $sanitize,
				'auth_callback'     => 'sekeco_meta_auth_callback',
			)
		);
	}

	register_post_meta(
		'sekeco_submission',
		'_sekeco_submission_data',
		array(
			'single'        => true,
			'show_in_rest'  => false,
			'auth_callback' => 'sekeco_meta_auth_callback',
		)
	);

	register_post_meta(
		'sekeco_submission',
		'_sekeco_submission_meta',
		array(
			'single'        => true,
			'show_in_rest'  => false,
			'auth_callback' => 'sekeco_meta_auth_callback',
		)
	);
}
add_action( 'init', 'sekeco_register_form_meta', 20 );

/**
 * Add a "View Form" row action.
 *
 * @param array   $actions Row actions.
 * @param WP_Post $post    Post object.
 * @return array
 */
function sekeco_form_row_actions( $actions, $post ) {
	if ( 'sekeco_form' !== $post->post_type || 'publish' !== $post->post_status ) {
		return $actions;
	}

	return $actions;
}
add_filter( 'post_row_actions', 'sekeco_form_row_actions', 10, 2 );

/**
 * Count submissions for a form.
 *
 * @param int $form_id Form ID.
 * @return int
 */
function sekeco_form_entries_count( $form_id ) {
	global $wpdb;

	return (int) $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COUNT(*) FROM {$wpdb->posts}
			WHERE post_parent = %d
				AND post_type = 'sekeco_submission'
				AND post_status = 'publish'",
			$form_id
		)
	);
}

/**
 * Add an "Entries" column to the forms list.
 *
 * @param array $columns Columns.
 * @return array
 */
function sekeco_form_columns( $columns ) {
	$new = array();

	foreach ( $columns as $key => $label ) {
		$new[ $key ] = $label;

		if ( 'title' === $key ) {
			$new['sekeco_entries'] = __( 'Entries', 'sekeco' );
		}
	}

	return $new;
}
add_filter( 'manage_sekeco_form_posts_columns', 'sekeco_form_columns' );

/**
 * Render the entries column.
 *
 * @param string $column  Column key.
 * @param int    $post_id Form ID.
 * @return void
 */
function sekeco_form_column_content( $column, $post_id ) {
	if ( 'sekeco_entries' !== $column ) {
		return;
	}

	$count = sekeco_form_entries_count( $post_id );

	if ( ! $count ) {
		echo '&mdash;';
		return;
	}

	printf(
		'<a href="%1$s">%2$s</a>',
		esc_url( admin_url( 'edit.php?post_type=sekeco_submission' ) ),
		esc_html( (string) $count )
	);
}
add_action( 'manage_sekeco_form_posts_custom_column', 'sekeco_form_column_content', 10, 2 );

/**
 * Register the form editor meta boxes.
 *
 * @return void
 */
function sekeco_add_form_meta_boxes() {
	add_meta_box(
		'sekeco_form_fields',
		__( 'Fields', 'sekeco' ),
		'sekeco_render_form_fields_box',
		'sekeco_form',
		'normal',
		'high'
	);

	add_meta_box(
		'sekeco_form_settings',
		__( 'Form settings', 'sekeco' ),
		'sekeco_render_form_settings_box',
		'sekeco_form',
		'side',
		'default'
	);

	add_meta_box(
		'sekeco_submission_data',
		__( 'Submitted values', 'sekeco' ),
		'sekeco_render_submission_box',
		'sekeco_submission',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'sekeco_add_form_meta_boxes' );

/**
 * Render a single field card.
 *
 * @param int|string $index Field index.
 * @param array      $field Field definition.
 * @return void
 */
function sekeco_render_field_row( $index, $field = array() ) {
	$field = wp_parse_args(
		$field,
		array(
			'label'       => '',
			'type'        => 'text',
			'required'    => 0,
			'options'     => '',
			'placeholder' => '',
			'help'        => '',
			'width'       => 'full',
		)
	);

	$name = 'sekeco_fields[' . $index . ']';
	$uid  = 'sekeco-field-' . $index;
	$has_options = in_array( $field['type'], sekeco_form_option_types(), true );
	?>
	<div class="sekeco-field" data-index="<?php echo esc_attr( $index ); ?>">
		<div class="sekeco-field__head">
			<span class="sekeco-field__handle dashicons dashicons-menu" title="<?php esc_attr_e( 'Drag to reorder', 'sekeco' ); ?>" aria-hidden="true"></span>
			<input
				type="text"
				class="sekeco-field__label"
				name="<?php echo esc_attr( $name ); ?>[label]"
				value="<?php echo esc_attr( $field['label'] ); ?>"
				placeholder="<?php esc_attr_e( 'Question', 'sekeco' ); ?>"
				aria-label="<?php esc_attr_e( 'Field label', 'sekeco' ); ?>"
			/>
			<span class="sekeco-field__actions">
				<button type="button" class="button-link sekeco-collapse" title="<?php esc_attr_e( 'Collapse', 'sekeco' ); ?>" aria-label="<?php esc_attr_e( 'Collapse field', 'sekeco' ); ?>" aria-expanded="true"><span class="dashicons dashicons-arrow-up"></span></button>
				<button type="button" class="button-link sekeco-move-up" title="<?php esc_attr_e( 'Move up', 'sekeco' ); ?>" aria-label="<?php esc_attr_e( 'Move up', 'sekeco' ); ?>"><span class="dashicons dashicons-arrow-up-alt2"></span></button>
				<button type="button" class="button-link sekeco-move-down" title="<?php esc_attr_e( 'Move down', 'sekeco' ); ?>" aria-label="<?php esc_attr_e( 'Move down', 'sekeco' ); ?>"><span class="dashicons dashicons-arrow-down-alt2"></span></button>
				<button type="button" class="button-link sekeco-duplicate" title="<?php esc_attr_e( 'Duplicate', 'sekeco' ); ?>" aria-label="<?php esc_attr_e( 'Duplicate', 'sekeco' ); ?>"><span class="dashicons dashicons-admin-page"></span></button>
				<button type="button" class="button-link sekeco-remove-field" title="<?php esc_attr_e( 'Delete', 'sekeco' ); ?>" aria-label="<?php esc_attr_e( 'Delete', 'sekeco' ); ?>"><span class="dashicons dashicons-trash"></span></button>
			</span>
		</div>

		<div class="sekeco-field__meta">
			<p class="sekeco-field__control">
				<label class="sekeco-field__control-label" for="<?php echo esc_attr( $uid ); ?>-type"><?php esc_html_e( 'Type', 'sekeco' ); ?></label>
				<select id="<?php echo esc_attr( $uid ); ?>-type" name="<?php echo esc_attr( $name ); ?>[type]" class="sekeco-field__type">
					<?php foreach ( sekeco_form_field_types() as $value => $label ) : ?>
						<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $field['type'], $value ); ?>><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select>
			</p>

			<p class="sekeco-field__control">
				<span class="sekeco-field__control-label"><?php esc_html_e( 'Required', 'sekeco' ); ?></span>
				<label class="sekeco-field__check">
					<input type="checkbox" name="<?php echo esc_attr( $name ); ?>[required]" value="1" <?php checked( ! empty( $field['required'] ) ); ?> />
					<?php esc_html_e( 'Required field', 'sekeco' ); ?>
				</label>
			</p>

			<p class="sekeco-field__control">
				<label class="sekeco-field__control-label" for="<?php echo esc_attr( $uid ); ?>-width"><?php esc_html_e( 'Width', 'sekeco' ); ?></label>
				<select id="<?php echo esc_attr( $uid ); ?>-width" name="<?php echo esc_attr( $name ); ?>[width]" class="sekeco-field__width">
					<option value="full" <?php selected( $field['width'], 'full' ); ?>><?php esc_html_e( 'Full width', 'sekeco' ); ?></option>
					<option value="half" <?php selected( $field['width'], 'half' ); ?>><?php esc_html_e( 'Half width', 'sekeco' ); ?></option>
				</select>
			</p>

			<p class="sekeco-field__control">
				<label class="sekeco-field__control-label" for="<?php echo esc_attr( $uid ); ?>-placeholder"><?php esc_html_e( 'Placeholder', 'sekeco' ); ?></label>
				<input type="text" class="regular-text" id="<?php echo esc_attr( $uid ); ?>-placeholder" name="<?php echo esc_attr( $name ); ?>[placeholder]" value="<?php echo esc_attr( $field['placeholder'] ); ?>" />
			</p>

			<p class="sekeco-field__control">
				<label class="sekeco-field__control-label" for="<?php echo esc_attr( $uid ); ?>-help"><?php esc_html_e( 'Help text', 'sekeco' ); ?></label>
				<input type="text" class="regular-text" id="<?php echo esc_attr( $uid ); ?>-help" name="<?php echo esc_attr( $name ); ?>[help]" value="<?php echo esc_attr( $field['help'] ); ?>" />
			</p>

			<div class="sekeco-field__control sekeco-field__control--wide sekeco-field__options-wrap"<?php echo $has_options ? '' : ' hidden'; ?>>
				<span class="sekeco-field__control-label"><?php esc_html_e( 'Options', 'sekeco' ); ?></span>
				<div class="sekeco-options"></div>
				<button type="button" class="button-link sekeco-option-add"><?php esc_html_e( '+ Add option', 'sekeco' ); ?></button>
				<textarea
					class="sekeco-field__options"
					name="<?php echo esc_attr( $name ); ?>[options]"
					rows="3"
					hidden
				><?php echo esc_textarea( $field['options'] ); ?></textarea>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Render the fields meta box.
 *
 * @param WP_Post $post Post object.
 * @return void
 */
function sekeco_render_form_fields_box( $post ) {
	$fields   = sekeco_get_form_fields( $post->ID );
	$settings = sekeco_get_form_settings( $post->ID );
	$preview  = 'publish' === $post->post_status
		? get_permalink( $post )
		: get_preview_post_link( $post );

	wp_nonce_field( 'sekeco_save_form', 'sekeco_form_meta_nonce' );
	?>
	<div class="sekeco-form-description">
		<label class="screen-reader-text" for="sekeco-form-description"><?php esc_html_e( 'Form description', 'sekeco' ); ?></label>
		<textarea
			id="sekeco-form-description"
			class="large-text"
			name="sekeco_form_description"
			rows="2"
			placeholder="<?php esc_attr_e( 'Form description (optional)', 'sekeco' ); ?>"
		><?php echo esc_textarea( $settings['description'] ); ?></textarea>
	</div>

	<div id="sekeco-fields-body" class="sekeco-fields">
		<?php
		if ( $fields ) {
			foreach ( $fields as $index => $field ) {
				sekeco_render_field_row( $index, $field );
			}
		} else {
			sekeco_render_field_row( 0 );
		}
		?>
	</div>

	<div class="sekeco-builder-bar">
		<select id="sekeco-add-type" aria-label="<?php esc_attr_e( 'Field type to add', 'sekeco' ); ?>">
			<?php foreach ( sekeco_form_field_types() as $value => $label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
		<button type="button" class="button button-primary" id="sekeco-add-field"><?php esc_html_e( '+ Add field', 'sekeco' ); ?></button>
		<a class="button sekeco-builder-bar__preview" href="<?php echo esc_url( $preview ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Preview', 'sekeco' ); ?></a>
	</div>

	<template id="sekeco-field-template">
		<?php sekeco_render_field_row( '__INDEX__' ); ?>
	</template>
	<?php
}

/**
 * Render the settings meta box.
 *
 * @param WP_Post $post Post object.
 * @return void
 */
function sekeco_render_form_settings_box( $post ) {
	$settings = sekeco_get_form_settings( $post->ID );
	$entries  = sekeco_form_entries_count( $post->ID );
	?>
	<p>
		<label for="sekeco-form-mode"><strong><?php esc_html_e( 'Mode', 'sekeco' ); ?></strong></label><br />
		<select id="sekeco-form-mode" name="sekeco_form_mode" class="widefat">
			<option value="form" <?php selected( $settings['mode'], 'form' ); ?>><?php esc_html_e( 'Form', 'sekeco' ); ?></option>
			<option value="survey" <?php selected( $settings['mode'], 'survey' ); ?>><?php esc_html_e( 'Survey (step by step)', 'sekeco' ); ?></option>
		</select>
	</p>
	<p>
		<label for="sekeco-form-submit"><strong><?php esc_html_e( 'Button label', 'sekeco' ); ?></strong></label>
		<input type="text" id="sekeco-form-submit" name="sekeco_form_submit" class="widefat" value="<?php echo esc_attr( $settings['submit'] ); ?>" />
	</p>
	<p>
		<label for="sekeco-form-success"><strong><?php esc_html_e( 'Success message', 'sekeco' ); ?></strong></label>
		<textarea id="sekeco-form-success" name="sekeco_form_success" class="widefat" rows="3"><?php echo esc_textarea( $settings['success'] ); ?></textarea>
	</p>
	<p>
		<label for="sekeco-form-redirect"><strong><?php esc_html_e( 'Redirect after submit', 'sekeco' ); ?></strong></label>
		<input type="url" id="sekeco-form-redirect" name="sekeco_form_redirect" class="widefat" value="<?php echo esc_attr( $settings['redirect'] ); ?>" placeholder="https://" />
		<span class="description"><?php esc_html_e( 'Optional. Leave empty to keep the success message inline.', 'sekeco' ); ?></span>
	</p>
	<p>
		<label for="sekeco-form-notify"><strong><?php esc_html_e( 'Notify email', 'sekeco' ); ?></strong></label>
		<input type="email" id="sekeco-form-notify" name="sekeco_form_notify" class="widefat" value="<?php echo esc_attr( $settings['notify'] ); ?>" />
		<span class="description"><?php esc_html_e( 'Leave empty to use the site admin email.', 'sekeco' ); ?></span>
	</p>
	<p>
		<strong><?php esc_html_e( 'Shortcode', 'sekeco' ); ?></strong><br />
		<code>[sekeco_form id="<?php echo esc_html( (string) $post->ID ); ?>"]</code>
	</p>
	<p>
		<strong><?php esc_html_e( 'Submissions', 'sekeco' ); ?></strong><br />
		<?php if ( $entries ) : ?>
			<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=sekeco_submission' ) ); ?>"><?php echo esc_html( (string) $entries ); ?></a>
			&middot;
			<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=sekeco_export_submissions&form_id=' . $post->ID ), 'sekeco_export_' . $post->ID ) ); ?>"><?php esc_html_e( 'Export CSV', 'sekeco' ); ?></a>
		<?php else : ?>
			<em><?php esc_html_e( 'None yet', 'sekeco' ); ?></em>
		<?php endif; ?>
	</p>
	<?php
}

/**
 * Save form meta.
 *
 * @param int $post_id Post ID.
 * @return void
 */
function sekeco_save_form_meta( $post_id ) {
	if ( ! isset( $_POST['sekeco_form_meta_nonce'] ) ) {
		return;
	}

	$nonce = sanitize_text_field( wp_unslash( $_POST['sekeco_form_meta_nonce'] ) );

	if ( ! wp_verify_nonce( $nonce, 'sekeco_save_form' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$raw_fields = isset( $_POST['sekeco_fields'] ) && is_array( $_POST['sekeco_fields'] )
		? wp_unslash( $_POST['sekeco_fields'] )
		: array();

	update_post_meta( $post_id, '_sekeco_form_fields', sekeco_sanitize_form_fields( $raw_fields ) );

	$mode = isset( $_POST['sekeco_form_mode'] )
		? sanitize_text_field( wp_unslash( $_POST['sekeco_form_mode'] ) )
		: 'form';

	update_post_meta( $post_id, '_sekeco_form_mode', sekeco_sanitize_form_mode( $mode ) );

	$text_settings = array(
		'_sekeco_form_submit'      => 'sekeco_form_submit',
		'_sekeco_form_success'     => 'sekeco_form_success',
		'_sekeco_form_description' => 'sekeco_form_description',
	);

	foreach ( $text_settings as $meta_key => $field ) {
		$value = isset( $_POST[ $field ] )
			? sanitize_text_field( wp_unslash( $_POST[ $field ] ) )
			: '';

		update_post_meta( $post_id, $meta_key, $value );
	}

	$redirect = isset( $_POST['sekeco_form_redirect'] )
		? esc_url_raw( wp_unslash( $_POST['sekeco_form_redirect'] ) )
		: '';

	update_post_meta( $post_id, '_sekeco_form_redirect', $redirect );

	$notify = isset( $_POST['sekeco_form_notify'] )
		? sanitize_email( wp_unslash( $_POST['sekeco_form_notify'] ) )
		: '';

	update_post_meta( $post_id, '_sekeco_form_notify', $notify );
}
add_action( 'save_post_sekeco_form', 'sekeco_save_form_meta' );

/**
 * Enqueue the field builder on form screens.
 *
 * @param string $hook Current admin page.
 * @return void
 */
function sekeco_enqueue_form_admin_assets( $hook ) {
	$screen = get_current_screen();

	if ( ! $screen || 'sekeco_form' !== $screen->post_type ) {
		return;
	}

	wp_enqueue_script(
		'sekeco-form-admin',
		SEKECO_URL . 'assets/admin/form-fields.js',
		array(),
		SEKECO_VERSION,
		true
	);

	wp_enqueue_style(
		'sekeco-form-admin',
		SEKECO_URL . 'assets/admin/form-fields.css',
		array(),
		SEKECO_VERSION
	);
}
add_action( 'admin_enqueue_scripts', 'sekeco_enqueue_form_admin_assets' );

/**
 * Render the submission values meta box.
 *
 * @param WP_Post $post Submission post.
 * @return void
 */
function sekeco_render_submission_box( $post ) {
	$data = get_post_meta( $post->ID, '_sekeco_submission_data', true );
	$meta = get_post_meta( $post->ID, '_sekeco_submission_meta', true );

	if ( ! is_array( $data ) || ! $data ) {
		echo '<p>' . esc_html__( 'No values stored.', 'sekeco' ) . '</p>';
		return;
	}

	echo '<table class="widefat striped"><tbody>';

	foreach ( $data as $label => $value ) {
		echo '<tr><th style="width:30%">' . esc_html( $label ) . '</th><td>' . nl2br( esc_html( (string) $value ) ) . '</td></tr>';
	}

	echo '</tbody></table>';

	if ( is_array( $meta ) && $meta ) {
		echo '<p class="description">' . esc_html( implode( ' · ', array_filter( $meta ) ) ) . '</p>';
	}
}

/**
 * Add a "Form" column to the submission list.
 *
 * @param array $columns Columns.
 * @return array
 */
function sekeco_submission_columns( $columns ) {
	$new = array();

	foreach ( $columns as $key => $label ) {
		$new[ $key ] = $label;

		if ( 'title' === $key ) {
			$new['sekeco_source_form'] = __( 'Form', 'sekeco' );
		}
	}

	return $new;
}
add_filter( 'manage_sekeco_submission_posts_columns', 'sekeco_submission_columns' );

/**
 * Render the submission "Form" column.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 * @return void
 */
function sekeco_submission_column_content( $column, $post_id ) {
	if ( 'sekeco_source_form' !== $column ) {
		return;
	}

	$parent = get_post_parent( $post_id );

	echo $parent ? esc_html( $parent->post_title ) : '&mdash;';
}
add_action( 'manage_sekeco_submission_posts_custom_column', 'sekeco_submission_column_content', 10, 2 );

/**
 * Export submissions for a form as CSV.
 *
 * @return void
 */
function sekeco_export_submissions() {
	$form_id = isset( $_GET['form_id'] ) ? absint( $_GET['form_id'] ) : 0;

	if ( ! $form_id || ! current_user_can( 'edit_post', $form_id ) ) {
		wp_die( esc_html__( 'You are not allowed to export these submissions.', 'sekeco' ) );
	}

	check_admin_referer( 'sekeco_export_' . $form_id );

	$fields = sekeco_get_form_fields( $form_id );
	$labels = array();

	foreach ( $fields as $field ) {
		$labels[] = $field['label'];
	}

	$filename = sanitize_title( get_the_title( $form_id ) ) . '-submissions.csv';

	nocache_headers();
	header( 'Content-Type: text/csv; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename="' . $filename . '"' );

	$output = fopen( 'php://output', 'w' );

	fputcsv( $output, array_merge( array( 'ID', 'Date' ), $labels ) );

	$submissions = get_posts(
		array(
			'post_type'      => 'sekeco_submission',
			'post_status'    => 'publish',
			'post_parent'    => $form_id,
			'posts_per_page' => -1,
			'orderby'        => 'ID',
			'order'          => 'ASC',
		)
	);

	foreach ( $submissions as $submission ) {
		$data = get_post_meta( $submission->ID, '_sekeco_submission_data', true );
		$row  = array( $submission->ID, $submission->post_date );

		foreach ( $labels as $label ) {
			$row[] = is_array( $data ) && isset( $data[ $label ] ) ? $data[ $label ] : '';
		}

		fputcsv( $output, $row );
	}

	fclose( $output );
	exit;
}
add_action( 'admin_post_sekeco_export_submissions', 'sekeco_export_submissions' );
