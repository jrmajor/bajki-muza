<script lang="ts">
	let { name }: { name: string } = $props();

	let googleUrl = $derived.by(() => {
		let urlParams = new URLSearchParams();
		urlParams.append('q', name);
		urlParams.append('tbm', 'isch');
		return `https://www.google.com/search?${urlParams.toString()}`;
	});

	let fototekaUrl = $derived.by(() => {
		let urlParams = new URLSearchParams();
		let nameParts = name.split(' ');
		let lastName = nameParts.pop();
		let firstName = nameParts.join(' ');
		urlParams.append('key', `${lastName} ${firstName}`);
		urlParams.append('search_type_in', 'osoba');
		urlParams.append('filter[charakter][]', 'portret');
		return `https://fototeka.fn.org.pl/pl/strona/wyszukiwarka.html?${urlParams.toString()}`;
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
