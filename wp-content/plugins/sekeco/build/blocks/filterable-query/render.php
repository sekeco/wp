<?php
/**
 * Server render for the Sekeco Filterable Query block.
 *
 * @package Sekeco
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Serialized inner blocks.
 * @var WP_Block $block      Block instance.
 */

defined( 'ABSPATH' ) || exit;

$taxonomy = isset( $attributes['taxonomy'] )
	? sanitize_key( $attributes['taxonomy'] )
	: 'category';

$show_all = ! isset( $attributes['showAll'] ) || ! empty( $attributes['showAll'] );

$all_label = isset( $attributes['allLabel'] ) && '' !== $attributes['allLabel']
	? $attributes['allLabel']
	: __( 'All', 'sekeco' );

$filter_label = isset( $attributes['filterLabel'] ) && '' !== $attributes['filterLabel']
	? $attributes['filterLabel']
	: __( 'Filter by category', 'sekeco' );

$terms = array();

if ( taxonomy_exists( $taxonomy ) ) {
	// Limit the filter to terms that actually have items in the queried post type.
	$post_type = '';

	if ( ! empty( $block->parsed_block['innerBlocks'] ) ) {
		foreach ( $block->parsed_block['innerBlocks'] as $inner_block ) {
			if ( isset( $inner_block['blockName'] ) && 'core/query' === $inner_block['blockName'] ) {
				$post_type = isset( $inner_block['attrs']['query']['postType'] )
					? sanitize_key( $inner_block['attrs']['query']['postType'] )
					: '';
				break;
			}
		}
	}

	$include = array();

	if ( $post_type ) {
		global $wpdb;

		$include = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT DISTINCT tt.term_id
				FROM {$wpdb->term_relationships} AS tr
				INNER JOIN {$wpdb->term_taxonomy} AS tt
					ON tr.term_taxonomy_id = tt.term_taxonomy_id
				INNER JOIN {$wpdb->posts} AS p ON tr.object_id = p.ID
				WHERE tt.taxonomy = %s
					AND p.post_type = %s
					AND p.post_status = 'publish'",
				$taxonomy,
				$post_type
			)
		);

		$include = array_map( 'intval', (array) $include );
	}

	if ( ! $post_type || $include ) {
		$terms = get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => true,
				'include'    => $include,
			)
		);
	}

	if ( is_wp_error( $terms ) ) {
		$terms = array();
	}
}

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class'                  => 'sekeco-filterable-query',
		'data-sekeco-filterable' => '',
		'data-taxonomy'          => $taxonomy,
	)
);

$first_term = true;
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php if ( ! empty( $terms ) ) : ?>
		<div
			class="sekeco-filterable-query__filters"
			role="group"
			aria-label="<?php echo esc_attr( $filter_label ); ?>"
		>
			<?php if ( $show_all ) : ?>
				<button
					type="button"
					class="sekeco-filterable-query__filter is-active"
					data-sekeco-filter-term="all"
					aria-pressed="true"
				>
					<?php echo esc_html( $all_label ); ?>
				</button>
			<?php endif; ?>

			<?php foreach ( $terms as $term ) : ?>
				<?php
				$is_initial = ! $show_all && $first_term;
				$first_term = false;
				?>
				<button
					type="button"
					class="sekeco-filterable-query__filter<?php echo $is_initial ? ' is-active' : ''; ?>"
					data-sekeco-filter-term="<?php echo esc_attr( $term->slug ); ?>"
					aria-pressed="<?php echo $is_initial ? 'true' : 'false'; ?>"
				>
					<?php echo esc_html( $term->name ); ?>
				</button>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<div class="sekeco-filterable-query__results">
		<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>

	<p class="sekeco-filterable-query__empty" hidden>
		<?php esc_html_e( 'No items found for this filter.', 'sekeco' ); ?>
	</p>
</div>
