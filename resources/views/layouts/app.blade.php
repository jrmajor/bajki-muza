<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="theme-color" content="#ebebeb" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#121212" media="(prefers-color-scheme: dark)">

    @yield('head')

    @routes

    @production
      <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    @endproduction

    @if (config('services.fathom.id'))
      <script src="https://cdn.usefathom.com/script.js" data-site="{{ config('services.fathom.id') }}" defer></script>
    @endif
  </head>
  <body class="font-sans text-gray-900 bg-gray-200 dark:bg-gray-950 dark:text-gray-200">
    <div class="container flex flex-col justify-between mx-auto min-h-screen">

      <div>
        <nav class="flex gap-4 justify-center my-8">
          <a href="{{ route('tales.index') }}" alt="Bajki"
            class="px-5 py-2.5 bg-white dark:bg-gray-700 shadow-lg rounded-full
              text-gray-900 dark:text-white transition-colors-shadow duration-200 uppercase font-semibold tracking-wide
              focus:outline-none hover:ring focus:ring active:ring ring-brand-primary hover:ring-opacity-50 focus:ring-opacity-75 active:ring-opacity-100">
            Bajki
          </a>
          <a href="{{ route('artists.index') }}" alt="Artyści"
            class="px-5 py-2.5 bg-white dark:bg-gray-700 shadow-lg rounded-full
              text-gray-900 dark:text-white transition-colors-shadow duration-200 uppercase font-semibold tracking-wide
              focus:outline-none hover:ring focus:ring active:ring ring-brand-primary hover:ring-opacity-50 focus:ring-opacity-75 active:ring-opacity-100">
            Artyści
          </a>
        </nav>

        <div class="flex flex-col items-center">
          <main class="flex flex-col items-center px-5 w-full md:px-8 lg:w-3/4 xl:1/2">
            @yield('main')
          </main>
        </div>
      </div>

      <footer class="px-3 my-8 text-sm text-center text-gray-500 dark:text-gray-600">
        <div>
          {{-- &copy; --}} 2019<a href="{{ route('login') }}" class="cursor-text">-</a>{{ now()->year }} {{-- <a href="mailto:jeremiah.major@bajki-muza.pl">Jeremiah Major</a> --}}
        </div>
        @auth
          <div x-data>
            <form x-ref="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none">
              @csrf
            </form>
            <a
              href="{{ route('tales.create') }}"
              class="mr-1.5 text-xs uppercase tracking-wide"
            >
              Dodaj bajkę
            </a>
            <a
              href="{{ route('logout') }}"
              class="ml-1.5 text-xs uppercase tracking-wide"
              x-on:click.prevent="$refs.logoutForm.submit()"
            >
              Wyloguj
            </a>
          </div>
        @endauth
      </footer>
    </div>
  </body>
</html>
