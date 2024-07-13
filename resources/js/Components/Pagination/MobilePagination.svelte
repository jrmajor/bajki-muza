<script lang="ts">
	import PaginationLink from './PaginationLink.svelte';

	let { meta }: { meta: PaginationMeta } = $props();
</script>

{#if meta.current_page !== 1}
	<PaginationLink url={meta.links[0].url} rel="prev" aria-label="Poprzednia" class="px-4 rounded-lg">
		&laquo; Poprzednia
	</PaginationLink>
{/if}

{#if meta.current_page < meta.last_page}
	<PaginationLink url={meta.links[meta.links.length - 1].url} rel="prev" aria-label="Następna" class="px-4 rounded-lg">
		Następna &raquo;
	</PaginationLink>
{/if}
