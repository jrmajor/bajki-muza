@php

    $data = [
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
    enctype="multipart/form-data"
    class="flex flex-col space-y-5"
    x-data="taleFormData(@encodedjson($data))"
    x-init="
        $watch('cover.file', value => {
            setCoverPreview($refs.cover.files)
            value != '' ? cover.remove = 0 : ''
        })
    ">
    @method($action == 'create' ? 'post' : 'put')
    @csrf

    @if ($errors->any())
        <ul class="text-red-700">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

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
        <span for="cover" class="w-full font-medium pb-1 text-gray-700">Okładka</span>
        <input type="hidden" name="remove_cover" x-model="cover.remove">
        <div class="flex space-x-5">
            <label class="flex-grow h-10 flex items-center bg-white rounded-md border overflow-hidden cursor-pointer">
                <div class="flex-none bg-gray-400 w-10 h-10"
                    style="background-image: url(&quot;data:image/svg+xml;utf8,%3Csvg viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill-rule='evenodd' d='M20 34.5c8.008 0 14.5-6.492 14.5-14.5S28.008 5.5 20 5.5 5.5 11.992 5.5 20 11.992 34.5 20 34.5zm0 1.5c8.837 0 16-7.163 16-16S28.837 4 20 4 4 11.163 4 20s7.163 16 16 16z M20 24.5a4.5 4.5 0 100-9 4.5 4.5 0 000 9zm0 1.5a6 6 0 100-12 6 6 0 000 12z M21.25 20a1.25 1.25 0 11-2.5 0 1.25 1.25 0 012.5 0z M11.345 30.061l4.1-4.746c-.27-.23-.52-.481-.751-.75L9.98 28.623c.418.513.875.994 1.365 1.44zM30.06 11.344a13.34 13.34 0 00-1.436-1.363l-4.048 4.722c.261.225.505.47.73.731l4.754-4.09z' fill='%23858c99'/%3E%3C/svg%3E&quot;)">
                    @if ($tale->cover())
                        <img src="{{ $tale->cover('128') }}"
                            loading="lazy"
                            class="w-10 h-10 object-cover bg-cover"
                            style="background-image: url(&quot;{{ $tale->cover_placeholder }}&quot;)"
                            x-show="cover.file == '' && cover.remove == 0">
                    @endif
                    <template x-if="cover.file != ''">
                        <img :src="cover.preview"
                            class="w-10 h-10 object-cover">
                    </template>
                </div>
                <span class="px-3 py-2">
                    <span
                        x-text="cover.file != '' ? $refs.cover.files[0].name : 'Wybierz plik'">
                        Wybierz plik
                    </span>
                    <small class="pl-1 text-xs font-medium"
                        x-text="cover.file != '' ? fileSize($refs.cover.files[0].size) : ''"></small>
                </span>
                <template x-if="cover.file != ''">
                    <button type="button" x-on:click="cover.file = ''" class="flex-none"></button>
                </template>
                <input type="file" name="cover" class="hidden"
                    x-ref="cover" x-model="cover.file">
            </label>
            @if ($tale->exists)
                <template x-if="! cover.remove">
                    <button type="button" x-on:click="cover.remove = 1; cover.file = ''"
                            class="flex-none px-3 py-2 bg-white rounded-md border font-medium text-sm
                            hover:bg-red-100 hover:border-red-200 hover:text-red-700
                            active:bg-red-600 active:cover-red-600 active:text-red-100
                            transition-colors duration-150">
                        Remove cover
                    </button>
                </template>
                <template x-if="cover.remove">
                    <button type="button" x-on:click="cover.remove = 0"
                            class="flex-none px-3 py-2 bg-red-600 text-red-100 rounded-md border-red-600 font-medium text-sm
                            hover:bg-red-500 hover:border-red-500 hover:text-white
                            active:bg-white active:text-black transition-colors duration-150">
                        Don't remove cover
                    </button>
                </template>
            @endif
        </div>
    </div>

    <div class="flex flex-col space-y-2">
        <div class="flex flex-col">
            <label for="director" class="w-full font-medium pb-1 text-gray-700">Reżyser</label>
            <div data-picker-name="director" data-picker-value="{{ old('director', optional($tale->director)->name) }}">
                <x-artist-picker/>
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
                                <td class="p-1" :data-picker-name="'lyricists[' + index + '][artist]'" :data-picker-value="lyricist.artist">
                                    <x-artist-picker/>
                                </td>
                                <td class="pl-1 py-1">
                                    <div class="w-full flex items-center">
                                        <button type="button" x-on:click="removeArtist('lyricists', index)"
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
                                    <button type="button" x-on:click="addArtist('lyricists')"
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
                                <td class="p-1" :data-picker-name="'composers[' + index + '][artist]'" :data-picker-value="composer.artist">
                                    <x-artist-picker/>
                                </td>
                                <td class="pl-1 py-1">
                                    <div class="w-full flex items-center">
                                        <button type="button" x-on:click="removeArtist('composers', index)"
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
                                    <button type="button" x-on:click="addArtist('composers')"
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
                                <td class="p-1" :data-picker-name="'actors[' + index + '][artist]'" :data-picker-value="actor.artist">
                                    <x-artist-picker/>
                                </td>
                            <td class="p-1">
                                <input type="text" :name="'actors[' + index + '][characters]'" x-model="actor.characters"
                                    class="form-input">
                            </td>
                            <td class="pl-1 py-1">
                                <div class="w-full flex items-center">
                                    <button type="button" x-on:click="removeArtist('actors', index)"
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
                                <button type="button" x-on:click="addArtist('actors')"
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

</form>
