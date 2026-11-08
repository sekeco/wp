<?php
/**
 * Server render for the Sekeco Slider block.
 *
 * @package Sekeco
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Serialized inner blocks.
 * @var WP_Block $block      Block instance.
 */

defined( 'ABSPATH' ) || exit;

$mode = isset( $attributes['mode'] ) && 'query' === $attributes['mode'] ? 'query' : 'inner';

$per_view = isset( $attributes['perView'] ) ? (int) $attributes['perView'] : 3;
$per_view = max( 1, min( 4, $per_view ) );

$gap = isset( $attributes['gap'] ) ? (int) $attributes['gap'] : 24;
$gap = max( 0, min( 80, $gap ) );

$show_arrows = ! empty( $attributes['showArrows'] );

$post_type = isset( $attributes['postType'] )
	? sanitize_key( $attributes['postType'] )
	: 'portfolio';
$post_type = in_array( $post_type, array( 'post', 'portfolio', 'product' ), true )
	? $post_type
	: 'portfolio';

$per_page = isset( $attributes['perPage'] ) ? (int) $attributes['perPage'] : 6;
$per_page = max( 1, min( 12, $per_page ) );

$label = isset( $attributes['label'] ) && '' !== $attributes['label']
	? $attributes['label']
	: __( 'Slider', 'sekeco' );

$classes = array(
	'sekeco-slider',
	'sekeco-slider--mode-' . $mode,
);

$styles = array(
	'--sekeco-slider-per-view:' . $per_view,
	'--sekeco-slider-gap:' . $gap . 'px',
);

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class'              => implode( ' ', $classes ),
		'style'              => implode( ';', $styles ),
		'data-sekeco-slider' => '',
	)
);

$slides_html = '';
$has_slides  = false;

if ( 'inner' === $mode ) {
	$slides_html = trim( $content );
	$has_slides  = '' !== $slides_html;

	if ( ! $has_slides ) {
		$slides_html = '<p class="sekeco-slider__empty">'
			. esc_html__( 'Add blocks to create slides.', 'sekeco' )
			. '</p>';
	}
} else {
	$query = new WP_Query(
		array(
			'post_type'           => $post_type,
			'posts_per_page'      => $per_page,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);

	if ( $query->have_posts() ) {
		$has_slides = true;
		ob_start();

		while ( $query->have_posts() ) {
			$query->the_post();
			$post = get_post();

			if ( ! $post ) {
				continue;
			}

			$slide_class = 'sekeco-slider__slide sekeco-slider__slide--' . $post_type;

			echo '<article class="' . esc_attr( $slide_class ) . '">';

			if ( has_post_thumbnail( $post ) ) {
				echo get_the_post_thumbnail(
					$post,
					'medium_large',
					array(
						'class'   => 'sekeco-slider__image',
						'loading' => 'lazy',
					)
				);
			}

			echo '<div class="sekeco-slider__body">';
			echo '<h3 class="wp-block-heading sekeco-slider__title">';
			echo '<a href="' . esc_url( get_permalink( $post ) ) . '">';
			echo esc_html( get_the_title( $post ) );
			echo '</a></h3>';

			$excerpt = get_the_excerpt( $post );

			if ( $excerpt ) {
				echo '<p class="sekeco-slider__excerpt">'
					. wp_kses_post( $excerpt )
					. '</p>';
			}

			if ( 'product' === $post_type ) {
				$meta = array_filter(
					array(
						get_post_meta( $post->ID, 'sekeco_product_status', true ),
						get_post_meta( $post->ID, 'sekeco_product_stack', true ),
					)
				);

				if ( $meta ) {
					echo '<p class="sekeco-slider__meta">'
						. esc_html( implode( ' · ', $meta ) )
						. '</p>';
				}
			} elseif ( 'portfolio' === $post_type ) {
				$meta = array_filter(
					array(
						get_post_meta( $post->ID, 'sekeco_portfolio_client', true ),
						get_post_meta( $post->ID, 'sekeco_portfolio_year', true ),
					)
				);

				if ( $meta ) {
					echo '<p class="sekeco-slider__meta">'
						. esc_html( implode( ' · ', $meta ) )
						. '</p>';
				}
			}

			echo '</div></article>';
		}

		$slides_html = ob_get_clean();
		wp_reset_postdata();
	} else {
		$slides_html = '<p class="sekeco-slider__empty">'
			. esc_html__( 'No slides found.', 'sekeco' )
			. '</p>';
	}
}
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div
		class="sekeco-slider__viewport"
		tabindex="0"
		role="region"
		aria-roledescription="<?php echo esc_attr__( 'carousel', 'sekeco' ); ?>"
		aria-label="<?php echo esc_attr( $label ); ?>"
	>
		<div class="sekeco-slider__track">
			<?php echo $slides_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	</div>

	<?php if ( $show_arrows && $has_slides ) : ?>
		<div class="sekeco-slider__controls">
			<button
				type="button"
				class="sekeco-slider__button"
				data-sekeco-slider-prev
				aria-label="<?php echo esc_attr__( 'Previous slide', 'sekeco' ); ?>"
				disabled
			>
				<svg width="24" height="24" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
					<path d="M15.5 4.5 8 12l7.5 7.5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
				</svg>
			</button>
			<button
				type="button"
				class="sekeco-slider__button"
				data-sekeco-slider-next
				aria-label="<?php echo esc_attr__( 'Next slide', 'sekeco' ); ?>"
			>
				<svg width="24" height="24" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
					<path d="M8.5 4.5 16 12l-7.5 7.5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
				</svg>
			</button>
		</div>
	<?php endif; ?>
</div>
