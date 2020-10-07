@extends('layouts.app')

@section('title', $artist->name)

@section('content')

    <div class="flex flex-col sm:flex-row items-center mb-6">

        <h2 class="sm:hidden text-2xl font-medium leading-7">
            @auth <a href="{{ route('artists.edit', $artist) }}"> @endauth
                @foreach (explode(' ', $artist->name) as $word)
                    <span class="shadow-title px-1.5 @if (! $loop->last) -mx-1.5 @else -ml-1.5 @endif">{{ $word }}</span>
                @endforeach
            @auth </a> @endauth
        </h2>

        @if ($artist->discogsPhoto())
            <div class="mt-5 mb-2 sm:my-0 sm:mr-6 flex-none self-center h-40 shadow-lg rounded-lg overflow-hidden">
                @auth
                    <form id="flush-cache-form" method="post" action="{{ route('artists.flushCache', $artist) }}" class="hidden"> @csrf </form>
                @endauth

                <img src="{{ $artist->discogsPhoto() }}" class="h-40"
                    @auth onclick="document.getElementById('flush-cache-form').submit()" @endauth
                    >
            </div>
        @endif

        <div class="@if ($artist->discogsPhoto()) sm:py-2 @endif flex-grow @if ($artist->wikipedia) self-stretch @endif flex flex-col justify-between space-y-3">

            <div class="hidden sm:block self-start">
                <h2 class="text-2xl font-medium leading-7 shadow-title px-1.5 -ml-1.5">
                    @auth <a href="{{ route('artists.edit', $artist) }}"> @endauth
                        {{ $artist->name }}
                    @auth </a> @endauth
                </h2>
            </div>

            @if ($artist->discogs || $artist->filmpolski || $artist->wikipedia)
                <div class="@if ($artist->discogsPhoto() || $artist->wikipedia) self-stretch @else self-center @endif flex flex-col space-y-2">
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
                                <span style="color: #566ea1">FILM</span><span style="color: #010101">POLSKI</span><span style="color: #566ea1">.PL</span>
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
                <h3 class="text-xl font-medium leading-6 shadow-subtitle px-1">
                    Reżyser
                </h3>
                <div class="w-full md:w-5/6 xl:w-2/3 flex flex-col space-y-2.5">
                    @foreach ($artist->asDirector as $tale)
                        <a href="{{ route('tales.show', $tale) }}"
                            class="w-full h-13 flex items-center bg-gray-50 rounded-lg shadow-lg overflow-hidden">
                            <div class="flex-none bg-gray-400 bg-cover w-13 h-13"
                                style="background-image: url(
                                    @if ($tale->cover)
                                        &quot;{{ $tale->cover_placeholder }}&quot;
                                    @else
                                        &quot;data:image/svg+xml;utf8,%3Csvg viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill-rule='evenodd' d='M20 34.5c8.008 0 14.5-6.492 14.5-14.5S28.008 5.5 20 5.5 5.5 11.992 5.5 20 11.992 34.5 20 34.5zm0 1.5c8.837 0 16-7.163 16-16S28.837 4 20 4 4 11.163 4 20s7.163 16 16 16z M20 24.5a4.5 4.5 0 100-9 4.5 4.5 0 000 9zm0 1.5a6 6 0 100-12 6 6 0 000 12z M21.25 20a1.25 1.25 0 11-2.5 0 1.25 1.25 0 012.5 0z M11.345 30.061l4.1-4.746c-.27-.23-.52-.481-.751-.75L9.98 28.623c.418.513.875.994 1.365 1.44zM30.06 11.344a13.34 13.34 0 00-1.436-1.363l-4.048 4.722c.261.225.505.47.73.731l4.754-4.09z' fill='%23858c99'/%3E%3C/svg%3E&quot;
                                    @endif
                                )">
                                @if ($tale->cover)
                                    <img src="{{ $tale->cover('120') }}"
                                        srcset="
                                            {{ $tale->cover('60') }} 1x,
                                            {{ $tale->cover('90') }} 1.5x,
                                            {{ $tale->cover('120') }} 2x"
                                        loading="lazy"
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
                <h3 class="text-xl font-medium leading-6 shadow-subtitle px-1">
                    Autor
                </h3>
                <div class="w-full md:w-5/6 xl:w-2/3 flex flex-col space-y-2.5">
                    @foreach ($artist->asLyricist as $tale)
                        <a href="{{ route('tales.show', $tale) }}"
                            class="w-full h-13 flex items-center bg-gray-50 rounded-lg shadow-lg overflow-hidden">
                            <div class="flex-none bg-gray-400 bg-cover w-13 h-13"
                                style="background-image: url(
                                    @if ($tale->cover)
                                        &quot;{{ $tale->cover_placeholder }}&quot;
                                    @else
                                        &quot;data:image/svg+xml;utf8,%3Csvg viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill-rule='evenodd' d='M20 34.5c8.008 0 14.5-6.492 14.5-14.5S28.008 5.5 20 5.5 5.5 11.992 5.5 20 11.992 34.5 20 34.5zm0 1.5c8.837 0 16-7.163 16-16S28.837 4 20 4 4 11.163 4 20s7.163 16 16 16z M20 24.5a4.5 4.5 0 100-9 4.5 4.5 0 000 9zm0 1.5a6 6 0 100-12 6 6 0 000 12z M21.25 20a1.25 1.25 0 11-2.5 0 1.25 1.25 0 012.5 0z M11.345 30.061l4.1-4.746c-.27-.23-.52-.481-.751-.75L9.98 28.623c.418.513.875.994 1.365 1.44zM30.06 11.344a13.34 13.34 0 00-1.436-1.363l-4.048 4.722c.261.225.505.47.73.731l4.754-4.09z' fill='%23858c99'/%3E%3C/svg%3E&quot;
                                    @endif
                                )">
                                @if ($tale->cover)
                                    <img src="{{ $tale->cover('120') }}"
                                        srcset="
                                            {{ $tale->cover('60') }} 1x,
                                            {{ $tale->cover('90') }} 1.5x,
                                            {{ $tale->cover('120') }} 2x"
                                        loading="lazy"
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
                <h3 class="text-xl font-medium leading-6 shadow-subtitle px-1">
                    Kompozytor
                </h3>
                <div class="w-full md:w-5/6 xl:w-2/3 flex flex-col space-y-2.5">
                    @foreach ($artist->asComposer as $tale)
                        <a href="{{ route('tales.show', $tale) }}"
                            class="w-full h-13 flex items-center bg-gray-50 rounded-lg shadow-lg overflow-hidden">
                            <div class="flex-none bg-gray-400 bg-cover w-13 h-13"
                                style="background-image: url(
                                    @if ($tale->cover)
                                        &quot;{{ $tale->cover_placeholder }}&quot;
                                    @else
                                        &quot;data:image/svg+xml;utf8,%3Csvg viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill-rule='evenodd' d='M20 34.5c8.008 0 14.5-6.492 14.5-14.5S28.008 5.5 20 5.5 5.5 11.992 5.5 20 11.992 34.5 20 34.5zm0 1.5c8.837 0 16-7.163 16-16S28.837 4 20 4 4 11.163 4 20s7.163 16 16 16z M20 24.5a4.5 4.5 0 100-9 4.5 4.5 0 000 9zm0 1.5a6 6 0 100-12 6 6 0 000 12z M21.25 20a1.25 1.25 0 11-2.5 0 1.25 1.25 0 012.5 0z M11.345 30.061l4.1-4.746c-.27-.23-.52-.481-.751-.75L9.98 28.623c.418.513.875.994 1.365 1.44zM30.06 11.344a13.34 13.34 0 00-1.436-1.363l-4.048 4.722c.261.225.505.47.73.731l4.754-4.09z' fill='%23858c99'/%3E%3C/svg%3E&quot;
                                    @endif
                                )">
                                @if ($tale->cover)
                                    <img src="{{ $tale->cover('120') }}"
                                        srcset="
                                            {{ $tale->cover('60') }} 1x,
                                            {{ $tale->cover('90') }} 1.5x,
                                            {{ $tale->cover('120') }} 2x"
                                        loading="lazy"
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
                <h3 class="text-xl font-medium leading-6 shadow-subtitle px-1">
                    Aktor
                </h3>
                <div class="w-full md:w-5/6 xl:w-2/3 flex flex-col space-y-2.5">
                    @foreach ($artist->asActor as $tale)
                        <a href="{{ route('tales.show', $tale) }}"
                            class="w-full h-15 flex items-center bg-gray-50 rounded-lg shadow-lg overflow-hidden">
                            <div class="flex-none bg-gray-400 bg-cover w-15 h-15"
                                style="background-image: url(
                                    @if ($tale->cover)
                                        &quot;{{ $tale->cover_placeholder }}&quot;
                                    @else
                                        &quot;data:image/svg+xml;utf8,%3Csvg viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill-rule='evenodd' d='M20 34.5c8.008 0 14.5-6.492 14.5-14.5S28.008 5.5 20 5.5 5.5 11.992 5.5 20 11.992 34.5 20 34.5zm0 1.5c8.837 0 16-7.163 16-16S28.837 4 20 4 4 11.163 4 20s7.163 16 16 16z M20 24.5a4.5 4.5 0 100-9 4.5 4.5 0 000 9zm0 1.5a6 6 0 100-12 6 6 0 000 12z M21.25 20a1.25 1.25 0 11-2.5 0 1.25 1.25 0 012.5 0z M11.345 30.061l4.1-4.746c-.27-.23-.52-.481-.751-.75L9.98 28.623c.418.513.875.994 1.365 1.44zM30.06 11.344a13.34 13.34 0 00-1.436-1.363l-4.048 4.722c.261.225.505.47.73.731l4.754-4.09z' fill='%23858c99'/%3E%3C/svg%3E&quot;
                                    @endif
                                )">
                                @if ($tale->cover)
                                    <img src="{{ $tale->cover('120') }}"
                                        srcset="
                                            {{ $tale->cover('60') }} 1x,
                                            {{ $tale->cover('90') }} 1.5x,
                                            {{ $tale->cover('120') }} 2x"
                                        loading="lazy"
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
