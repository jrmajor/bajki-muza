<script lang="ts">
	import { route } from 'ziggy-js';
	import { inertia } from '@inertiajs/svelte';

	let { user }: { user: SharedUser } = $props();

	const currentYear = new Date().getFullYear();
</script>

<footer class="px-3 my-8 text-sm text-center text-gray-500 dark:text-gray-600">
	<div>
		<!-- &copy; --> 2019<a href="{route('login')}" class="cursor-text">-</a>{currentYear}
		<!-- <a href="mailto:jeremiah.major@bajki-muza.pl">Jeremiah Major</a> -->
	</div>
	{#if user}
		<div>
			<a href="{route('tales.create')}" class="mr-1.5 text-xs uppercase tracking-wide">
				Dodaj bajkę
			</a>
			<a href="{route('logout')}" use:inertia={{ method: 'post' }} class="ml-1.5 text-xs uppercase tracking-wide">
				Wyloguj
			</a>
		</div>
	{/if}
</footer>
