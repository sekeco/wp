<?php
/**
 * Light/dark toggle. The mode is set before first paint by the theme.
 *
 * @package Matholek
 */

defined( 'ABSPATH' ) || exit;
?>
<button <?php echo get_block_wrapper_attributes( array( 'class' => 'mk-toggle', 'type' => 'button', 'aria-label' => __( 'Ganti mode terang/gelap', 'matholek' ), 'data-mk-theme-toggle' => '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><span class="mk-icon mk-icon--moon mk-toggle__moon" aria-hidden="true"></span><span class="mk-icon mk-icon--sun-2 mk-toggle__sun" aria-hidden="true"></span></button>
