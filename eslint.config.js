import js from '@eslint/js';
import ts from 'typescript-eslint';
import svelte from 'eslint-plugin-svelte';
import stylistic from '@stylistic/eslint-plugin';
import globals from 'globals';

/** @type {import('eslint').Linter.FlatConfig[]} */
export default [
	js.configs.recommended,
	...ts.configs.recommended,
	...svelte.configs['flat/recommended'],
	{
		plugins: {
			'@stylistic': stylistic,
		},
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
		rules: {
			'@stylistic/array-bracket-newline': ['error', 'consistent'],
			'@stylistic/array-bracket-spacing': 'error',
			'@stylistic/array-element-newline': ['error', 'consistent'],
			'@stylistic/arrow-parens': 'error',
			'@stylistic/arrow-spacing': 'error',
			'@stylistic/block-spacing': 'error',
			'@stylistic/brace-style': 'error',
			'@stylistic/comma-dangle': ['error', 'always-multiline'],
			'@stylistic/comma-spacing': 'error',
			'@stylistic/comma-style': 'error',
			'@stylistic/computed-property-spacing': 'error',
			'@stylistic/dot-location': 'error',
			'@stylistic/eol-last': 'error',
			'@stylistic/func-call-spacing': 'error',
			'@stylistic/function-call-argument-newline': ['error', 'consistent'],
			'@stylistic/function-call-spacing': 'error',
			'@stylistic/function-paren-newline': ['error', 'consistent'],
			'@stylistic/generator-star-spacing': 'error',
			'@stylistic/implicit-arrow-linebreak': 'error',
			'@stylistic/indent': ['error', 'tab'],
			'@stylistic/indent-binary-ops': ['error', 'tab'],
			'@stylistic/key-spacing': 'error',
			'@stylistic/keyword-spacing': 'error',
			'@stylistic/linebreak-style': 'error',
			'@stylistic/lines-around-comment': 'error',
			'@stylistic/lines-between-class-members': 'error',
			'@stylistic/max-statements-per-line': 'error',
			'@stylistic/member-delimiter-style': 'error',
			'@stylistic/multiline-comment-style': ['error', 'separate-lines'],
			'@stylistic/multiline-ternary': ['error', 'always-multiline'],
			'@stylistic/new-parens': 'error',
			'@stylistic/no-extra-semi': 'error',
			'@stylistic/no-floating-decimal': 'error',
			'@stylistic/no-mixed-operators': 'error',
			'@stylistic/no-mixed-spaces-and-tabs': 'error',
			'@stylistic/no-multi-spaces': 'error',
			'@stylistic/no-multiple-empty-lines': 'error',
			'@stylistic/no-trailing-spaces': 'error',
			'@stylistic/no-whitespace-before-property': 'error',
			'@stylistic/nonblock-statement-body-position': 'error',
			'@stylistic/object-curly-newline': ['error', { consistent: true }],
			'@stylistic/object-curly-spacing': ['error', 'always'],
			'@stylistic/object-property-newline': ['error', { allowAllPropertiesOnSameLine: true }],
			'@stylistic/operator-linebreak': ['error', 'before'],
			'@stylistic/padded-blocks': ['error', 'never'],
			'@stylistic/quote-props': ['error', 'as-needed'],
			'@stylistic/quotes': ['error', 'single'],
			'@stylistic/rest-spread-spacing': 'error',
			'@stylistic/semi': 'error',
			'@stylistic/semi-spacing': 'error',
			'@stylistic/semi-style': 'error',
			'@stylistic/space-before-blocks': 'error',
			'@stylistic/space-before-function-paren': ['error', { named: 'never' }],
			'@stylistic/space-in-parens': 'error',
			'@stylistic/space-infix-ops': 'error',
			'@stylistic/space-unary-ops': 'error',
			'@stylistic/spaced-comment': 'error',
			'@stylistic/switch-colon-spacing': 'error',
			'@stylistic/template-curly-spacing': 'error',
			'@stylistic/template-tag-spacing': 'error',
			'@stylistic/type-annotation-spacing': 'error',
			'@stylistic/type-generic-spacing': 'error',
			'@stylistic/type-named-tuple-spacing': 'error',
			'@stylistic/yield-star-spacing': 'error',
			// seems broken, causes parse errors
			'@typescript-eslint/no-unused-expressions': 'off',
		},
	},
	{
		files: ['**/*.svelte'],
		languageOptions: {
			parserOptions: {
				parser: ts.parser,
			},
		},
	},
	{
		ignores: [
			'public',
			'resources/js/classic',
			'resources/js/types/ziggy.d.ts',
			'vendor',
			'vite.config.[jt]s.timestamp-*',
		],
	},
];
