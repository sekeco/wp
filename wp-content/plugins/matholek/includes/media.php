<?php
/**
 * Media: modern formats for generated image sizes.
 *
 * @package Matholek
 */

defined( 'ABSPATH' ) || exit;

/**
 * Generate the resized copies of new uploads as AVIF (photos) or WebP.
 *
 * Only the sub-sizes WordPress creates on upload change; the original file is
 * kept as uploaded. Images already in the library keep their current sizes
 * until they are regenerated. Filter `matholek_image_output_formats` to change
 * or disable the mapping (return an empty array).
 *
 * @param array $formats Source mime type => output mime type.
 * @return array
 */
function matholek_image_output_formats( $formats ) {
	$map = array(
		'image/jpeg' => wp_image_editor_supports( array( 'mime_type' => 'image/avif' ) ) ? 'image/avif' : 'image/webp',
		'image/png'  => 'image/webp',
	);

	foreach ( $map as $from => $to ) {
		if ( ! wp_image_editor_supports( array( 'mime_type' => $to ) ) ) {
			unset( $map[ $from ] );
		}
	}

	/**
	 * Filter the source to output mime type map for generated image sizes.
	 *
	 * @param array $map Source mime type => output mime type.
	 */
	return array_merge( $formats, apply_filters( 'matholek_image_output_formats', $map ) );
}
add_filter( 'image_editor_output_format', 'matholek_image_output_formats' );
