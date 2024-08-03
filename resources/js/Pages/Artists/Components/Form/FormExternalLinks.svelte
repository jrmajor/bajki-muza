<script lang="ts">
	let { name }: { name: string } = $props();

	let googleUrl = $derived.by(() => {
		let params = new URLSearchParams({ q: name, tbm: 'isch' });
		return `https://www.google.com/search?${params}`;
	});

	let fototekaUrl = $derived.by(() => {
		let nameParts = name.split(' ');
		let lastName = nameParts.pop();
		let firstName = nameParts.join(' ');
		let params = new URLSearchParams({
			key: `${lastName} ${firstName}`,
			search_type_in: 'osoba',
			'filter[charakter][]': 'portret',
		});
		return `https://fototeka.fn.org.pl/pl/strona/wyszukiwarka.html?${params}`;
	});
</script>

<div class="flex flex-col gap-2 items-center py-3 sm:flex-row sm:gap-5">
	<a href={googleUrl} target="_blank" class="text-sm font-medium">
		<span class="shadow-link">Google</span> →
	</a>
	<a href={fototekaUrl} target="_blank" class="text-sm font-medium">
		<span class="shadow-link">Fototeka</span> →
	</a>
</div>
