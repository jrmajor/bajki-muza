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
        enctype="multipart/form-data"
        class="flex flex-col space-y-5"
        x-data="artistFormData(@encodedjson($data))"
        x-init="
            $watch('photo.file', value => {
                setPhotoPreview($refs.photo.files)
                value !== '' ? photo.remove = false : ''
            });
        ">
        @method('put')
        @csrf

        @if ($errors->any())
            <ul class="text-red-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

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

        </div>

        <div class="flex flex-col">
            <span for="photo" class="w-full font-medium pb-1 text-gray-700">Zdjęcie</span>
            <input type="hidden" name="remove_photo" :value="photo.remove ? 1 : 0">
            <div class="flex space-x-5">
                <label class="flex-grow h-10 flex items-center bg-white rounded-md border overflow-hidden cursor-pointer">
                    <div class="flex-none bg-gray-400 w-10 h-10"
                        style="background-image: url(&quot;data:image/svg+xml;utf8,%3Csvg viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill-rule='evenodd' d='M10.998 8.504C8.682 10.98 7.27 14.744 7.87 19.9l.037.32-.178.266c-.83 1.247-.657 2.119-.304 2.667.412.642 1.144.973 1.576.973h.592l.22.55c1.473 3.681 3.167 6.38 4.932 8.144 1.76 1.76 3.55 2.556 5.256 2.556 1.707 0 3.496-.796 5.256-2.556 1.765-1.765 3.46-4.463 4.932-8.144l.22-.55H31c.432 0 1.164-.331 1.577-.973.352-.548.526-1.42-.305-2.667l-.178-.267.037-.32c.6-5.155-.813-8.92-3.13-11.394C26.672 6.014 23.35 4.75 20 4.75c-3.35 0-6.67 1.264-9.002 3.754zm22.905 11.288c.56-5.444-.96-9.637-3.624-12.484C27.58 4.424 23.775 3 20 3c-3.775 0-7.58 1.424-10.28 4.308-2.664 2.847-4.183 7.04-3.623 12.484-.984 1.647-.878 3.167-.146 4.306.58.9 1.525 1.51 2.432 1.708 1.486 3.584 3.222 6.35 5.123 8.25 1.99 1.99 4.2 3.069 6.494 3.069 2.293 0 4.504-1.079 6.494-3.069 1.9-1.9 3.637-4.666 5.123-8.25.907-.197 1.853-.808 2.431-1.708.733-1.139.84-2.659-.145-4.306z M17 22a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM26 22a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z M16.882 11.892c-.452 1.88-1.68 4.402-4.79 7.253a.875.875 0 11-1.183-1.29c2.89-2.649 3.911-4.876 4.272-6.372a6.635 6.635 0 00.175-2.086 9.324 9.324 0 01-.01-.175V9.22c-.002-.055-.008-.174.008-.293a.964.964 0 01.129-.368.896.896 0 01.767-.434c.302 0 .505.147.586.215.09.074.148.152.178.192.06.083.108.174.137.228.03.054.054.103.08.151.042.084.085.168.149.282a4.8 4.8 0 00.869 1.14c.816.786 2.327 1.654 5.217 1.543 2.107-.081 3.707.253 4.912.861 1.216.613 1.98 1.479 2.447 2.366.46.875.617 1.75.663 2.392a6.347 6.347 0 01-.01 1.028 2.811 2.811 0 01-.008.07l-.003.022v.008l-.001.003v.001c0 .001 0 .002-.866-.127l.865.129a.875.875 0 01-1.731-.254v-.003l.004-.031c.003-.031.008-.081.011-.148.008-.135.011-.333-.006-.572a4.369 4.369 0 00-.468-1.705c-.309-.588-.819-1.18-1.684-1.616-.876-.442-2.164-.748-4.057-.676-3.314.128-5.303-.88-6.498-2.029a5.494 5.494 0 01-.073-.071c-.024.12-.05.242-.081.368z' fill='%23858c99'/%3E%3C/svg%3E&quot;)">
                        @if ($artist->photo())
                            <img src="{{ $artist->photo('84') }}"
                                class="w-10 h-10 object-cover"
                                x-show="photo.file === '' && photo.remove == false">
                        @endif
                        <template x-if="photo.file !== ''">
                            <img :src="photo.preview"
                                class="w-10 h-10 object-cover">
                        </template>
                    </div>
                    <span class="px-3 py-2">
                        <span
                            x-text="photo.file !== '' ? $refs.photo.files[0].name : 'Wybierz plik'">
                            Wybierz plik
                        </span>
                        <small class="pl-1 text-xs font-medium"
                            x-text="photo.file !== '' ? fileSize($refs.photo.files[0].size) : ''"></small>
                    </span>
                    <template x-if="photo.file !== ''">
                        <button type="button" x-on:click="photo.file = ''" class="flex-none"></button>
                    </template>
                    <input type="file" name="photo" class="hidden"
                        x-ref="photo" x-model="photo.file">
                </label>
                <template x-if="!photo.remove">
                    <button type="button" x-on:click="photo.remove = true; photo.file = ''"
                            class="flex-none px-3 py-2 bg-white rounded-md border font-medium text-sm
                            hover:bg-red-100 hover:border-red-200 hover:text-red-700
                            active:bg-red-600 active:cover-red-600 active:text-red-100
                            transition-colors duration-150">
                        Usuń
                    </button>
                </template>
                <template x-if="photo.remove">
                    <button type="button" x-on:click="photo.remove = false"
                            class="flex-none px-3 py-2 bg-red-600 text-red-100 rounded-md border-red-600 font-medium text-sm
                            hover:bg-red-500 hover:border-red-500 hover:text-white
                            active:bg-white active:text-black transition-colors duration-150">
                        Nie usuwaj
                    </button>
                </template>
            </div>
        </div>

        <button type="submit"
            class="self-center px-4 py-2 bg-white text-sm font-medium rounded-full shadow-md">
            Zapisz
        </button>

        <div class="space-y-3 flex flex-col items-center" x-data="{ dimensions: {} }">
            <a href="https://www.google.com/search?q={{ urlencode($artist->name) }}&tbm=isch" target="_blank"
                class="mt-2 text-sm font-medium leading-4 shadow-link px-1">
                Wyszukiwanie obrazów →
            </a>

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

    </form>

@endsection
