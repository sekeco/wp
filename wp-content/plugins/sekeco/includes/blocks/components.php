<?php
/**
 * Component blocks and their shared render helpers.
 *
 * Every component is a dynamic block built to `build/blocks/<name>`; this file
 * registers them from one list and holds the helpers that more than one
 * render.php needs (project cards, queries, icons in markup).
 *
 * @package Sekeco
 */

defined( 'ABSPATH' ) || exit;

/**
 * Block directories (under build/blocks) registered by this module.
 *
 * @return string[]
 */
function sekeco_component_blocks() {
	return apply_filters(
		'sekeco_component_blocks',
		array(
			'section',
			'section-header',
			'hero',
			'statement',
			'marquee',
			'stats',
			'stat',
			'capabilities',
			'capability',
			'process',
			'process-step',
			'faq',
			'faq-item',
			'cta',
			'project-grid',
			'theme-toggle',
		)
	);
}

/**
 * Register the component blocks from their build metadata.
 *
 * @return void
 */
function sekeco_register_component_blocks() {
	foreach ( sekeco_component_blocks() as $name ) {
		$dir = SEKECO_PATH . 'build/blocks/' . $name;

		if ( file_exists( $dir . '/block.json' ) ) {
			register_block_type( $dir );
		}
	}
}
add_action( 'init', 'sekeco_register_component_blocks' );

/**
 * Post types the project grid can list.
 *
 * @return string[]
 */
function sekeco_project_post_types() {
	return array( 'portfolio', 'product', 'team' );
}

/**
 * Split "Client: Descriptor" titles into a name and a descriptor.
 *
 * @param string $title Post title.
 * @return string[] Two items: name, descriptor.
 */
function sekeco_split_title( $title ) {
	$title = wp_strip_all_tags( html_entity_decode( (string) $title, ENT_QUOTES, 'UTF-8' ) );
	$pos   = strpos( $title, ':' );

	if ( false === $pos || $pos < 2 ) {
		return array( trim( $title ), '' );
	}

	return array( trim( substr( $title, 0, $pos ) ), trim( substr( $title, $pos + 1 ) ) );
}

/**
 * Stable 1-4 tone index so a post keeps the same cover colour.
 *
 * @param int $post_id Post ID.
 * @return int
 */
function sekeco_card_tone( $post_id ) {
	return ( (int) $post_id % 4 ) + 1;
}

/**
 * Initials used as the cover mark of a person without a portrait.
 *
 * @param string $name Full name.
 * @return string
 */
function sekeco_initials( $name ) {
	$words    = preg_split( '/\s+/', trim( $name ) );
	$initials = '';

	foreach ( array_slice( (array) $words, 0, 2 ) as $word ) {
		$initials .= function_exists( 'mb_substr' ) ? mb_substr( $word, 0, 1 ) : substr( $word, 0, 1 );
	}

	return strtoupper( $initials );
}

/**
 * Query published entries for a project grid.
 *
 * @param string $post_type Post type slug.
 * @param int    $count     Number of entries.
 * @param int    $term_id   Optional category ID.
 * @return WP_Post[]
 */
function sekeco_query_projects( $post_type, $count, $term_id = 0 ) {
	if ( ! in_array( $post_type, sekeco_project_post_types(), true ) ) {
		return array();
	}

	$args = array(
		'post_type'           => $post_type,
		'post_status'         => 'publish',
		'posts_per_page'      => max( 1, min( 24, (int) $count ) ),
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	);

	if ( 'team' === $post_type ) {
		$args['orderby'] = array(
			'menu_order' => 'ASC',
			'date'       => 'ASC',
		);
	}

	if ( $term_id > 0 ) {
		$args['cat'] = (int) $term_id;
	}

	return get_posts( $args );
}

/**
 * Whether the featured image file of a post is still present.
 *
 * Only local uploads are checked; offloaded or remote media is assumed present.
 *
 * @param WP_Post $post Post object.
 * @return bool
 */
function sekeco_thumbnail_file_exists( $post ) {
	$id = get_post_thumbnail_id( $post );
	$url = $id ? wp_get_attachment_url( $id ) : '';

	if ( ! $url ) {
		return false;
	}

	$uploads = wp_get_upload_dir();

	if ( 0 !== strpos( $url, $uploads['baseurl'] ) ) {
		return true;
	}

	$file = get_attached_file( $id );

	return $file && file_exists( $file );
}

/**
 * Markup of one project, product, or team card.
 *
 * Entries without a featured image get a typographic cover: the name set large
 * on one of four palette fields, so a grid never shows empty boxes.
 *
 * @param WP_Post|int $post Post object or ID.
 * @param array       $args Optional: eager (bool), size (string).
 * @return string
 */
function sekeco_render_project_card( $post, $args = array() ) {
	$post = get_post( $post );

	if ( ! $post ) {
		return '';
	}

	$args = wp_parse_args(
		$args,
		array(
			'eager' => false,
			'size'  => 'large',
		)
	);

	$type                  = $post->post_type;
	list( $name, $detail ) = sekeco_split_title( get_the_title( $post ) );
	// Product images are repository previews, so products always use the typographic cover.
	$thumb_html            = 'product' !== $type
		? get_the_post_thumbnail(
			$post,
			$args['size'],
			array(
				'class'    => 'sk-card__img',
				'alt'      => '',
				'loading'  => $args['eager'] ? 'eager' : 'lazy',
				'decoding' => 'async',
			)
		)
		: '';
	// A thumbnail whose file is gone from local uploads is not an image: fall back to the cover.
	$has_image = '' !== $thumb_html && sekeco_thumbnail_file_exists( $post );
	$tone                  = sekeco_card_tone( $post->ID );

	$chips = array();
	$meta  = '';

	if ( 'team' === $type ) {
		$meta = (string) get_post_meta( $post->ID, 'sekeco_team_role', true );
	} else {
		foreach ( array_slice( (array) get_the_category( $post->ID ), 0, 2 ) as $term ) {
			if ( 'uncategorized' !== $term->slug ) {
				$chips[] = $term->name;
			}
		}

		$year_key = 'portfolio' === $type ? 'sekeco_portfolio_year' : 'sekeco_product_year';
		$meta     = (string) get_post_meta( $post->ID, $year_key, true );
	}

	if ( 'portfolio' === $type ) {
		$client = (string) get_post_meta( $post->ID, 'sekeco_portfolio_client', true );
		$name   = '' !== $client ? $client : $name;
	}

	if ( '' === $detail && has_excerpt( $post ) ) {
		$detail = wp_trim_words( get_the_excerpt( $post ), 12, '…' );
	}

	$classes = array(
		'sk-card',
		'sk-card--' . $type,
		'sk-card--tone-' . $tone,
		$has_image ? 'sk-card--has-image' : 'sk-card--typographic',
	);

	ob_start();
	?>
	<article class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
		<a class="sk-card__link" href="<?php echo esc_url( get_permalink( $post ) ); ?>">
			<span class="sk-card__media"<?php echo 'portfolio' === $type ? ' data-sk-parallax="0.1"' : ''; ?>>
				<?php if ( $has_image ) : ?>
					<?php echo $thumb_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php elseif ( 'team' === $type ) : ?>
					<span class="sk-card__mark" aria-hidden="true"><?php echo esc_html( sekeco_initials( $name ) ); ?></span>
				<?php endif; ?>
			</span>
			<span class="sk-card__body">
				<?php if ( $chips ) : ?>
					<span class="sk-card__tags"><?php echo esc_html( implode( ', ', $chips ) ); ?></span>
				<?php endif; ?>
				<span class="sk-card__title"><?php echo esc_html( $name ); ?></span>
				<?php if ( '' !== $detail || '' !== $meta ) : ?>
					<span class="sk-card__detail">
						<?php echo esc_html( 'team' === $type ? $meta : trim( $detail ) ); ?>
					</span>
				<?php endif; ?>
			</span>
			<span class="sk-card__go">
				<?php echo sekeco_icon( 'arrow-right-up', array( 'size' => 20 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<span class="screen-reader-text"><?php echo esc_html( sprintf( /* translators: %s: project name */ __( 'View %s', 'sekeco' ), $name ) ); ?></span>
			</span>
		</a>
	</article>
	<?php
	return (string) ob_get_clean();
}

/**
 * Restrict rich text attributes to inline formatting.
 *
 * @param string $html Rich text value.
 * @return string
 */
function sekeco_kses_inline( $html ) {
	return wp_kses(
		(string) $html,
		array(
			'br'     => array(),
			'em'     => array(),
			'i'      => array(),
			'strong' => array(),
			'b'      => array(),
			'span'   => array( 'class' => array() ),
			'a'      => array(
				'href'   => array(),
				'rel'    => array(),
				'target' => array(),
			),
		)
	);
}
