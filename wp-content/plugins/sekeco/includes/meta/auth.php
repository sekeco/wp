<?php
/**
 * Shared post meta authorization.
 *
 * @package Sekeco
 */

defined( 'ABSPATH' ) || exit;

/**
 * Only allow meta writes for users who can edit the post.
 *
 * @param bool   $allowed  Whether the meta value is allowed.
 * @param string $meta_key Meta key.
 * @param int    $post_id  Post ID.
 * @return bool
 */
function sekeco_meta_auth_callback( $allowed, $meta_key, $post_id ) {
	if ( $post_id ) {
		return current_user_can( 'edit_post', $post_id );
	}

	return $allowed;
}
