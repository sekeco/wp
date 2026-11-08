/**
 * Shared motion definitions.
 */
import { __ } from '@wordpress/i18n';

export const SUPPORTED_BLOCKS = [ 'core/group', 'core/cover' ];

export const MOTION_ATTRIBUTES = {
	sekecoMotion: {
		type: 'string',
		default: 'none',
	},
	sekecoDuration: {
		type: 'number',
		default: 700,
	},
	sekecoDelay: {
		type: 'number',
		default: 0,
	},
	sekecoTrigger: {
		type: 'string',
		default: 'scroll',
	},
};

export const MOTION_OPTIONS = [
	{ label: __( 'None', 'sekeco' ), value: 'none' },
	{ label: __( 'Fade', 'sekeco' ), value: 'fade' },
	{ label: __( 'Fade up', 'sekeco' ), value: 'fade-up' },
	{ label: __( 'Fade down', 'sekeco' ), value: 'fade-down' },
	{ label: __( 'Slide from right', 'sekeco' ), value: 'slide-left' },
	{ label: __( 'Slide from left', 'sekeco' ), value: 'slide-right' },
	{ label: __( 'Zoom', 'sekeco' ), value: 'zoom' },
];

export const TRIGGER_OPTIONS = [
	{ label: __( 'On scroll', 'sekeco' ), value: 'scroll' },
	{ label: __( 'On page load', 'sekeco' ), value: 'load' },
];
