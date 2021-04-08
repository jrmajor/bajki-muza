<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @hasSection('title')
      <title>@yield('title')</title>
    @else
      <title>{{ config('app.name') }}</title>
    @endif

    @hasSection('meta')
      @yield('meta')
    @endif

    @production
      <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    @endproduction
    @unless (app()->runningUnitTests())
      <link rel="stylesheet" href="{{ mix('css/style.css') }}">
    @endif

    @routes
    @unless (app()->runningUnitTests())
      <script src="{{ mix('js/app.js') }}" defer></script>
    @endif
    @stack('scripts')
  </head>
  <body class="font-sans text-gray-900 bg-gray-200 dark:bg-gray-950 dark:text-gray-200">
    <div id="app" class="container flex flex-col justify-between mx-auto min-h-screen">

      <div>
        <nav class="flex gap-4 justify-center my-8">
          <a href="{{ route('tales.index') }}" alt="Bajki"
            class="px-5 py-2.5 bg-white dark:bg-gray-700 shadow-lg rounded-full
              text-gray-900 dark:text-white transition-colors-shadow duration-200 uppercase font-semibold tracking-wide
              focus:outline-none hover:ring focus:ring active:ring ring-yellow-kox hover:ring-opacity-50 focus:ring-opacity-75 active:ring-opacity-100">
            Bajki
          </a>
          <a href="{{ route('artists.index') }}" alt="Artyści"
            class="px-5 py-2.5 bg-white dark:bg-gray-700 shadow-lg rounded-full
              text-gray-900 dark:text-white transition-colors-shadow duration-200 uppercase font-semibold tracking-wide
              focus:outline-none hover:ring focus:ring active:ring ring-yellow-kox hover:ring-opacity-50 focus:ring-opacity-75 active:ring-opacity-100">
            Artyści
          </a>
        </nav>

        <div class="flex flex-col items-center">
          <main class="flex flex-col items-center px-5 w-full md:px-8 lg:w-3/4 xl:1/2">
            @yield('content')
          </main>
        </div>
      </div>

      <footer class="px-3 my-8 text-sm text-center text-gray-500 dark:text-gray-600">
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
