/**
 * Small helpers shared by the component blocks.
 */
import { InnerBlocks } from '@wordpress/block-editor';

/** Dynamic block that only stores inner blocks. */
export const saveInner = () => <InnerBlocks.Content />;

/** Dynamic block with no stored markup. */
export const saveNull = () => null;

/** Formats allowed inside display text: bold and italic (italic = dimmed). */
export const DISPLAY_FORMATS = [ 'core/bold', 'core/italic' ];
