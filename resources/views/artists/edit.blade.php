@extends('layouts.app')

@section('content')

    <h2 class="text-2xl font-medium leading-7">
        <a href="{{ route('artists.show', $artist) }}">
            @foreach (explode(' ', $artist->name) as $word)
                <span class="shadow-title px-1.5 @if (! $loop->last) -mx-1.5 @else -ml-1.5 @endif">{{ $word }}</span>
            @endforeach
        </a>
    </h2>

    @php

        $data = [
            'route' => url('ajax'),
            'discogs' => old('discogs', $artist->discogs),
            'filmpolski' => old('filmpolski', $artist->filmpolski),
            'wikipedia' => old('wikipedia', $artist->wikipedia),
        ];

    @endphp

    <form
        method="post" action="{{ route('artists.update', $artist) }}"
        class="flex flex-col space-y-5"
        x-data="artistFormData(@encodedjson($data))">
        @method('put')
        @csrf

        <div class="flex flex-col">
            <label for="name" class="w-full font-medium pb-1 text-gray-700">Imię i nazwisko</label>
            <input type="text" name="name" value="{{ old('name', $artist->name) }}"
                class="w-full form-input">
        </div>

        <div class="flex flex-col space-y-2 sm:flex-row sm:space-y-0 sm:space-x-5">
            <div class="w-full sm:w-1/2 flex space-x-5">
                <div class="w-1/2 items-stretch flex flex-col">
                    <label for="year" class="w-full font-medium pb-1 text-gray-700">Discogs</label>
                    <div class="relative w-full"
                        x-on:mousedown.away="discogs.isOpen = false">
                        <input
                            type="text" name="discogs" value="{{ old('discogs', $artist->discogs) }}"
                            class="w-full form-input" autocomplete="off"
                            x-model="discogs.value" x-on:focus="discogs.isOpen = true" x-on:input.debounce="findPeople('discogs')">
                        <template x-if="discogs.isOpen && discogs.value.length >= 5">
                            <div class="absolute mt-2 z-50 py-1 w-full text-gray-800 bg-white rounded-md shadow-md border border-gray-300">
                                <template x-if="discogs.people.length == 0">
                                    <div class="w-full px-3 py-1 text-gray-600">
                                        Brak wyników
                                    </div>
                                </template>
                                <template x-for="person in discogs.people" x-key="person.id">
                                    <button
                                        x-on:click.prevent="discogs.value = person.id; discogs.isOpen = false"
                                        class="flex w-full px-3 py-1 text-gray-800 text-left justify-between hover:bg-cool-gray-100">
                                        <span x-text="person.name"></span>
                                        <span class="text-gray-400" x-text="discogs.value == person.id ? '✓ ' : ''"></span>
                                    </button>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="w-1/2 items-stretch flex flex-col">
                    <label for="year" class="w-full font-medium pb-1 text-gray-700">Film Polski</label>
                    <div class="relative w-full"
                        x-on:mousedown.away="filmPolski.isOpen = false">
                        <input
                            type="text" name="filmpolski" value="{{ old('filmpolski', $artist->filmpolski) }}"
                            class="w-full form-input" autocomplete="off"
                            x-model="filmPolski.value" x-on:focus="filmPolski.isOpen = true" x-on:input.debounce="findPeople('filmPolski')">
                        <template x-if="filmPolski.isOpen && filmPolski.value.length >= 5">
                            <div class="absolute mt-2 z-50 py-1 w-full text-gray-800 bg-white rounded-md shadow-md border border-gray-300">
                                <template x-if="filmPolski.people.length == 0">
                                    <div class="w-full px-3 py-1 text-gray-600">
                                        Brak wyników
                                    </div>
                                </template>
                                <template x-for="person in filmPolski.people" x-key="person.id">
                                    <button
                                        x-on:click.prevent="filmPolski.value = person.id; filmPolski.isOpen = false"
                                        class="flex w-full px-3 py-1 text-gray-800 text-left justify-between hover:bg-cool-gray-100">
                                        <span x-text="person.name"></span>
                                        <span class="text-gray-400" x-text="filmPolski.value == person.id ? '✓ ' : ''"></span>
                                    </button>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="w-full sm:w-1/2 flex flex-col">
                <label for="title" class="w-full font-medium pb-1 text-gray-700">Wikipedia</label>
                <div class="relative w-full"
                    x-on:mousedown.away="wikipedia.isOpen = false">
                    <input
                        type="text" name="wikipedia" value="{{ old('wikipedia', $artist->wikipedia) }}"
                        class="w-full form-input" autocomplete="off"
                        x-model="wikipedia.value" x-on:focus="wikipedia.isOpen = true" x-on:input.debounce="findPeople('wikipedia')">
                    <template x-if="wikipedia.isOpen && wikipedia.value.length >= 5">
                        <div class="absolute mt-2 z-50 py-1 w-full text-gray-800 bg-white rounded-md shadow-md border border-gray-300">
                            <template x-if="wikipedia.people.length == 0">
                                <div class="w-full px-3 py-1 text-gray-600">
                                    Brak wyników
                                </div>
                            </template>
                            <template x-for="person in wikipedia.people" x-key="person.id">
                                <button
                                    x-on:click.prevent="wikipedia.value = person.id; wikipedia.isOpen = false"
                                    class="flex w-full px-3 py-1 text-gray-800 text-left justify-between hover:bg-cool-gray-100">
                                    <span x-text="person.name"></span>
                                    <span class="text-gray-400" x-text="wikipedia.value == person.id ? '✓ ' : ''"></span>
                                </button>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
                        </template>
                    </div>
            </div>
        </div>

        <button type="submit"
            class="self-center px-4 py-2 bg-white text-sm font-medium rounded-full shadow-md">
            Zapisz
        </button>

    </form>

    <div class="mt-6 space-y-3 flex flex-col items-center" x-data="{ dimensions: {} }">
        <h3 class="text-xl font-medium leading-6 shadow-subtitle px-1">
            Zdjęcia
        </h3>

        <div class="w-full flex flex-wrap justify-around">
            @foreach ($artist->discogsPhotos() as $photo)
                @php $ref = 'discogs_'.$loop->iteration @endphp
                <div class="group relative m-1.5 shadow-lg rounded-lg overflow-hidden">
                    <img class="h-40" src="{{ $photo['uri'] }}" x-ref="{{ $ref }}"
                        x-on:load="dimensions.{{ $ref }} = $event.target.naturalWidth + '×' + $event.target.naturalHeight">
                    <div class="absolute top-0 right-0 pl-8 pb-2
                        opacity-0 group-hover:opacity-100 transition-all duration-300"
                        style="background-image: radial-gradient(ellipse farthest-side at top right, rgba(0, 0, 0, .4), transparent);">
                        <span class="text-2xs text-white px-2"
                            x-text="$refs.{{ $ref }}.complete
                                        ? $refs.{{ $ref }}.naturalWidth + '×' + $refs.{{ $ref }}.naturalHeight
                                        : dimensions.{{ $ref }}">
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="w-full flex flex-wrap justify-around">
            @foreach ($artist->filmPolskiPhotos() as $title => $movie)
                @foreach ($movie['photos'] as $photo)
                    @php $ref = 'filmpolski_'.$loop->parent->iteration.'_'.$loop->iteration @endphp
                    <div class="group relative m-1.5 shadow-lg rounded-lg overflow-hidden">
                        <img class="h-40" src="https://filmpolski.pl{{ $photo }}" x-ref="{{ $ref }}"
                            x-on:load="dimensions.{{ $ref }} = $event.target.naturalWidth + '×' + $event.target.naturalHeight">
                        @if ($title !== 'main' && $title !== '')
                            <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent
                                opacity-0 group-hover:opacity-75 transition-all duration-300"></div>
                        @endif
                        <div class="absolute bottom-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="flex flex-col p-2 text-white">
                                <small class="text-xs">{{ $movie['year'] }}</small>
                                <span class="text-sm font-medium leading-tight">{{ $title !== 'main' ? Str::title($title) : '' }}</span>
                            </div>
                        </div>
                        <div class="absolute top-0 right-0 pl-8 pb-2
                            opacity-0 group-hover:opacity-100 transition-all duration-300"
                            style="background-image: radial-gradient(ellipse farthest-side at top right, rgba(0,0,0,.4), transparent);">
                            <span class="text-2xs text-white px-2"
                                x-text="$refs.{{ $ref }}.complete
                                            ? $refs.{{ $ref }}.naturalWidth + '×' + $refs.{{ $ref }}.naturalHeight
                                            : dimensions.{{ $ref }}">
                            </span>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>

@endsection
