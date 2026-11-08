<?php
/**
 * Form submission handling: REST endpoint, no-JS fallback, storage, and email.
 *
 * @package Sekeco
 */

defined( 'ABSPATH' ) || exit;

/**
 * Validate and store a submission.
 *
 * @param int   $form_id Form post ID.
 * @param array $data    Raw request data (already unslashed by the caller).
 * @return array
 */
function sekeco_process_form( $form_id, $data ) {
	$form_id = absint( $form_id );
	$form    = get_post( $form_id );

	if ( ! $form || 'sekeco_form' !== $form->post_type || 'publish' !== $form->post_status ) {
		return array(
			'ok'      => false,
			'status'  => 404,
			'message' => __( 'This form is not available.', 'sekeco' ),
			'errors'  => array(),
		);
	}

	$nonce = isset( $data['sekeco_form_nonce'] ) ? (string) $data['sekeco_form_nonce'] : '';

	if ( ! wp_verify_nonce( $nonce, 'sekeco_form_' . $form_id ) ) {
		return array(
			'ok'      => false,
			'status'  => 403,
			'message' => __( 'Your session expired. Please reload the page and try again.', 'sekeco' ),
			'errors'  => array(),
		);
	}

	// Honeypot: pretend success so bots do not learn anything.
	if ( ! empty( $data['sekeco_website'] ) ) {
		return array(
			'ok'      => true,
			'status'  => 200,
			'message' => sekeco_get_form_settings( $form_id )['success'],
			'errors'  => array(),
		);
	}

	$submitted_at = isset( $data['sekeco_form_time'] ) ? absint( $data['sekeco_form_time'] ) : 0;
	$age          = time() - $submitted_at;

	if ( $age < 2 || $age > 12 * HOUR_IN_SECONDS ) {
		return array(
			'ok'      => true,
			'status'  => 200,
			'message' => sekeco_get_form_settings( $form_id )['success'],
			'errors'  => array(),
		);
	}

	$ip = isset( $_SERVER['REMOTE_ADDR'] )
		? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) )
		: '';

	$rate_key = 'sekeco_form_rl_' . $form_id . '_' . md5( $ip );
	$attempts = (int) get_transient( $rate_key );

	if ( $attempts >= 5 ) {
		return array(
			'ok'      => false,
			'status'  => 429,
			'message' => __( 'Too many submissions. Please try again later.', 'sekeco' ),
			'errors'  => array(),
		);
	}

	$fields = sekeco_get_form_fields( $form_id );
	$errors = array();
	$values = array();

	foreach ( $fields as $index => $field ) {
		$key   = 'f' . $index;
		$raw   = isset( $data[ $key ] ) ? $data[ $key ] : '';
		$value = is_array( $raw )
			? implode( ', ', array_map( 'sanitize_text_field', $raw ) )
			: sanitize_textarea_field( (string) $raw );

		$value = trim( $value );

		if ( strlen( $value ) > 5000 ) {
			$value = substr( $value, 0, 5000 );
		}

		if ( ! empty( $field['required'] ) && '' === $value ) {
			$errors[ $key ] = __( 'This field is required.', 'sekeco' );
			continue;
		}

		if ( '' === $value ) {
			$values[ $field['label'] ] = '';
			continue;
		}

		switch ( $field['type'] ) {
			case 'email':
				if ( ! is_email( $value ) ) {
					$errors[ $key ] = __( 'Enter a valid email address.', 'sekeco' );
				}
				break;

			case 'url':
				if ( ! esc_url_raw( $value ) ) {
					$errors[ $key ] = __( 'Enter a valid URL.', 'sekeco' );
				}
				break;

			case 'number':
				if ( ! is_numeric( $value ) ) {
					$errors[ $key ] = __( 'Enter a number.', 'sekeco' );
				}
				break;

			case 'tel':
				if ( ! preg_match( '/^[0-9+\-().\s]{5,25}$/', $value ) ) {
					$errors[ $key ] = __( 'Enter a valid phone number.', 'sekeco' );
				}
				break;

			case 'select':
			case 'radio':
				$options = sekeco_form_options_list( (string) $field['options'] );

				if ( $options && ! in_array( $value, $options, true ) ) {
					$errors[ $key ] = __( 'Choose one of the listed options.', 'sekeco' );
				}
				break;

			case 'checkbox_group':
				$options  = sekeco_form_options_list( (string) $field['options'] );
				$selected = array_filter( array_map( 'trim', explode( ',', $value ) ) );

				if ( $options && array_diff( $selected, $options ) ) {
					$errors[ $key ] = __( 'Choose from the listed options.', 'sekeco' );
				}
				break;
		}

		$values[ $field['label'] ] = $value;
	}

	if ( $errors ) {
		return array(
			'ok'      => false,
			'status'  => 422,
			'message' => __( 'Please review the highlighted fields.', 'sekeco' ),
			'errors'  => $errors,
		);
	}

	set_transient( $rate_key, $attempts + 1, 10 * MINUTE_IN_SECONDS );

	$submission_id = wp_insert_post(
		array(
			'post_type'   => 'sekeco_submission',
			'post_status' => 'publish',
			'post_parent' => $form_id,
			'post_title'  => sprintf(
				/* translators: 1: form title, 2: date. */
				__( '%1$s — %2$s', 'sekeco' ),
				$form->post_title,
				date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) )
			),
		),
		true
	);

	if ( is_wp_error( $submission_id ) ) {
		return array(
			'ok'      => false,
			'status'  => 500,
			'message' => __( 'The submission could not be saved. Please try again.', 'sekeco' ),
			'errors'  => array(),
		);
	}

	update_post_meta( $submission_id, '_sekeco_submission_data', $values );
	update_post_meta(
		$submission_id,
		'_sekeco_submission_meta',
		array(
			'ip'    => $ip,
			'agent' => isset( $_SERVER['HTTP_USER_AGENT'] )
				? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) )
				: '',
			'url'   => isset( $data['sekeco_form_redirect'] )
				? esc_url_raw( (string) $data['sekeco_form_redirect'] )
				: '',
		)
	);

	$settings = sekeco_get_form_settings( $form_id );

	$lines = array();

	foreach ( $values as $label => $value ) {
		$lines[] = $label . ': ' . ( '' !== $value ? $value : '—' );
	}

	$lines[] = '';
	$lines[] = sprintf(
		/* translators: %s: submission admin URL. */
		__( 'View in admin: %s', 'sekeco' ),
		admin_url( 'post.php?post=' . $submission_id . '&action=edit' )
	);

	$reply_to = '';

	foreach ( $fields as $field ) {
		if ( 'email' === $field['type'] && ! empty( $values[ $field['label'] ] ) ) {
			$reply_to = $values[ $field['label'] ];
			break;
		}
	}

	$headers = array();

	if ( $reply_to && is_email( $reply_to ) ) {
		$headers[] = 'Reply-To: ' . $reply_to;
	}

	wp_mail(
		$settings['notify'],
		sprintf(
			/* translators: %s: form title. */
			__( 'New submission: %s', 'sekeco' ),
			$form->post_title
		),
		implode( "\n", $lines ),
		$headers
	);

	return array(
		'ok'      => true,
		'status'  => 200,
		'message' => $settings['success'],
		'errors'  => array(),
	);
}

/**
 * REST handler.
 *
 * @param WP_REST_Request $request Request.
 * @return WP_REST_Response|WP_Error
 */
function sekeco_rest_form_submit( $request ) {
	$form_id = absint( $request['id'] );
	$data    = $request->get_params();
	$result  = sekeco_process_form( $form_id, $data );

	if ( ! $result['ok'] ) {
		return new WP_Error(
			'sekeco_form_error',
			$result['message'],
			array(
				'status' => $result['status'],
				'errors' => $result['errors'],
			)
		);
	}

	return rest_ensure_response(
		array(
			'ok'      => true,
			'message' => $result['message'],
		)
	);
}

/**
 * Register the submission route.
 *
 * @return void
 */
function sekeco_register_form_routes() {
	register_rest_route(
		'sekeco/v1',
		'/forms/(?P<id>\d+)/submit',
		array(
			'methods'             => 'POST',
			'callback'            => 'sekeco_rest_form_submit',
			'permission_callback' => '__return_true',
			'args'                => array(
				'id' => array(
					'required'          => true,
					'validate_callback' => static function ( $value ) {
						return is_numeric( $value );
					},
				),
			),
		)
	);
}
add_action( 'rest_api_init', 'sekeco_register_form_routes' );

/**
 * No-JS fallback: accepts a normal POST and redirects back.
 *
 * @return void
 */
function sekeco_handle_form_post() {
	$form_id = isset( $_POST['sekeco_form_id'] ) ? absint( $_POST['sekeco_form_id'] ) : 0;
	$data    = wp_unslash( $_POST );

	$result = sekeco_process_form( $form_id, $data );
	$settings = $form_id ? sekeco_get_form_settings( $form_id ) : array( 'redirect' => '' );

	if ( $result['ok'] && ! empty( $settings['redirect'] ) ) {
		wp_redirect( esc_url_raw( $settings['redirect'] ) ); // phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect
		exit;
	}

	$redirect = isset( $data['sekeco_form_redirect'] ) && $data['sekeco_form_redirect']
		? esc_url_raw( $data['sekeco_form_redirect'] )
		: home_url( '/' );

	$redirect = add_query_arg(
		array(
			'sekeco_form_status' => $result['ok'] ? 'ok' : 'error',
			'sekeco_form_id'     => $form_id,
		),
		$redirect
	);

	wp_safe_redirect( $redirect . '#sekeco-form-' . $form_id );
	exit;
}
add_action( 'admin_post_nopriv_sekeco_form_submit', 'sekeco_handle_form_post' );
add_action( 'admin_post_sekeco_form_submit', 'sekeco_handle_form_post' );
