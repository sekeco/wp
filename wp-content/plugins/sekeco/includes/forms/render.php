<?php
/**
 * Frontend rendering for Sekeco forms and surveys.
 *
 * @package Sekeco
 */

defined( 'ABSPATH' ) || exit;

/**
 * Read the field definitions for a form.
 *
 * @param int $form_id Form post ID.
 * @return array
 */
function sekeco_get_form_fields( $form_id ) {
	$fields = get_post_meta( $form_id, '_sekeco_form_fields', true );

	if ( ! is_array( $fields ) ) {
		return array();
	}

	return array_map(
		static function ( $field ) {
			return wp_parse_args(
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
		},
		$fields
	);
}

/**
 * Read the settings for a form.
 *
 * @param int $form_id Form post ID.
 * @return array
 */
function sekeco_get_form_settings( $form_id ) {
	$submit      = get_post_meta( $form_id, '_sekeco_form_submit', true );
	$success     = get_post_meta( $form_id, '_sekeco_form_success', true );
	$description = get_post_meta( $form_id, '_sekeco_form_description', true );
	$redirect    = get_post_meta( $form_id, '_sekeco_form_redirect', true );
	$notify      = get_post_meta( $form_id, '_sekeco_form_notify', true );

	return array(
		'mode'        => sekeco_sanitize_form_mode( get_post_meta( $form_id, '_sekeco_form_mode', true ) ),
		'submit'      => '' !== $submit ? $submit : __( 'Send', 'sekeco' ),
		'success'     => '' !== $success ? $success : __( 'Thanks — we got it and will be in touch.', 'sekeco' ),
		'description' => $description,
		'redirect'    => $redirect,
		'notify'      => '' !== $notify ? $notify : get_option( 'admin_email' ),
	);
}

/**
 * Split an options string into a list.
 *
 * @param string $options Stored options.
 * @return array
 */
function sekeco_form_options_list( $options ) {
	if ( '' === (string) $options ) {
		return array();
	}

	$lines = preg_split( '/[\r\n,]+/', (string) $options );

	return array_values( array_filter( array_map( 'trim', (array) $lines ) ) );
}

/**
 * Render the input controls for one field.
 *
 * @param int    $form_id     Form ID.
 * @param int    $index       Field index.
 * @param array  $field       Field definition.
 * @param string $describedby Space-separated element IDs.
 * @return void
 */
function sekeco_render_form_control( $form_id, $index, $field, $describedby = '' ) {
	$type        = $field['type'];
	$name        = 'f' . $index;
	$id          = 'sekeco-' . $form_id . '-f' . $index;
	$required    = ! empty( $field['required'] );
	$options     = sekeco_form_options_list( $field['options'] );
	$placeholder = isset( $field['placeholder'] ) ? $field['placeholder'] : '';
	$described   = '';

	if ( '' !== $describedby ) {
		$described = ' aria-describedby="' . esc_attr( $describedby ) . '"';
	}

	$common = sprintf(
		'id="%1$s" name="%2$s"%3$s%4$s',
		esc_attr( $id ),
		esc_attr( $name ),
		$required ? ' required aria-required="true"' : '',
		$described
	);

	$with_placeholder = '' !== $placeholder
		? $common . ' placeholder="' . esc_attr( $placeholder ) . '"'
		: $common;

	switch ( $type ) {
		case 'textarea':
			printf(
				'<textarea %s rows="5"></textarea>',
				$with_placeholder // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
			break;

		case 'select':
			printf( '<select %s>', $common ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			printf(
				'<option value="">%s</option>',
				esc_html( '' !== $placeholder ? $placeholder : __( 'Select an option…', 'sekeco' ) )
			);

			foreach ( $options as $option ) {
				printf( '<option value="%1$s">%1$s</option>', esc_attr( $option ) );
			}

			echo '</select>';
			break;

		case 'radio':
		case 'checkbox_group':
			$input_type = 'radio' === $type ? 'radio' : 'checkbox';

			echo '<div class="sekeco-form__choices">';

			foreach ( $options as $choice_index => $option ) {
				$choice_id = $id . '-' . $choice_index;
				printf(
					'<label class="sekeco-form__choice" for="%1$s"><input type="%2$s" id="%1$s" name="%3$s%4$s" value="%5$s"%6$s /> <span>%5$s</span></label>',
					esc_attr( $choice_id ),
					esc_attr( $input_type ),
					esc_attr( $name ),
					'radio' === $type ? '' : '[]',
					esc_attr( $option ),
					$required ? ( 'radio' === $type ? ' required aria-required="true"' : ' data-required-group="1"' ) : ''
				);
			}

			echo '</div>';
			break;

		case 'checkbox':
			printf(
				'<label class="sekeco-form__choice" for="%1$s"><input type="checkbox" id="%1$s" name="%2$s" value="1"%3$s%4$s /> <span>%5$s</span></label>',
				esc_attr( $id ),
				esc_attr( $name ),
				$required ? ' required aria-required="true"' : '',
				$described,
				esc_html__( 'I agree', 'sekeco' )
			);
			break;

		default:
			printf(
				'<input type="%1$s" %2$s />',
				esc_attr( in_array( $type, array( 'text', 'email', 'tel', 'url', 'number', 'date', 'time' ), true ) ? $type : 'text' ),
				$with_placeholder // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
	}
}

/**
 * Render a form.
 *
 * @param int $form_id Form post ID.
 * @return string
 */
function sekeco_render_form( $form_id ) {
	$form_id = absint( $form_id );
	$form    = get_post( $form_id );

	if ( ! $form || 'sekeco_form' !== $form->post_type || 'publish' !== $form->post_status ) {
		return '';
	}

	$fields = sekeco_get_form_fields( $form_id );

	if ( ! $fields ) {
		return '';
	}

	$settings  = sekeco_get_form_settings( $form_id );
	$is_survey = 'survey' === $settings['mode'];

	$status      = isset( $_GET['sekeco_form_status'] ) ? sanitize_key( wp_unslash( $_GET['sekeco_form_status'] ) ) : '';
	$status_form = isset( $_GET['sekeco_form_id'] ) ? absint( $_GET['sekeco_form_id'] ) : 0;

	$classes = 'sekeco-form';

	if ( $is_survey ) {
		$classes .= ' sekeco-form--survey';
	}

	$rest_url = rest_url( 'sekeco/v1/forms/' . $form_id . '/submit' );

	ob_start();
	?>
	<form
		class="<?php echo esc_attr( $classes ); ?>"
		method="post"
		action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
		data-sekeco-form
		data-form-id="<?php echo esc_attr( $form_id ); ?>"
		data-rest="<?php echo esc_url( $rest_url ); ?>"
		data-success="<?php echo esc_attr( $settings['success'] ); ?>"
		data-redirect="<?php echo esc_url( $settings['redirect'] ); ?>"
		<?php if ( is_user_logged_in() ) : ?>
			data-sekeco-rest-nonce="<?php echo esc_attr( wp_create_nonce( 'wp_rest' ) ); ?>"
		<?php endif; ?>
		<?php echo $is_survey ? 'data-sekeco-survey' : ''; ?>
		novalidate
	>
		<input type="hidden" name="action" value="sekeco_form_submit" />
		<input type="hidden" name="sekeco_form_id" value="<?php echo esc_attr( $form_id ); ?>" />
		<input type="hidden" name="sekeco_form_nonce" value="<?php echo esc_attr( wp_create_nonce( 'sekeco_form_' . $form_id ) ); ?>" />
		<input type="hidden" name="sekeco_form_time" value="<?php echo esc_attr( time() ); ?>" />
		<input type="hidden" name="sekeco_form_redirect" value="<?php echo esc_url( get_permalink() ? get_permalink() : home_url( '/' ) ); ?>" />
		<div class="sekeco-form__honeypot" aria-hidden="true">
			<label for="sekeco-<?php echo esc_attr( $form_id ); ?>-website">Website</label>
			<input type="text" id="sekeco-<?php echo esc_attr( $form_id ); ?>-website" name="sekeco_website" tabindex="-1" autocomplete="off" />
		</div>

		<?php if ( '' !== $settings['description'] ) : ?>
			<p class="sekeco-form__description"><?php echo nl2br( esc_html( $settings['description'] ) ); ?></p>
		<?php endif; ?>

		<?php if ( $is_survey ) : ?>
			<div class="sekeco-form__meta">
				<span class="sekeco-form__counter" aria-live="polite">
					<?php echo esc_html( '01 / ' . str_pad( (string) count( $fields ), 2, '0', STR_PAD_LEFT ) ); ?>
				</span>
			</div>
			<div class="sekeco-form__progress" aria-hidden="true">
				<span class="sekeco-form__progress-bar" style="width:<?php echo esc_attr( round( 100 / max( 1, count( $fields ) ) ) ); ?>%"></span>
			</div>
		<?php endif; ?>

		<?php if ( 'ok' === $status && $status_form === $form_id ) : ?>
			<p class="sekeco-form__notice is-success"><?php echo esc_html( $settings['success'] ); ?></p>
		<?php elseif ( 'error' === $status && $status_form === $form_id ) : ?>
			<p class="sekeco-form__notice is-error"><?php esc_html_e( 'Something went wrong. Please review the fields and try again.', 'sekeco' ); ?></p>
		<?php endif; ?>

		<div class="<?php echo $is_survey ? 'sekeco-form__steps' : 'sekeco-form__fields'; ?>">
			<?php foreach ( $fields as $index => $field ) : ?>
				<?php
				$field_id = 'sekeco-' . $form_id . '-f' . $index;
				$help_id  = $field_id . '-help';
				$is_choice = in_array( $field['type'], array( 'radio', 'checkbox', 'checkbox_group' ), true );

				$describedby = $field_id . '-error';

				if ( '' !== $field['help'] ) {
					$describedby = $help_id . ' ' . $describedby;
				}

				$field_classes = 'sekeco-form__field';

				if ( 'half' === $field['width'] && ! $is_survey ) {
					$field_classes .= ' sekeco-form__field--half';
				}

				if ( $is_survey ) {
					$field_classes .= ' sekeco-form__question sekeco-form__step';
				}
				?>
				<div class="<?php echo esc_attr( $field_classes ); ?>"<?php echo $is_survey ? ' data-step="' . esc_attr( $index ) . '"' : ''; ?>>
					<?php if ( $is_survey ) : ?>
						<span class="sekeco-form__number" aria-hidden="true"><?php echo esc_html( str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
					<?php endif; ?>

					<label class="sekeco-form__label" for="<?php echo esc_attr( $field_id ); ?>">
						<?php echo esc_html( $field['label'] ); ?>
						<?php if ( ! empty( $field['required'] ) ) : ?>
							<span class="sekeco-form__required" aria-hidden="true">*</span>
						<?php endif; ?>
					</label>

					<?php sekeco_render_form_control( $form_id, $index, $field, $describedby ); ?>

					<?php if ( '' !== $field['help'] ) : ?>
						<p class="sekeco-form__help" id="<?php echo esc_attr( $help_id ); ?>"><?php echo esc_html( $field['help'] ); ?></p>
					<?php endif; ?>

					<p class="sekeco-form__error" id="<?php echo esc_attr( $field_id ); ?>-error" hidden></p>
				</div>
			<?php endforeach; ?>
		</div>

		<?php if ( $is_survey ) : ?>
			<div class="sekeco-form__nav">
				<button type="button" class="sekeco-form__back" hidden><?php esc_html_e( 'Back', 'sekeco' ); ?></button>
				<button type="button" class="sekeco-form__next" hidden><?php esc_html_e( 'Continue', 'sekeco' ); ?></button>
				<button type="submit" class="sekeco-form__submit">
					<?php echo esc_html( $settings['submit'] ); ?>
				</button>
			</div>
		<?php else : ?>
			<button type="submit" class="sekeco-form__submit">
				<?php echo esc_html( $settings['submit'] ); ?>
			</button>
		<?php endif; ?>

		<p class="sekeco-form__status" role="status" aria-live="polite"></p>
	</form>
	<?php

	return ob_get_clean();
}

/**
 * Shortcode: [sekeco_form id="123"].
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function sekeco_form_shortcode( $atts ) {
	$atts = shortcode_atts( array( 'id' => 0 ), $atts, 'sekeco_form' );
	$html = sekeco_render_form( absint( $atts['id'] ) );

	return '' === $html ? '' : '<div class="sekeco-form-embed">' . $html . '</div>';
}
add_shortcode( 'sekeco_form', 'sekeco_form_shortcode' );
