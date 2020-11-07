@extends('layouts.app')

@section('title', $artist->name)

@section('content')

  <div class="flex flex-col sm:flex-row items-center mb-6">

    <div class="sm:hidden text-center">
      <h2 class="text-2xl font-medium">
        @auth
          <a href="{{ route('artists.edit', $artist) }}">
        @endauth
        @foreach (explode(' ', $artist->name) as $word)
          <span class="shadow-title">{{ $word }}</span>
        @endforeach
        @auth
          </a>
        @endauth
      </h2>
    </div>

    @if ($artist->photo())
      <div style="width: {{ ($artist->photo_width / $artist->photo_height) * 10 }}rem"
        class="relative mt-5 mb-2 sm:my-0 sm:mr-6 -p-px flex-none self-center h-40 shadow-lg rounded-lg overflow-hidden">
        @auth
          <form id="flush-cache-form" method="post" action="{{ route('artists.flushCache', $artist) }}" class="hidden"> @csrf </form>
        @endauth
        <div class="bg-gray-400 dark:bg-gray-800 bg-center bg-cover absolute -inset-px"
          style="background-image: url(&quot;{{ $artist->photo_placeholder }}&quot;)">
          <img src="{{ $artist->photo('320') }}"
            srcset="
              {{ $artist->photo('160') }} 1x,
              {{ $artist->photo('240') }} 1.5x,
              {{ $artist->photo('320') }} 2x"
            @auth onclick="document.getElementById('flush-cache-form').submit()" @endauth
            loading="eager"
            class="w-full h-full object-center object-cover transition-opacity duration-300 opacity-0">
        </div>
      </div>
    @elseif ($artist->discogsPhoto() && Auth::guest())
      <div class="mt-5 mb-2 sm:my-0 sm:mr-6 flex-none self-center h-40 shadow-lg rounded-lg overflow-hidden">
        @auth
          <form id="flush-cache-form" method="post" action="{{ route('artists.flushCache', $artist) }}" class="hidden"> @csrf </form>
        @endauth

        <img src="{{ $artist->discogsPhoto() }}" class="h-40"
          @auth onclick="document.getElementById('flush-cache-form').submit()" @endauth
          >
      </div>
    @endif

    <div class="
      flex flex-col flex-grow justify-between space-y-3
      @if ($artist->photo() || $artist->discogsPhoto()) sm:py-2 @endif
      @if ($artist->wikipedia) self-stretch @endif
    ">

      <div class="
        hidden sm:block
        @if ($artist->photo() || $artist->wikipedia) self-start @else self-center @endif">
        <h2 class="text-2xl font-medium">
          @auth <a href="{{ route('artists.edit', $artist) }}"> @endauth
            <span class="shadow-title">{{ $artist->name }}</span>
          @auth </a> @endauth
        </h2>
      </div>

      @if ($artist->discogs || $artist->filmpolski || $artist->wikipedia)
        <div class="
          flex flex-col space-y-2
          @if ($artist->photo() || $artist->discogsPhoto() || $artist->wikipedia) self-stretch @else self-center @endif
        ">
          @if ($artist->wikipedia)
            <div>
              {!! strip_tags($artist->wikipedia_extract) !!}
            </div>
          @endif

          <div class="self-center sm:self-start flex items-center space-x-5">
            @if ($artist->discogs)
              <a href="{{ $artist->discogs_url }}" target="_blank">
                <x-icons.discogs class="fill-current h-5"/>
              </a>
            @endif
            @if ($artist->filmpolski)
              <a href="{{ $artist->filmpolski_url }}" target="_blank"
                class="font-medium">
                <span class="text-filmpolski-blue dark:text-filmpolski-blue-lighter">FILM</span><span class="text-filmpolski-gray dark:text-filmpolski-gray-inverted">POLSKI</span><span class="text-filmpolski-blue dark:text-filmpolski-blue-lighter">.PL</span>
              </a>
            @endif
            @if ($artist->wikipedia)
              <a href="{{ $artist->wikipedia_url }}" target="_blank">
                <x-icons.wikipedia class="fill-current h-4"/>
              </a>
            @endif
          </div>
        </div>
      @endif

    </div>

  </div>

  <div class="w-full space-y-6">
    @if ($artist->asDirector->count())
      <div class="w-full flex flex-col items-center space-y-3">
        <h3 class="text-xl font-medium shadow-subtitle">
          Reżyser
        </h3>
        <div class="w-full md:w-5/6 xl:w-2/3 flex flex-col space-y-2.5">
          @foreach ($artist->asDirector as $tale)
            <a href="{{ route('tales.show', $tale) }}"
              class="w-full h-13 flex items-center bg-gray-50 dark:bg-gray-900 rounded-lg shadow-lg overflow-hidden">
              <div class="flex-none bg-placeholder-cover w-13 h-13"
                @if ($tale->cover()) style="background-image: url(&quot;{{ $tale->cover_placeholder }}&quot;)" @endif
                >
                @if ($tale->cover())
                  <img src="{{ $tale->cover('120') }}"
                    srcset="
                      {{ $tale->cover('60') }} 1x,
                      {{ $tale->cover('90') }} 1.5x,
                      {{ $tale->cover('120') }} 2x"
                    loading="lazy"
                    alt="Okładka bajki {{ $tale->title }}"
                    class="w-13 h-13 object-cover transition-opacity duration-300 opacity-0">
                @endif
              </div>
              <div class="flex-grow p-2 pl-3 text-sm sm:text-base font-medium leading-tight">
                {{ $tale->title }}
              </div>
              <div class="pr-5">
                <small>{{ $tale->year }}</small>
              </div>
            </a>
          @endforeach
        </div>
      </div>
    @endif

    @if ($artist->asLyricist->count())
      <div class="w-full flex flex-col items-center space-y-3">
        <h3 class="text-xl font-medium shadow-subtitle">
          Autor
        </h3>
        <div class="w-full md:w-5/6 xl:w-2/3 flex flex-col space-y-2.5">
          @foreach ($artist->asLyricist as $tale)
            <a href="{{ route('tales.show', $tale) }}"
              class="w-full h-13 flex items-center bg-gray-50 dark:bg-gray-900 rounded-lg shadow-lg overflow-hidden">
              <div class="flex-none bg-placeholder-cover w-13 h-13"
                @if ($tale->cover()) style="background-image: url(&quot;{{ $tale->cover_placeholder }}&quot;)" @endif
                >
                @if ($tale->cover())
                  <img src="{{ $tale->cover('120') }}"
                    srcset="
                      {{ $tale->cover('60') }} 1x,
                      {{ $tale->cover('90') }} 1.5x,
                      {{ $tale->cover('120') }} 2x"
                    loading="lazy"
                    alt="Okładka bajki {{ $tale->title }}"
                    class="w-13 h-13 object-cover transition-opacity duration-300 opacity-0">
                @endif
              </div>
              <div class="flex-grow p-2 pl-3 text-sm sm:text-base font-medium leading-tight">
                {{ $tale->title }}
              </div>
              <div class="pr-5">
                <small>{{ $tale->year }}</small>
              </div>
            </a>
          @endforeach
        </div>
      </div>
    @endif

    @if ($artist->asComposer->count())
      <div class="w-full flex flex-col items-center space-y-3">
        <h3 class="text-xl font-medium shadow-subtitle">
          Kompozytor
        </h3>
        <div class="w-full md:w-5/6 xl:w-2/3 flex flex-col space-y-2.5">
          @foreach ($artist->asComposer as $tale)
            <a href="{{ route('tales.show', $tale) }}"
              class="w-full h-13 flex items-center bg-gray-50 dark:bg-gray-900 rounded-lg shadow-lg overflow-hidden">
              <div class="flex-none bg-placeholder-cover w-13 h-13"
                @if ($tale->cover()) style="background-image: url(&quot;{{ $tale->cover_placeholder }}&quot;)" @endif
                >
                @if ($tale->cover())
                  <img src="{{ $tale->cover('120') }}"
                    srcset="
                      {{ $tale->cover('60') }} 1x,
                      {{ $tale->cover('90') }} 1.5x,
                      {{ $tale->cover('120') }} 2x"
                    loading="lazy"
                    alt="Okładka bajki {{ $tale->title }}"
                    class="w-13 h-13 object-cover transition-opacity duration-300 opacity-0">
                @endif
              </div>
              <div class="flex-grow p-2 pl-3 text-sm sm:text-base font-medium leading-tight">
                {{ $tale->title }}
              </div>
              <div class="pr-5">
                <small>{{ $tale->year }}</small>
              </div>
            </a>
          @endforeach
        </div>
      </div>
    @endif

    @if ($artist->asActor->count())
      <div class="w-full flex flex-col items-center space-y-3">
        <h3 class="text-xl font-medium shadow-subtitle">
          Aktor
        </h3>
        <div class="w-full md:w-5/6 xl:w-2/3 flex flex-col space-y-2.5">
          @foreach ($artist->asActor as $tale)
            <a href="{{ route('tales.show', $tale) }}"
              class="w-full h-15 flex items-center bg-gray-50 dark:bg-gray-900 rounded-lg shadow-lg overflow-hidden">
              <div class="flex-none bg-placeholder-cover w-15 h-15"
                @if ($tale->cover()) style="background-image: url(&quot;{{ $tale->cover_placeholder }}&quot;)" @endif
                >
                @if ($tale->cover())
                  <img src="{{ $tale->cover('120') }}"
                    srcset="
                      {{ $tale->cover('60') }} 1x,
                      {{ $tale->cover('90') }} 1.5x,
                      {{ $tale->cover('120') }} 2x"
                    loading="lazy"
                    alt="Okładka bajki {{ $tale->title }}"
                    class="w-15 h-15 object-cover transition-opacity duration-300 opacity-0">
                @endif
              </div>
              <div class="flex-grow flex flex-col justify-between p-2 pl-3">
                <div class="text-sm sm:text-base font-medium leading-tight">
                  {{ $tale->title }}
                </div>
                @if ($tale->pivot->characters)
                  <small>
                    jako
                    @foreach (explode('; ', $tale->pivot->characters) as $character)
                      {{ $character }}@if ($loop->remaining > 1), @elseif ($loop->remaining > 0) i @endif
                    @endforeach
                  </small>
                @endif
              </div>
              <div class="hidden sm:block flex-none pr-4">
                <small>{{ $tale->year }}</small>
              </div>
            </a>
          @endforeach
        </div>
      </div>
    @endif
  </div>

  @auth
    @if ($artist->appearances() == 0)
      <form method="post" action="{{ route('artists.destroy', $artist) }}">
        @csrf
        @method('delete')
        <button class="px-3 py-1.5 bg-red-700 text-red-100 text-sm tracking-wide font-medium uppercase shadow-lg rounded-full
            hover:bg-red-600 active:bg-red-800 transition-colors duration-150 ease out">
          Usuń
        </button>
      </form>
    @endif
  @endauth

@endsection
