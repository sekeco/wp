<?php
/**
 * Server render for the Sekeco Project grid block.
 *
 * @package Sekeco
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Serialized inner blocks.
 * @var WP_Block $block      Block instance.
 */

defined( 'ABSPATH' ) || exit;

$post_type = isset( $attributes['postType'] ) ? sanitize_key( $attributes['postType'] ) : 'portfolio';
$post_type = in_array( $post_type, sekeco_project_post_types(), true ) ? $post_type : 'portfolio';
$layout    = isset( $attributes['layout'] ) ? $attributes['layout'] : 'grid';
$layout    = in_array( $layout, array( 'grid', 'mosaic', 'reel', 'list' ), true ) ? $layout : 'grid';
$columns   = isset( $attributes['columns'] ) ? max( 2, min( 4, (int) $attributes['columns'] ) ) : 3;
$per_page  = isset( $attributes['perPage'] ) ? (int) $attributes['perPage'] : 6;
$term_id   = isset( $attributes['category'] ) ? (int) $attributes['category'] : 0;
$filter    = ! empty( $attributes['filter'] ) && 'list' !== $layout;

$posts = sekeco_query_projects( $post_type, $per_page, $term_id );

if ( ! $posts ) {
	return;
}

$classes = array(
	'sk-projects',
	'sk-projects--' . $layout,
	'sk-projects--type-' . $post_type,
);

if ( 'grid' === $layout ) {
	$classes[] = 'sk-projects--cols-' . $columns;
}

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => implode( ' ', $classes ) ) );

if ( 'list' === $layout ) :
	?>
	<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<?php
		foreach ( $posts as $item ) :
			list( $name, $detail ) = sekeco_split_title( get_the_title( $item ) );
			$year_key              = 'portfolio' === $item->post_type ? 'sekeco_portfolio_year' : 'sekeco_product_year';
			$year                  = 'team' === $item->post_type ? (string) get_post_meta( $item->ID, 'sekeco_team_role', true ) : (string) get_post_meta( $item->ID, $year_key, true );
			$terms                 = array();

			foreach ( array_slice( (array) get_the_category( $item->ID ), 0, 2 ) as $term ) {
				if ( 'uncategorized' !== $term->slug ) {
					$terms[] = $term->name;
				}
			}
			?>
			<a class="sk-row" href="<?php echo esc_url( get_permalink( $item ) ); ?>">
				<span class="sk-row__name"><?php echo esc_html( $name ); ?></span>
				<span class="sk-row__detail"><?php echo esc_html( '' !== $detail ? $detail : implode( ', ', $terms ) ); ?></span>
				<span class="sk-row__year"><?php echo esc_html( $year ); ?></span>
				<span class="sk-row__go"><?php echo sekeco_icon( 'arrow-right-up', array( 'size' => 22 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			</a>
		<?php endforeach; ?>
	</div>
	<?php
	return;
endif;

$filter_terms = array();

if ( $filter ) {
	foreach ( $posts as $item ) {
		foreach ( (array) get_the_category( $item->ID ) as $term ) {
			if ( 'uncategorized' !== $term->slug ) {
				$filter_terms[ $term->slug ] = $term->name;
			}
		}
	}

	asort( $filter_terms );
}
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo $filter && $filter_terms ? ' data-sk-filter' : ''; ?>>
	<?php if ( $filter && $filter_terms ) : ?>
		<div class="sk-filter" role="group" aria-label="<?php esc_attr_e( 'Filter by category', 'sekeco' ); ?>">
			<button type="button" class="sk-filter__btn is-active" data-filter="*" aria-pressed="true"><?php esc_html_e( 'All', 'sekeco' ); ?></button>
			<?php foreach ( $filter_terms as $slug => $name ) : ?>
				<button type="button" class="sk-filter__btn" data-filter="<?php echo esc_attr( $slug ); ?>" aria-pressed="false"><?php echo esc_html( html_entity_decode( $name, ENT_QUOTES, 'UTF-8' ) ); ?></button>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
	<div class="sk-projects__list">
	<?php foreach ( $posts as $index => $item ) : ?>
		<?php
		$slugs = array();

		foreach ( (array) get_the_category( $item->ID ) as $term ) {
			$slugs[] = $term->slug;
		}
		?>
		<div class="sk-projects__item" data-terms="<?php echo esc_attr( implode( ' ', $slugs ) ); ?>">
			<?php echo sekeco_render_project_card( $item, array( 'eager' => $index < 2 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	<?php endforeach; ?>
	</div>
</div>
