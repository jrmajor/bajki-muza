<script lang="ts">
	import { route } from 'ziggy-js';

	let {
		value = $bindable(),
	}: {
		value: string;
	} = $props();

	let hovered: number | null = $state(null);
	let isOpen = $state(false);
	let shouldCloseOnBlur = $state(true);
	let search = $state('');
	let artists = $state([]);

	function onkeydown(event: KeyboardEvent) {
		const listener = {
			ArrowUp: () => arrow('up'),
			ArrowDown: () => arrow('down'),
			Enter: enter,
		}[event.key];

		if (!listener) return;
		event.preventDefault();
		listener();
	}

	function arrow(direction: 'up' | 'down') {
		if (artists.length === 0) return;

		if (hovered === null) {
			hovered = direction === 'up' ? artists.length - 1 : 0;
			return;
		}

		hovered = direction === 'up' ? hovered - 1 : hovered + 1;

		if (hovered < 0) hovered = artists.length - 1;
		if (hovered > artists.length - 1) hovered = 0;
	}

	function enter() {
		if (hovered !== null) select(artists[hovered]);
	}

	function findArtists() {
		if (value.length < 2) {
			artists = [];
			return;
		}

		isOpen = true;

		fetch(route('ajax.artists', { search: value }))
			.then((response) => response.json())
			.then((data) => {
				artists = data;
				if (hovered && hovered > artists.length - 1) hovered = null;
			});
	}

	function closeDropdown() {
		if (!shouldCloseOnBlur) {
			shouldCloseOnBlur = true;
			return;
		}

		isOpen = false;

		hovered = null;
		shouldCloseOnBlur = true;
	}

	function select(artist: string) {
		value = artist;
		closeDropdown();
	}
</script>

<div class="relative w-full">
	<input
		type="text"
		class="w-full form-input"
		autocomplete="off"
		bind:value={value}
		{onkeydown}
		oninput={findArtists}
		onfocus={() => isOpen = shouldCloseOnBlur = true}
		onblur={closeDropdown}
	>
	{#if isOpen && !(search.length > 1 && artists.length === 0)}
		<!-- svelte-ignore a11y_no_noninteractive_element_interactions -->
		<ul
			class="absolute z-50 py-1 mt-2 w-full text-gray-800 bg-white rounded-md border border-gray-300 shadow-md"
			onmousedown={() => shouldCloseOnBlur = false}
		>
			{#if artists.length === 0}
				<li class="py-1 px-3 w-full text-gray-600">
					Brak wyników
				</li>
			{/if}
			{#each artists as artist, index (artist)}
				<!-- svelte-ignore a11y_click_events_have_key_events -->
				<!-- svelte-ignore a11y_mouse_events_have_key_events -->
				<li
					onmouseover={() => hovered = index}
					onclick={() => select(artist)}
					class="flex justify-between py-1 px-3 w-full text-left text-gray-800 select-none"
					class:bg-gray-200={hovered === index}
				>
					<span>{artist}</span>
					<span class="text-gray-800">{value === artist ? '✓ ' : ''}</span>
				</li>
			{/each}
		</ul>
	{/if}
</div>
