<script lang="ts">
	let {
		value = $bindable(),
		searchUsing: searchCallback,
		id = null,
	}: {
		value: unknown; // todo: stricter type
		searchUsing: (query: string) => Promise<Entry[]>;
		id?: string | null;
	} = $props();

	type Entry = { label: string; value: string };

	let isOpen = $state(false);
	let shouldCloseOnBlur = $state(true);
	let hoveredIndex: number | null = $state(null);
	let results: Entry[] = $state([]);

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
		if (results.length === 0) return;

		if (hoveredIndex === null) {
			hoveredIndex = direction === 'up' ? results.length - 1 : 0;
			return;
		}

		hoveredIndex = direction === 'up' ? hoveredIndex - 1 : hoveredIndex + 1;

		if (hoveredIndex < 0) hoveredIndex = results.length - 1;
		if (hoveredIndex > results.length - 1) hoveredIndex = 0;
	}

	function enter() {
		if (hoveredIndex !== null) select(results[hoveredIndex]);
	}

	function oninput() {
		if (String(value).length < 2) {
			results = [];
			return;
		}

		isOpen = true;

		searchCallback(String(value)).then((data) => {
			results = data;
			if (hoveredIndex && hoveredIndex > results.length - 1) hoveredIndex = null;
		});
	}

	function closeDropdown() {
		if (!shouldCloseOnBlur) {
			shouldCloseOnBlur = true;
			return;
		}

		isOpen = false;

		hoveredIndex = null;
		shouldCloseOnBlur = true;
	}

	function select(entry: Entry) {
		value = entry.value;
		closeDropdown();
	}
</script>

<div class="relative w-full">
	<input
		type="text"
		class="w-full form-input"
		autocomplete="off"
		bind:value
		{id}
		{onkeydown}
		{oninput}
		onfocus={() => isOpen = shouldCloseOnBlur = true}
		onblur={closeDropdown}
	>
	{#if isOpen && !(String(value).length > 1 && results.length === 0)}
		<!-- svelte-ignore a11y_no_noninteractive_element_interactions -->
		<ul
			class="absolute z-50 py-1 mt-2 w-full text-gray-800 bg-white rounded-md border border-gray-300 shadow-md"
			onmousedown={() => shouldCloseOnBlur = false}
		>
			{#each results as result, index (result.value)}
				<!-- svelte-ignore a11y_click_events_have_key_events -->
				<!-- svelte-ignore a11y_mouse_events_have_key_events -->
				<li
					onmouseover={() => hoveredIndex = index}
					onclick={() => select(result)}
					class="flex justify-between py-1 px-3 w-full text-left text-gray-800 select-none"
					class:bg-gray-200={hoveredIndex === index}
				>
					<span>{result.label}</span>
					<span class="text-gray-800">{value === result.value ? '✓ ' : ''}</span>
				</li>
			{:else}
				<li class="py-1 px-3 w-full text-gray-600">
					Brak wyników
				</li>
			{/each}
		</ul>
	{/if}
</div>
