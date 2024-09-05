import base, { svelte } from '@jrmajor/eslint-config';
import globals from 'globals';

/** @type {import('eslint').Linter.Config[]} */
export default [
	...base,
	...svelte,
	{
		languageOptions: {
			globals: {
				...globals.browser,
				...globals.node,
				SharedProps: 'readonly',
				SharedUser: 'readonly',
				PaginatedResource: 'readonly',
				PaginationLinks: 'readonly',
				PaginationMeta: 'readonly',
			},
		},
	},
	{
		ignores: [
			'public',
			'resources/js/helpers/ziggy.*',
		],
	},
];
