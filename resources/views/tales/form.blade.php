@php

    $data = [
        'route' => route('ajax.artists'),

        'director' => old('director', optional($tale->director)->name),

        'lyricists' => collect($tale->lyricists)
            ->map(fn ($lyricist) => ['artist' => $lyricist->name, 'credit_nr' => $lyricist->pivot->credit_nr]),

        'composers' => collect($tale->composers)
            ->map(fn ($composer) => ['artist' => $composer->name, 'credit_nr' => $composer->pivot->credit_nr]),

        'actors' => collect($tale->actors)
            ->map(function ($actor) {
                return [
                    'artist' => $actor->name,
                    'credit_nr' => $actor->pivot->credit_nr,
                    'characters' => $actor->pivot->characters,
                ];
            }),
    ];

@endphp

<form
    method="post"
    action="{{ $action == 'create' ? route('tales.store') : route('tales.update', $tale) }}"
    x-data="taleFormData(@encodedjson($data))" x-init="init()">
    @method($action == 'create' ? 'post' : 'put')
    @csrf

    <div class="flex flex-col space-y-5">
        <div class="flex flex-col space-y-2 sm:flex-row sm:space-y-0 sm:space-x-5">
            <div class="w-full sm:w-1/2 flex flex-col">
                <label for="title" class="w-full font-medium pb-1 text-gray-700">Tytuł</label>
                <input type="text" name="title" value="{{ old('title', $tale->title) }}"
                    class="w-full form-input">
            </div>
            <div class="w-full sm:w-1/2 flex space-x-5">
                <div class="w-1/2 items-stretch flex flex-col">
                    <label for="year" class="w-full font-medium pb-1 text-gray-700">Rok</label>
                    <input type="text" name="year" value="{{ old('year', $tale->year) }}"
                        class="w-full form-input">
                </div>
                <div class="w-1/2 items-stretch flex flex-col">
                    <label for="nr" class="w-full font-medium pb-1 text-gray-700">№</label>
                    <input type="text" name="nr" value="{{ old('nr', $tale->nr) }}"
                        class="w-full form-input">
                </div>
            </div>
        </div>

        <div class="flex flex-col">
            <label for="cover" class="w-full font-medium pb-1 text-gray-700">Okładka w LastFM</label>
            <input type="text" name="cover" value="{{ old('cover', $tale->cover) }}"
                class="w-full form-input">
        </div>

        <div class="flex flex-col space-y-2">
            <div class="flex flex-col">
                <label for="director" class="w-full font-medium pb-1 text-gray-700">Reżyser</label>
                <div class="relative w-full"
                    x-on:mousedown.away="director.isOpen = false">
                    <input
                        type="text" name="director"
                        class="w-full form-input" autocomplete="off"
                        x-model="director.artist" x-on:focus="director.isOpen = true" x-on:input="findDirector()">
                    <template x-if="director.isOpen && director.artist.length >= 2">
                        <div class="absolute mt-2 z-50 py-1 w-full text-gray-800 bg-white rounded-md shadow-md border border-gray-300">
                            <template x-if="director.people.length == 0">
                                <div class="w-full px-3 py-1 text-gray-600">
                                    Brak wyników
                                </div>
                            </template>
                            <template x-for="person in director.people" x-key="person">
                                <button
                                    x-on:click.prevent="director.artist = person; director.isOpen = false"
                                    class="flex w-full px-3 py-1 text-gray-800 text-left justify-between hover:bg-cool-gray-100">
                                    <span x-text="person"></span>
                                    <span class="text-gray-400" x-text="director.artist == person ? '✓ ' : ''"></span>
                                </button>
                            </template>
                        </div>
                    </template>
                </div>
            </div>

            <div class="flex flex-col space-y-2 md:flex-row md:space-y-0 md:space-x-5">
                <div class="w-full md:w-1/2 flex flex-col">
                    <span class="w-full -mb-1.5 font-medium text-gray-700">Słowa</span>
                    <table>
                        <thead>
                            <tr>
                                <td class="px-1"><span class="w-full text-xs font-medium text-gray-700">№</span></td>
                                <td class="px-1"><span class="w-full text-xs font-medium text-gray-700">Artysta</span></td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(lyricist, index) in lyricists" :key="index">
                                <tr>
                                    <td class="pr-1 py-1">
                                        <input type="text" :name="'lyricists[' + index + '][credit_nr]'" x-model="lyricist.credit_nr"
                                            class="w-10 text-sm form-input">
                                    </td>
                                    <td class="p-1">
                                        <div class="relative w-full"
                                            x-on:mousedown.away="lyricist.isOpen = false">
                                            <input
                                                type="text" :name="'lyricists[' + index + '][artist]'"
                                                class="w-full form-input" autocomplete="off"
                                                x-model="lyricist.artist" x-on:focus="lyricist.isOpen = true" x-on:input="findArtists(lyricist)">
                                            <template x-if="lyricist.isOpen && lyricist.artist.length >= 2">
                                                <div class="absolute mt-2 z-50 py-1 w-full text-gray-800 bg-white rounded-md shadow-md border border-gray-300">
                                                    <template x-if="lyricist.people.length == 0">
                                                        <div class="w-full px-3 py-1 text-gray-600">
                                                            Brak wyników
                                                        </div>
                                                    </template>
                                                    <template x-for="person in lyricist.people" x-key="person">
                                                        <button
                                                            x-on:click.prevent="lyricist.artist = person; lyricist.isOpen = false"
                                                            class="flex w-full px-3 py-1 text-gray-800 text-left justify-between hover:bg-cool-gray-100">
                                                            <span x-text="person"></span>
                                                            <span class="text-gray-400" x-text="lyricist.artist == person ? '✓ ' : ''"></span>
                                                        </button>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>
                                    </td>
                                    <td class="pl-1 py-1">
                                        <div class="w-full flex items-center">
                                            <button
                                                x-on:click="removeArtist($event, 'lyricists', index)"
                                                class="mt-1 w-5 h-5 flex items-center justify-center bg-red-500 text-red-100 rounded-full focus:bg-red-700">
                                                <span>-</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <tr>
                                <td colspan="2"></td>
                                <td class="pl-1 py-1">
                                    <div class="w-full flex items-center">
                                        <button
                                            x-on:click="addArtist($event, 'lyricists')"
                                            class="mt-1 w-5 h-5 flex items-center justify-center bg-green-500 text-green-100 rounded-full focus:bg-green-700">
                                            <span>+</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="w-full md:w-1/2 flex flex-col">
                    <span class="w-full -mb-1.5 font-medium text-gray-700">Muzyka</span>
                    <table>
                        <thead>
                            <tr>
                                <td class="px-1"><span class="w-full text-xs font-medium text-gray-700">№</span></td>
                                <td class="px-1"><span class="w-full text-xs font-medium text-gray-700">Artysta</span></td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(composer, index) in composers" :key="index">
                                <tr>
                                    <td class="pr-1 py-1">
                                        <input type="text" :name="'composers[' + index + '][credit_nr]'" x-model="composer.credit_nr"
                                            class="w-10 text-sm form-input">
                                    </td>
                                    <td class="p-1">
                                        <div class="relative w-full"
                                            x-on:mousedown.away="composer.isOpen = false">
                                            <input
                                                type="text" :name="'composers[' + index + '][artist]'"
                                                class="w-full form-input" autocomplete="off"
                                                x-model="composer.artist" x-on:focus="composer.isOpen = true" x-on:input="findArtists(composer)">
                                            <template x-if="composer.isOpen && composer.artist.length >= 2">
                                                <div class="absolute mt-2 z-50 py-1 w-full text-gray-800 bg-white rounded-md shadow-md border border-gray-300">
                                                    <template x-if="composer.people.length == 0">
                                                        <div class="w-full px-3 py-1 text-gray-600">
                                                            Brak wyników
                                                        </div>
                                                    </template>
                                                    <template x-for="person in composer.people" x-key="person">
                                                        <button
                                                            x-on:click.prevent="composer.artist = person; composer.isOpen = false"
                                                            class="flex w-full px-3 py-1 text-gray-800 text-left justify-between hover:bg-cool-gray-100">
                                                            <span x-text="person"></span>
                                                            <span class="text-gray-400" x-text="composer.artist == person ? '✓ ' : ''"></span>
                                                        </button>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>
                                    </td>
                                    <td class="pl-1 py-1">
                                        <div class="w-full flex items-center">
                                            <button
                                                x-on:click="removeArtist($event, 'composers', index)"
                                                class="mt-1 w-5 h-5 flex items-center justify-center bg-red-500 text-red-100 rounded-full focus:bg-red-700">
                                                <span>-</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <tr>
                                <td colspan="2"></td>
                                <td class="pl-1 py-1">
                                    <div class="w-full flex items-center">
                                        <button
                                            x-on:click="addArtist($event, 'composers')"
                                            class="mt-1 w-5 h-5 flex items-center justify-center bg-green-500 text-green-100 rounded-full focus:bg-green-700">
                                            <span>+</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex flex-col">
                <span class="w-full -mb-1.5 font-medium text-gray-700">Obsada</span>
                <table>
                    <thead>
                        <tr>
                            <td class="px-1"><span class="w-full text-xs font-medium text-gray-700">№</span></td>
                            <td class="px-1"><span class="w-full text-xs font-medium text-gray-700">Artysta</span></td>
                            <td class="px-1"><span class="w-full text-xs font-medium text-gray-700">Postaci</span></td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(actor, index) in actors" :key="index">
                            <tr>
                                <td class="pr-1 py-1">
                                    <input type="text" :name="'actors[' + index + '][credit_nr]'" x-model="actor.credit_nr"
                                        class="w-10 text-sm form-input">
                                </td>
                                <td class="p-1">
                                    <div class="relative w-full"
                                        x-on:mousedown.away="actor.isOpen = false">
                                        <input
                                            type="text" :name="'actors[' + index + '][artist]'"
                                            class="w-full form-input" autocomplete="off"
                                            x-model="actor.artist" x-on:focus="actor.isOpen = true" x-on:input="findArtists(actor)">
                                        <template x-if="actor.isOpen && actor.artist.length >= 2">
                                            <div class="absolute mt-2 z-50 py-1 w-full text-gray-800 bg-white rounded-md shadow-md border border-gray-300">
                                                <template x-if="actor.people.length == 0">
                                                    <div class="w-full px-3 py-1 text-gray-600">
                                                        Brak wyników
                                                    </div>
                                                </template>
                                                <template x-for="person in actor.people" x-key="person">
                                                    <button
                                                        x-on:click.prevent="actor.artist = person; actor.isOpen = false"
                                                        class="flex w-full px-3 py-1 text-gray-800 text-left justify-between hover:bg-cool-gray-100">
                                                        <span x-text="person"></span>
                                                        <span class="text-gray-400" x-text="actor.artist == person ? '✓ ' : ''"></span>
                                                    </button>
                                                </template>
                                            </div>
                                        </template>
                                    </div>
                                </td>
                                <td class="p-1">
                                    <input type="text" :name="'actors[' + index + '][characters]'" x-model="actor.characters"
                                        class="form-input">
                                </td>
                                <td class="pl-1 py-1">
                                    <div class="w-full flex items-center">
                                        <button
                                            x-on:click="removeArtist($event, 'actors', index)"
                                            class="mt-1 w-5 h-5 flex items-center justify-center bg-red-500 text-red-100 rounded-full focus:bg-red-700">
                                            <span>-</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <tr>
                            <td colspan="3"></td>
                            <td class="pl-1 py-1">
                                <div class="w-full flex items-center">
                                    <button
                                        x-on:click="addArtist($event, 'actors')"
                                        class="mt-1 w-5 h-5 flex items-center justify-center bg-green-500 text-green-100 rounded-full focus:bg-green-700">
                                        <span>+</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <button type="submit"
            class="self-center px-4 py-2 bg-white text-sm font-medium rounded-full shadow-md">
            Zapisz
        </button>
    </div>

</form>
