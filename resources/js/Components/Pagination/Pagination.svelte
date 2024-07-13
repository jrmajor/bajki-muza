<script lang="ts">
	import MobilePagination from './MobilePagination.svelte';
	import DesktopPagination from './DesktopPagination.svelte';

	let { meta }: { meta: PaginationMeta } = $props();
</script>

{#if meta.last_page > 1}
	<nav aria-label="Nawigacja" class="w-full text-sm font-medium leading-5">
		<div class="flex justify-around w-full sm:hidden gap-3">
			<MobilePagination {meta}/>
		</div>
		<div class="hidden justify-center w-full sm:flex">
			<DesktopPagination {meta}/>
		</div>
	</nav>
{/if}
