<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<meta name="theme-color" content="#ebebeb" media="(prefers-color-scheme: light)">
		<meta name="theme-color" content="#121212" media="(prefers-color-scheme: dark)">

		<title inertia>Bajki Polskich Nagrań „Muza”</title>

		<link rel="preconnect" href="https://rsms.me/">
		<link rel="stylesheet" href="https://rsms.me/inter/inter.css">

		<script>
			window.config = {
				posthogToken: @json(config('services.posthog.token')),
			};
		</script>

		@unless (app()->runningUnitTests())
			@vite('resources/css/style.css')
			@vite(['resources/js/browser.ts', "resources/js/Pages/{$page['component']}.svelte"])
		@endunless

		@routes
		@inertiaHead

		@if (config('services.fathom.id'))
			<script
				src="https://cdn.usefathom.com/script.js"
				data-site="{{ config('services.fathom.id') }}"
				data-spa="history"
				defer
			></script>
		@endif
	</head>
	<body class="bg-gray-200 font-sans text-gray-900 scheme-light-dark dark:bg-gray-950 dark:text-gray-200">
		@inertia
	</body>
</html>
