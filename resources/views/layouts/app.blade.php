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
        @stack('scripts')
    </head>
    <body class="bg-gray-200 text-gray-900">
        <div id="app" class="container mx-auto">

            <div class="my-8 flex justify-center space-x-4">
                <a href="{{ route('tales.index') }}" alt="Bajki"
                    class="flex items-center px-5 py-2.5 bg-white shadow-lg rounded-full
                        text-gray-900 hover:text-yellow-kox duration-200 uppercase font-semibold tracking-wide">
                    Bajki
                </a>
                <a href="{{ route('artists.index') }}" alt="Artyści"
                    class="flex items-center px-5 py-2.5 bg-white shadow-lg rounded-full
                        text-gray-900 hover:text-yellow-kox duration-200 uppercase font-semibold tracking-wide">
                    Artyści
                </a>
            </div>

            <div class="flex flex-col items-center">
                <main class="px-5 md:px-8 w-full lg:w-3/4 xl:1/2 flex flex-col items-center">
                    @yield('content')
                </main>
            </div>

            <footer class="my-8 px-3 text-center text-gray-500 text-sm">
                {{-- &copy; --}} 2019<a href="{{ route('login') }}" class="cursor-text">-</a>{{ now()->year }} {{-- <a href="mailto:jeremiah.major@bajki-muza.pl">Jeremiah Major</a> --}}
                @auth
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none">
                        @csrf
                    </form>
                    <br>
                    <a href="{{ route('tales.create') }}"
                        class="mr-1.5 text-xs uppercase tracking-wide">
                        Dodaj bajkę
                    </a>
                    <a href="{{ route('logout') }}"
                        class="ml-1.5 text-xs uppercase tracking-wide"
                        onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        Wyloguj
                    </a>
                @endauth
            </footer>
        </div>
    </body>
</html>
