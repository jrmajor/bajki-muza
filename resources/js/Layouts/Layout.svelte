<script lang="ts">
	import type { Snippet } from 'svelte';
	import { route } from 'ziggy-js';
	import MainMenuButton from './MainMenuButton.svelte';
	import Footer from './Footer.svelte';

	let { user, children }: { children: Snippet } & SharedProps = $props();
</script>

<div class="container flex flex-col justify-between mx-auto min-h-screen">
	<div>
		<nav class="flex gap-4 justify-center my-8">
			<MainMenuButton href={route('tales.index')}>Bajki</MainMenuButton>
			<MainMenuButton href={route('artists.index')}>Artyści</MainMenuButton>
		</nav>

		<div class="flex flex-col items-center">
			<main class="flex flex-col items-center px-5 w-full md:px-8 lg:w-3/4 xl:1/2">
				{@render children()}
			</main>
		</div>
	</div>

	<Footer {user}/>
</div>
