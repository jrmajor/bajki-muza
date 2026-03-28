import inertia from '@inertiajs/vite';
import { svelte } from '@sveltejs/vite-plugin-svelte';
import tailwindcss from '@tailwindcss/vite';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';
import run from 'vite-plugin-run';

export default defineConfig({
	plugins: [
		laravel({
			input: ['resources/js/app.ts', 'resources/css/style.css'],
			refresh: true,
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
});
