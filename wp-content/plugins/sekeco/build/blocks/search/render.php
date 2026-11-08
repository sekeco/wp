<?php
/**
 * Server render for the Sekeco full-screen search block.
 *
 * The overlay is a native <dialog>; recommendations and the masonry are fetched
 * from the sekeco/v1 REST routes the first time it opens. Without JavaScript
 * the trigger is hidden and the theme's search page is still reachable.
 *
 * @package Sekeco
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Serialized inner blocks.
 * @var WP_Block $block      Block instance.
 */

defined( 'ABSPATH' ) || exit;

$floating    = ! isset( $attributes['floating'] ) || ! empty( $attributes['floating'] );
$placeholder = isset( $attributes['placeholder'] ) && '' !== $attributes['placeholder']
	? $attributes['placeholder']
	: __( 'Search the site', 'sekeco' );

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class'            => 'sk-search',
		'data-sk-search'   => '',
		'data-endpoint'    => esc_url_raw( rest_url( 'sekeco/v1/' ) ),
		'data-empty-label' => __( 'No results for', 'sekeco' ),
	)
);

$links = array(
	array( __( 'Works', 'sekeco' ), home_url( '/works/' ) ),
	array( __( 'Portfolio', 'sekeco' ), home_url( '/portfolio/' ) ),
	array( __( 'Team', 'sekeco' ), home_url( '/team/' ) ),
	array( __( 'Journal', 'sekeco' ), home_url( '/journal/' ) ),
);
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<button type="button" class="sk-search__trigger<?php echo $floating ? ' sk-search__trigger--float' : ' sk-search__trigger--inline'; ?>" data-sk-search-open aria-haspopup="dialog" aria-label="<?php esc_attr_e( 'Search', 'sekeco' ); ?>" hidden>
		<?php echo sekeco_icon( 'magnifer', array( 'size' => 22 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</button>

	<dialog class="sk-search__dialog" aria-label="<?php esc_attr_e( 'Search', 'sekeco' ); ?>">
		<div class="sk-search__panel">
			<div class="sk-search__main">
				<form class="sk-search__form" role="search" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
					<span class="sk-search__glyph"><?php echo sekeco_icon( 'magnifer', array( 'size' => 28 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<label class="screen-reader-text" for="sk-search-input"><?php esc_html_e( 'Search', 'sekeco' ); ?></label>
					<input id="sk-search-input" class="sk-search__input" type="search" name="s" placeholder="<?php echo esc_attr( $placeholder ); ?>" autocomplete="off" autocapitalize="off" spellcheck="false" aria-controls="sk-search-results">
					<kbd class="sk-search__kbd" aria-hidden="true">esc</kbd>
				</form>

				<nav class="sk-search__quick" aria-label="<?php esc_attr_e( 'Browse', 'sekeco' ); ?>">
					<?php foreach ( $links as $link ) : ?>
						<a href="<?php echo esc_url( $link[1] ); ?>"><?php echo esc_html( $link[0] ); ?></a>
					<?php endforeach; ?>
				</nav>

				<p class="sk-search__label" data-sk-search-label><?php esc_html_e( 'Recommended', 'sekeco' ); ?></p>
				<ul class="sk-search__results" id="sk-search-results" data-sk-search-results></ul>
				<p class="sk-search__status" role="status" aria-live="polite" data-sk-search-status></p>
			</div>

			<aside class="sk-search__aside" aria-label="<?php esc_attr_e( 'Random picks', 'sekeco' ); ?>">
				<div class="sk-search__tiles" data-sk-search-tiles></div>
			</aside>

			<button type="button" class="sk-search__close" data-sk-search-close aria-label="<?php esc_attr_e( 'Close search', 'sekeco' ); ?>">
				<?php echo sekeco_icon( 'close-circle', array( 'size' => 24 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<span><?php esc_html_e( 'Close', 'sekeco' ); ?></span>
			</button>
		</div>
	</dialog>
</div>
