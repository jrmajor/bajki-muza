<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        @hasSection('title')
            <title>@yield('title') - {{ config('app.name') }}</title>
        @else
            <title>{{ config('app.name') }}</title>
        @endif

        <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body class="bg-gray-200 text-gray-900">
        <div id="app" class="container mx-auto my-8">

            <div class="flex justify-center space-x-4">
                <a href="{{ route('tales.index') }}" alt="Bajki"
                    class="flex items-center px-4 py-2 bg-white shadow-lg rounded-full
                        text-gray-600 hover:text-gray-800 duration-500 uppercase font-semibold tracking-wide">
                    <img src="https://img.icons8.com/windows/64/ecc94b/music-record.png" class="w-8 h-8 mr-1">
                    Bajki
                </a>
                <a href="{{ route('artists.index') }}" alt="Artyści"
                    class="flex items-center px-4 py-2 bg-white shadow-lg rounded-full
                        text-gray-600 hover:text-gray-800 duration-500 uppercase font-semibold tracking-wide">
                    <img src="https://img.icons8.com/windows/64/ecc94b/person-male.png" class="w-8 h-8 mr-1">
                    Artyści
                </a>
            </div>

            <main class="mt-8 mx-5 md:mx-8">
                @yield('content')
            </main>
        </div>
    </body>
</html>
