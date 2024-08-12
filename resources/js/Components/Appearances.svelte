<script lang="ts">
	let { count, small = false }: {
		count: number;
		small?: boolean;
	} = $props();
</script>

{#if count > 1}
	<small
		class="
			inline-flex items-center justify-center bg-yellow-300 text-yellow-800 rounded-full shadow-md
			{small ? 'ml-1.5 size-4.5 text-2xs' : 'ml-1.5 size-6 text-xs'}
		"
	>
		{count}
	</small>
{/if}
