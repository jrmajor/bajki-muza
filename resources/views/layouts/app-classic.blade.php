@extends('layouts.app')

@section('head')
  @hasSection('title')
    <title>@yield('title')</title>
  @else
    <title>{{ config('app.name') }}</title>
  @endif

  @hasSection('meta')
    @yield('meta')
  @endif

  @unless (app()->runningUnitTests())
    @vite(['resources/js/classicApp.js'])
  @endunless
@endsection

@section('outerContent')
  <div class="container flex flex-col justify-between mx-auto min-h-screen">
    <div>
      <nav class="flex gap-4 justify-center my-8">
        <a href="{{ route('tales.index') }}"
          class="px-5 py-2.5 bg-white dark:bg-gray-700 shadow-lg rounded-full
            text-gray-900 dark:text-white transition-colors-shadow duration-200 uppercase font-semibold tracking-wide
            focus:outline-none hover:ring focus:ring active:ring ring-brand-primary hover:ring-opacity-50 focus:ring-opacity-75 active:ring-opacity-100">
          Bajki
        </a>
        <a href="{{ route('artists.index') }}"
          class="px-5 py-2.5 bg-white dark:bg-gray-700 shadow-lg rounded-full
            text-gray-900 dark:text-white transition-colors-shadow duration-200 uppercase font-semibold tracking-wide
            focus:outline-none hover:ring focus:ring active:ring ring-brand-primary hover:ring-opacity-50 focus:ring-opacity-75 active:ring-opacity-100">
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
@endsection
