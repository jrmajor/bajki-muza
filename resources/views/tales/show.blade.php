@extends('layouts.app')

@section('title', $tale->title)

@section('content')

    <div class="flex flex-col sm:flex-row items-center mb-6">

        <div class="sm:hidden text-center">
            <h2 class="text-2xl font-medium">
                @auth <a href="{{ route('tales.edit', $tale) }}"> @endauth
                    @foreach (explode(' ', $tale->title) as $word)
                        <span class="shadow-title">{{ $word }}</span>
                    @endforeach
                @auth </a> @endauth
            </h2>
            @if ($tale->year)
                <div class="mt-1.5">{{ $tale->year }}</div>
            @endif
        </div>

        <div class="mt-5 mb-2 sm:my-0 sm:mr-6 flex-none self-center shadow-lg rounded-lg overflow-hidden">
            <div class="bg-placeholder-cover w-40 h-40"
                @if ($tale->cover) style="background-image: url(&quot;{{ $tale->cover_placeholder }}&quot;)" @endif
                >
                @if ($tale->cover)
                    <img src="{{ $tale->cover('320') }}"
                        srcset="
                            {{ $tale->cover('160') }} 1x,
                            {{ $tale->cover('240') }} 1.5x,
                            {{ $tale->cover('320') }} 2x"
                        loading="eager"
                        class="w-full h-full object-center object-cover transition-opacity duration-300 opacity-0">
                @endif
            </div>
        </div>

        <div class="sm:py-2 flex-grow self-center sm:self-stretch flex flex-col justify-between space-y-3">

            <div class="hidden sm:block self-start">
                <h2 class="text-2xl font-medium">
                    @auth <a href="{{ route('tales.edit', $tale) }}"> @endauth
                        <span class="shadow-title">{{ $tale->title }}</span>
                    @auth </a> @endauth
                </h2>
                @if ($tale->year)
                    <div class="mt-1">{{ $tale->year }}</div>
                @endif
            </div>

            <div>
                @if ($tale->director)
                    <strong>Reżyseria:</strong>
                    <a href="{{ route('artists.show', $tale->director) }}"
                        class="inline-flex items-center">
                        {{ $tale->director->name }}
                        @if ($tale->director->appearances > 1)
                            <small class="ml-1.5 w-4.5 h-4.5 text-2xs inline-flex items-center justify-center bg-yellow-300 text-yellow-800 rounded-full shadow-md">
                                {{ $tale->director->appearances }}
                            </small>
                        @endif
                    </a>
                    <br>
                @endif

                @if ($tale->lyricists->count() > 0)
                    <strong>Słowa:</strong>
                    @foreach ($tale->lyricists as $lyricist)
                        <a href="{{ route('artists.show', $lyricist) }}"
                            class="inline-flex items-center">
                            {{ $lyricist->name }}@if (! $loop->last && $lyricist->appearances <= 1),@endif
                            @if ($lyricist->appearances > 1)
                                <small class="ml-1.5 w-4.5 h-4.5 text-2xs inline-flex items-center justify-center bg-yellow-300 text-yellow-800 rounded-full shadow-md -mr-1">
                                    {{ $lyricist->appearances }}
                                </small>
                            @endif
                        </a>
                        @if (! $loop->last && $lyricist->appearances > 1),@endif
                    @endforeach
                    <br>
                @endif

                @if ($tale->composers->count() > 0)
                    <strong>Muzyka:</strong>
                    @foreach ($tale->composers as $composer)
                        <a href="{{ route('artists.show', $composer) }}"
                            class="inline-flex items-center">
                            {{ $composer->name }}@if (! $loop->last && $composer->appearances <= 1),@endif
                            @if ($composer->appearances > 1)
                                <small class="ml-1.5 w-4.5 h-4.5 text-2xs inline-flex items-center justify-center bg-yellow-300 text-yellow-800 rounded-full shadow-md -mr-1">
                                    {{ $composer->appearances }}
                                </small>
                            @endif
                        </a>
                        @if (! $loop->last && $composer->appearances > 1),@endif
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    @if ($tale->actors->count())
        <div class="w-full flex flex-col items-center space-y-3">
            <h3 class="text-xl font-medium shadow-subtitle">
                Obsada
            </h3>
            <div class="w-full md:w-5/6 xl:w-2/3 flex flex-col space-y-2.5">
                @foreach ($tale->actors as $actor)
                    <a href="{{ route('artists.show', $actor) }}"
                        class="w-full h-14 flex items-center bg-gray-50 dark:bg-gray-900 rounded-lg shadow-lg overflow-hidden">
                        <div class="relative flex-none bg-placeholder-artist w-14 h-14">
                            @if ($actor->photo())
                                <div class="bg-gray-400 dark:bg-gray-800 bg-center bg-cover absolute -inset-px"
                                    style="background-image: url(&quot;{{ $actor->photo_placeholder }}&quot;)">
                                    <img src="{{ $actor->photo('112') }}"
                                        srcset="
                                            {{ $actor->photo('56') }} 1x,
                                            {{ $actor->photo('84') }} 1.5x,
                                            {{ $actor->photo('112') }} 2x"
                                        loading="lazy"
                                        class="w-full h-full object-center object-cover transition-opacity duration-300 opacity-0">
                                </div>
                            @elseif ($actor->discogsPhoto())
                                <img src="{{ $actor->discogsPhoto('150') }}"
                                    class="w-14 h-14 object-cover">
                            @endif
                        </div>
                        <div class="flex-grow flex flex-col justify-between p-2 pl-3">
                            <div class="text-sm sm:text-base font-medium leading-tight">
                                {{ $actor->name }}
                            </div>
                            @if ($actor->pivot->characters)
                                <small>
                                    jako
                                    @foreach (explode('; ', $actor->pivot->characters) as $character)
                                        {{ $character }}@if ($loop->remaining > 1), @elseif ($loop->remaining > 0) i @endif
                                    @endforeach
                                </small>
                            @endif
                        </div>
                        <div class="flex-none pr-4">
                            @if ($actor->appearances > 1)
                                <small class="ml-1.5 w-6 h-6 text-xs inline-flex items-center justify-center bg-yellow-300 text-yellow-800 rounded-full shadow-md">
                                    {{ $actor->appearances }}
                                </small>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

@endsection
