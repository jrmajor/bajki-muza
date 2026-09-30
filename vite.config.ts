import inertia from '@inertiajs/vite';
import { svelte } from '@sveltejs/vite-plugin-svelte';
import tailwindcss from '@tailwindcss/vite';
import laravel from 'laravel-vite-plugin';
import { google } from 'laravel-vite-plugin/fonts';
import { defineConfig } from 'vite';
import run from 'vite-plugin-run';

const dir = import.meta.dirname;

export default defineConfig({
	plugins: [
		laravel({
			input: ['resources/js/app.ts', 'resources/css/style.css'],
			refresh: true,
			fonts: [
				google('Inter', {
					alias: 'sans',
					weights: ['100..900'],
					subsets: ['latin', 'latin-ext'],
					fallbacks: ['ui-sans-serif', 'system-ui', 'sans-serif'],
					optimizedFallbacks: false,
				}),
			],
		}),
		inertia(),
		run({
			name: 'ziggy',
			pattern: 'routes/*.php',
			run: ['php', 'artisan', 'ziggy:generate', '--types'],
			build: false,
		}),
		svelte(),
		tailwindcss(),
	],
	build: {
		assetsInlineLimit: 4096,
	},
	resolve: {
		tsconfigPaths: true,
	},
	server: {
		watch: {
			ignored: [
				`${dir}/storage/framework/views/**`,
			],
		},
	},
});
