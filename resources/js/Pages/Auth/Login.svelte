<script lang="ts">
	import { useForm } from '@inertiajs/svelte';
	import { route } from 'ziggy-js';
	import Title from '@/Components/Title.svelte';

	const form = useForm({
		username: '',
		password: '',
		remember: false,
	});

	function onsubmit(event: SubmitEvent) {
		event.preventDefault();
		$form.post(route('login'));
	}
</script>

<svelte:head>
	<title>Logowanie - Bajki Polskich Nagrań „Muza”</title>
</svelte:head>

<div class="text-center">
	<Title text="Login"/>
</div>

<form {onsubmit} class="flex flex-col gap-5 mt-5">
	<div class="flex flex-col gap-2 items-center sm:flex-row">
		<input type="text" bind:value={$form.username} class="w-full form-input">
		<input type="password" bind:value={$form.password} class="w-full form-input">
	</div>

	<div class="flex justify-between items-center">
		<div>
			<input type="checkbox" id="remember" bind:checked={$form.remember} class="hidden">
			<label for="remember" class="text-2xl">{$form.remember ? '🦞' : '🦎'}</label>
		</div>

		<button type="submit" class="text-2xl">🐠</button>
	</div>
</form>
