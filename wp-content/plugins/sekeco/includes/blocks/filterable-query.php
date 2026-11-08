<?php
/**
 * Filterable Query block registration and term-link metadata.
 *
 * @package Sekeco
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the Filterable Query block from its build metadata.
 *
 * @return void
 */
function sekeco_register_filterable_query_block() {
	$block_dir = SEKECO_PATH . 'build/blocks/filterable-query';

	if ( file_exists( $block_dir . '/block.json' ) ) {
		register_block_type( $block_dir );
	}
}
add_action( 'init', 'sekeco_register_filterable_query_block' );

/**
 * Add a stable term slug attribute to post term links.
 *
 * The filter runtime reads these attributes from each Query Loop item, so it
 * does not have to guess term slugs from arbitrary URLs.
 *
 * @param string $block_content Rendered block HTML.
 * @param array  $block         Parsed block.
 * @return string
 */
function sekeco_filterable_query_term_links( $block_content, $block ) {
	if (
		empty( $block['blockName'] ) ||
		'core/post-terms' !== $block['blockName']
	) {
		return $block_content;
	}

	$processor = new WP_HTML_Tag_Processor( $block_content );

	while ( $processor->next_tag( 'a' ) ) {
		$href = $processor->get_attribute( 'href' );

		if ( ! $href ) {
			continue;
		}

		$path = wp_parse_url( $href, PHP_URL_PATH );

		if ( ! $path ) {
			continue;
		}

		$slug = basename( untrailingslashit( $path ) );

		if ( $slug ) {
			$processor->set_attribute(
				'data-sekeco-term-slug',
				sanitize_title( rawurldecode( $slug ) )
			);
		}
	}

	return $processor->get_updated_html();
}
add_filter( 'render_block', 'sekeco_filterable_query_term_links', 10, 2 );
