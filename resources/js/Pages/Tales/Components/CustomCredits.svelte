<script lang="ts">
	import type { ShowResource } from '@/types/tales';
	import addLoop from '@/helpers/addLoop';
	import ArtistName from '@/Components/ArtistName.svelte';

	let { tale }: { tale: ShowResource } = $props();
</script>

<div class="flex flex-col">
	{#each Object.entries(tale.customCredits) as [label, credits]}
		<div>
			<strong>{label}:</strong>
			{#each addLoop(credits) as [artist, loop]}
				<ArtistName {artist} {loop}/>
			{/each}
		</div>
	{/each}
</div>
