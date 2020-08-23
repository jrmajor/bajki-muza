@extends('layouts.app')

@section('content')

    <h2 class="mb-2 text-2xl font-medium">
    	<a href="{{ route('artists.show', $artist) }}">
    		{{ $artist->name }}
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
                            x-model="discogs.value" x-on:focus="discogs.isOpen = true" x-on:input="findPeople('discogs')">
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
                            x-model="filmPolski.value" x-on:focus="filmPolski.isOpen = true" x-on:input="findPeople('filmPolski')">
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
                            x-model="wikipedia.value" x-on:focus="wikipedia.isOpen = true" x-on:input="findPeople('wikipedia')">
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
        </div>

        <button type="submit"
            class="self-center px-4 py-2 bg-white text-sm font-medium rounded-full shadow-md">
            Zapisz
        </button>
    </form>

@endsection
