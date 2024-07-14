<script lang="ts">
	import Layout from '@/Layouts/Layout.svelte';

	let { user }: SharedProps = $props();
</script>

<Layout {user}>
	<h2 class="mb-2 text-2xl font-medium">Błąd 404</h2>
	Nie znaleziono takiej strony.
</Layout>
