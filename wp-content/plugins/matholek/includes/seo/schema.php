<?php
/**
 * JSON-LD structured data.
 *
 * One @graph per page: the school (EducationalOrganization) and the WebSite
 * everywhere, plus an Article on a single news post.
 *
 * @package Matholek
 */

defined( 'ABSPATH' ) || exit;

/**
 * Keep only non-empty values.
 *
 * @param array $data Node properties.
 * @return array
 */
function matholek_schema_filter( $data ) {
	return array_filter(
		$data,
		static function ( $value ) {
			return ! ( '' === $value || null === $value || array() === $value );
		}
	);
}

/**
 * Build the structured-data graph for the current request.
 *
 * @return array Graph nodes; empty when nothing should be printed.
 */
function matholek_schema_graph() {
	$ctx    = matholek_seo_context();
	$org_id = home_url( '/#organization' );
	$logo   = get_site_icon_url( 512 );
	$graph  = array();

	$graph[] = matholek_schema_filter(
		array(
			'@type'        => 'EducationalOrganization',
			'@id'          => $org_id,
			'name'         => $ctx['site'],
			'url'          => home_url( '/' ),
			'description'  => wp_strip_all_tags( get_bloginfo( 'description' ) ),
			'foundingDate' => '1912',
			'address'      => array(
				'@type'           => 'PostalAddress',
				'addressLocality' => 'Kajen, Margoyoso',
				'addressRegion'   => 'Pati, Jawa Tengah',
				'addressCountry'  => 'ID',
			),
			'logo'         => $logo ? array(
				'@type' => 'ImageObject',
				'url'   => $logo,
			) : '',
		)
	);

	$graph[] = array(
		'@type'      => 'WebSite',
		'@id'        => home_url( '/#website' ),
		'url'        => home_url( '/' ),
		'name'       => $ctx['site'],
		'inLanguage' => get_bloginfo( 'language' ),
		'publisher'  => array( '@id' => $org_id ),
	);

	$post = is_singular( 'post' ) ? get_queried_object() : null;

	if ( $post instanceof WP_Post && ! post_password_required( $post ) ) {
		$image   = has_post_thumbnail( $post ) ? get_the_post_thumbnail_url( $post, 'full' ) : '';
		$graph[] = matholek_schema_filter(
			array(
				'@type'         => 'Article',
				'@id'           => $ctx['url'] . '#article',
				'url'           => $ctx['url'],
				'headline'      => $ctx['title'],
				'description'   => $ctx['description'],
				'image'         => $image ? $image : '',
				'datePublished' => get_post_time( 'c', true, $post ),
				'dateModified'  => get_post_modified_time( 'c', true, $post ),
				'inLanguage'    => get_bloginfo( 'language' ),
				'author'        => array( '@id' => $org_id ),
				'publisher'     => array( '@id' => $org_id ),
			)
		);
	}

	/**
	 * Filter the JSON-LD graph nodes.
	 *
	 * @param array $graph Nodes.
	 * @param array $ctx   SEO context.
	 */
	return apply_filters( 'matholek_schema_graph', $graph, $ctx );
}

/**
 * Print the JSON-LD script in the head.
 *
 * @return void
 */
function matholek_schema_print() {
	if ( is_404() || is_feed() || is_embed() || is_search() || ! get_option( 'blog_public' ) ) {
		return;
	}

	$graph = matholek_schema_graph();

	if ( ! $graph ) {
		return;
	}

	$json = wp_json_encode(
		array(
			'@context' => 'https://schema.org',
			'@graph'   => $graph,
		),
		JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP
	);

	echo '<script type="application/ld+json">' . $json . "</script>\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON-encoded with HEX_TAG.
}
add_action( 'wp_head', 'matholek_schema_print', 6 );
