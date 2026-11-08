/**
 * Dynamic block: the inner Query Loop is stored in post content and rendered
 * by render.php.
 */
import { InnerBlocks } from '@wordpress/block-editor';

export default function save() {
	return <InnerBlocks.Content />;
}
