<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<meta name="theme-color" content="#ebebeb" media="(prefers-color-scheme: light)">
		<meta name="theme-color" content="#121212" media="(prefers-color-scheme: dark)">

		@php
			$siteSchema = [
				'@context' => 'https://schema.org',
				'@type' => 'WebSite',
				'name' => 'Bajki Polskich Nagrań „Muza”',
				'url' => route('home'),
			];
		@endphp

		<meta name="application-name" content="Bajki Polskich Nagrań „Muza”">
		<meta property="og:site_name" content="Bajki Polskich Nagrań „Muza”">
		<script type="application/ld+json">
			@json($siteSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
		</script>

		<x-inertia::head>
			<title>Bajki Polskich Nagrań „Muza”</title>
		</x-inertia::head>

		<link rel="preconnect" href="https://rsms.me/">
		<link rel="stylesheet" href="https://rsms.me/inter/inter.css">

		<script>
			window.config = {
				posthogToken: @json(config('services.posthog.token')),
			};
		</script>

		@unless (app()->runningUnitTests())
			@vite('resources/css/style.css')
			@vite(['resources/js/app.ts', "resources/js/Pages/{$page['component']}.svelte"])
		@endunless

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
		<x-inertia::app/>
	</body>
</html>
