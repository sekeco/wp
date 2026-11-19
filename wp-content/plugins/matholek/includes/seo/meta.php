<?php
/**
 * Meta description and Open Graph / Twitter card tags.
 *
 * Core already prints the canonical link, robots directives and the document
 * title; this module adds what it lacks so shared links render a proper card.
 *
 * @package Matholek
 */

defined( 'ABSPATH' ) || exit;

/**
 * Canonical-style URL of the current request, without the pagination suffix.
 *
 * @return string
 */
function matholek_seo_current_url() {
	if ( is_singular() ) {
		return (string) get_permalink( get_queried_object_id() );
	}

	if ( is_front_page() ) {
		return home_url( '/' );
	}

	if ( is_home() ) {
		$page = (int) get_option( 'page_for_posts' );

		return $page ? (string) get_permalink( $page ) : home_url( '/' );
	}

	if ( is_post_type_archive() ) {
		$link = get_post_type_archive_link( get_query_var( 'post_type' ) );

		return $link ? $link : home_url( '/' );
	}

	if ( is_category() || is_tag() || is_tax() ) {
		$link = get_term_link( get_queried_object() );

		return is_wp_error( $link ) ? home_url( '/' ) : $link;
	}

	if ( is_author() ) {
		return get_author_posts_url( (int) get_queried_object_id() );
	}

	global $wp;

	return trailingslashit( home_url( $wp->request ) );
}

/**
 * Short plain-text description for a post.
 *
 * @param WP_Post $post Post object.
 * @return string Empty string when nothing usable exists.
 */
function matholek_seo_post_description( $post ) {
	if ( post_password_required( $post ) ) {
		return '';
	}

	$text = has_excerpt( $post )
		? $post->post_excerpt
		: wp_strip_all_tags( strip_shortcodes( excerpt_remove_blocks( $post->post_content ) ) );
	$text = trim( preg_replace( '/\s+/', ' ', html_entity_decode( (string) $text, ENT_QUOTES, 'UTF-8' ) ) );

	return wp_trim_words( $text, 30, '…' );
}

/**
 * Share image for the current request.
 *
 * @return array{url:string,width:int,height:int,alt:string}|null
 */
function matholek_seo_image() {
	$id = 0;

	if ( is_singular() ) {
		$post = get_queried_object();

		if ( $post instanceof WP_Post && has_post_thumbnail( $post ) ) {
			$id = (int) get_post_thumbnail_id( $post );
		}
	}

	if ( ! $id ) {
		$options = matholek_seo_options();
		$id      = (int) $options['image_id'];
	}

	if ( $id ) {
		$src = wp_get_attachment_image_src( $id, 'full' );

		if ( $src ) {
			return array(
				'url'    => $src[0],
				'width'  => (int) $src[1],
				'height' => (int) $src[2],
				'alt'    => trim( (string) get_post_meta( $id, '_wp_attachment_image_alt', true ) ),
			);
		}
	}

	$icon = get_site_icon_url( 512 );

	return $icon ? array(
		'url'    => $icon,
		'width'  => 512,
		'height' => 512,
		'alt'    => '',
	) : null;
}

/**
 * Collect title, description, URL and image for the current request.
 *
 * Shared with the structured-data module. Filter `matholek_seo_context` to
 * override any value for a specific request.
 *
 * @return array
 */
function matholek_seo_context() {
	static $cache = null;

	if ( null !== $cache ) {
		return $cache;
	}

	$site    = wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES );
	$options = matholek_seo_options();
	$default = '' !== $options['description'] ? $options['description'] : wp_strip_all_tags( get_bloginfo( 'description' ) );
	$object  = get_queried_object();
	$type    = 'website';
	$title   = $site;
	$desc    = '';

	if ( is_singular() && $object instanceof WP_Post ) {
		$title = wp_strip_all_tags( get_the_title( $object ) );
		$desc  = matholek_seo_post_description( $object );
		$type  = 'post' === $object->post_type ? 'article' : 'website';
	} elseif ( is_post_type_archive() ) {
		$title = post_type_archive_title( '', false );
		$pto   = get_post_type_object( get_query_var( 'post_type' ) );
		$desc  = $pto && ! empty( $pto->description ) ? $pto->description : '';
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$title = single_term_title( '', false );
		$desc  = wp_strip_all_tags( term_description() );
	} elseif ( is_search() ) {
		$title = wp_get_document_title();
	} elseif ( ! is_front_page() ) {
		$title = wp_get_document_title();
	}

	$context = array(
		'site'        => $site,
		'title'       => $title,
		'description' => trim( preg_replace( '/\s+/', ' ', '' !== $desc ? $desc : $default ) ),
		'url'         => matholek_seo_current_url(),
		'type'        => $type,
		'image'       => matholek_seo_image(),
		'locale'      => get_locale(),
		'object'      => $object,
	);

	/**
	 * Filter the SEO context for the current request.
	 *
	 * @param array $context Title, description, url, type, image, locale, object.
	 */
	$cache = apply_filters( 'matholek_seo_context', $context );

	return $cache;
}

/**
 * Print the description, Open Graph and Twitter card tags.
 *
 * @return void
 */
function matholek_seo_print_tags() {
	if ( is_404() || is_feed() || is_embed() || ! get_option( 'blog_public' ) ) {
		return;
	}

	$c = matholek_seo_context();

	$tags = array(
		array( 'name', 'description', $c['description'] ),
		array( 'property', 'og:locale', $c['locale'] ),
		array( 'property', 'og:type', $c['type'] ),
		array( 'property', 'og:title', $c['title'] ),
		array( 'property', 'og:description', $c['description'] ),
		array( 'property', 'og:url', $c['url'] ),
		array( 'property', 'og:site_name', $c['site'] ),
	);

	if ( ! empty( $c['image'] ) ) {
		$image  = $c['image'];
		$tags[] = array( 'property', 'og:image', $image['url'] );
		$tags[] = array( 'property', 'og:image:width', $image['width'] ? (string) $image['width'] : '' );
		$tags[] = array( 'property', 'og:image:height', $image['height'] ? (string) $image['height'] : '' );
		$tags[] = array( 'property', 'og:image:alt', $image['alt'] );
	}

	if ( 'article' === $c['type'] && $c['object'] instanceof WP_Post ) {
		$tags[] = array( 'property', 'article:published_time', get_post_time( 'c', true, $c['object'] ) );
		$tags[] = array( 'property', 'article:modified_time', get_post_modified_time( 'c', true, $c['object'] ) );
	}

	$options = matholek_seo_options();

	$tags[] = array( 'name', 'twitter:card', ! empty( $c['image'] ) && $c['image']['width'] >= 600 ? 'summary_large_image' : 'summary' );
	$tags[] = array( 'name', 'twitter:title', $c['title'] );
	$tags[] = array( 'name', 'twitter:description', $c['description'] );
	$tags[] = array( 'name', 'twitter:site', '' !== $options['twitter'] ? '@' . $options['twitter'] : '' );

	if ( ! empty( $c['image'] ) ) {
		$tags[] = array( 'name', 'twitter:image', $c['image']['url'] );
		$tags[] = array( 'name', 'twitter:image:alt', $c['image']['alt'] );
	}

	foreach ( $tags as $tag ) {
		list( $attr, $key, $value ) = $tag;

		if ( '' === (string) $value ) {
			continue;
		}

		$value = in_array( $key, array( 'og:url', 'og:image', 'twitter:image' ), true ) ? esc_url( $value ) : esc_attr( $value );

		printf( "<meta %s=\"%s\" content=\"%s\" />\n", esc_attr( $attr ), esc_attr( $key ), $value ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped above.
	}
}
add_action( 'wp_head', 'matholek_seo_print_tags', 5 );
