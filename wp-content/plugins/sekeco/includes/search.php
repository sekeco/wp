<?php
/**
 * REST endpoints behind the full-screen search: live results and random picks.
 *
 * Both routes are public and read-only; they only ever return published
 * Works, Portfolio, Team, Journal and page entries.
 *
 * @package Sekeco
 */

defined( 'ABSPATH' ) || exit;

/**
 * Searchable post types and their labels.
 *
 * @return array<string,string>
 */
function sekeco_search_types() {
	return array(
		'portfolio' => __( 'Works', 'sekeco' ),
		'product'   => __( 'Portfolio', 'sekeco' ),
		'team'      => __( 'Team', 'sekeco' ),
		'post'      => __( 'Journal', 'sekeco' ),
		'page'      => __( 'Page', 'sekeco' ),
	);
}

/**
 * Shape one post for the search UI.
 *
 * @param WP_Post $post Post object.
 * @return array
 */
function sekeco_search_item( $post ) {
	$types = sekeco_search_types();
	$type  = $post->post_type;

	list( $name, $detail ) = sekeco_split_title( get_the_title( $post ) );

	if ( 'portfolio' === $type ) {
		$client = (string) get_post_meta( $post->ID, 'sekeco_portfolio_client', true );
		$name   = '' !== $client ? $client : $name;
	}

	if ( 'team' === $type ) {
		$detail = (string) get_post_meta( $post->ID, 'sekeco_team_role', true );
	} elseif ( '' === $detail ) {
		$source = has_excerpt( $post ) ? get_the_excerpt( $post ) : wp_strip_all_tags( strip_shortcodes( $post->post_content ) );
		$detail = wp_trim_words( $source, 14, '…' );
	}

	$thumb = '';

	if ( sekeco_thumbnail_file_exists( $post ) ) {
		$url   = get_the_post_thumbnail_url( $post, 'medium_large' );
		$thumb = $url ? $url : '';
	}

	return array(
		'id'     => (int) $post->ID,
		'type'   => $type,
		'label'  => isset( $types[ $type ] ) ? $types[ $type ] : '',
		'title'  => $name,
		'detail' => $detail,
		'url'    => get_permalink( $post ),
		'thumb'  => $thumb,
	);
}

/**
 * Live search.
 *
 * @param WP_REST_Request $request Request.
 * @return WP_REST_Response
 */
function sekeco_rest_search( $request ) {
	$query = new WP_Query(
		array(
			's'                   => $request->get_param( 'q' ),
			'post_type'           => array_keys( sekeco_search_types() ),
			'post_status'         => 'publish',
			'posts_per_page'      => 8,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);

	return rest_ensure_response(
		array(
			'results' => array_map( 'sekeco_search_item', $query->posts ),
		)
	);
}

/**
 * Random picks: a short recommended list and a larger set with images.
 *
 * @return WP_REST_Response
 */
function sekeco_rest_suggest() {
	$types = array( 'portfolio', 'product', 'post' );

	$pick = static function ( $count, $with_image ) use ( $types ) {
		$args = array(
			'post_type'           => $types,
			'post_status'         => 'publish',
			'posts_per_page'      => $count,
			'orderby'             => 'rand',
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		);

		if ( $with_image ) {
			$args['meta_key']     = '_thumbnail_id'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			$args['meta_compare'] = 'EXISTS';
		}

		return get_posts( $args );
	};

	// One tile per distinct image, so the masonry never repeats a picture.
	$tiles = array();

	foreach ( array_map( 'sekeco_search_item', $pick( 24, true ) ) as $item ) {
		if ( '' !== $item['thumb'] && ! isset( $tiles[ $item['thumb'] ] ) ) {
			$tiles[ $item['thumb'] ] = $item;
		}
	}

	$tiles = array_values( $tiles );

	return rest_ensure_response(
		array(
			'recommended' => array_map( 'sekeco_search_item', $pick( 6, false ) ),
			'tiles'       => array_slice( $tiles, 0, 12 ),
		)
	);
}

/**
 * Register the routes.
 *
 * @return void
 */
function sekeco_register_search_routes() {
	register_rest_route(
		'sekeco/v1',
		'/search',
		array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => 'sekeco_rest_search',
			'permission_callback' => '__return_true',
			'args'                => array(
				'q' => array(
					'type'              => 'string',
					'required'          => true,
					'sanitize_callback' => 'sanitize_text_field',
					'validate_callback' => static function ( $value ) {
						return is_string( $value ) && strlen( trim( $value ) ) >= 2 && strlen( $value ) <= 100;
					},
				),
			),
		)
	);

	register_rest_route(
		'sekeco/v1',
		'/suggest',
		array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => 'sekeco_rest_suggest',
			'permission_callback' => '__return_true',
		)
	);
}
add_action( 'rest_api_init', 'sekeco_register_search_routes' );
