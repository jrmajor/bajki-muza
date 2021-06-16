@extends('layouts.app')

@section('content')

  <div class="text-center">
    <h2 class="text-2xl font-medium">
      <a href="{{ route('artists.show', $artist) }}">
        @foreach (explode(' ', $artist->name) as $word)
          <span class="shadow-title">{{ $word }}</span>
        @endforeach
      </a>
    </h2>
  </div>

  @php

    $data = [
      'discogs' => old('discogs', $artist->discogs) ?? '',
      'filmpolski' => old('filmpolski', $artist->filmpolski) ?? '',
      'wikipedia' => old('wikipedia', $artist->wikipedia) ?? '',

      'photo' => [
        'uri' => $artist->photo?->originalUrl(),
        'source' => old('photo_source', $artist->photo?->source),
        'crop' => old('photo_crop', $artist->photo?->crop()),
        'grayscale' => (bool) old('photo_grayscale', $artist->photo?->grayscale ?? true),
      ],
    ];

  @endphp

  <form
    method="post" action="{{ route('artists.update', $artist) }}"
    enctype="multipart/form-data"
    class="flex flex-col gap-5"
    x-data="artistFormData(@encodedjson($data))">
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
      <label for="name" class="pb-1 w-full font-medium text-gray-700 dark:text-gray-400">Imię i nazwisko</label>
      <input type="text" name="name" value="{{ old('name', $artist->name) }}"
        class="w-full form-input">
    </div>

    <div class="flex flex-col">
      <label for="genetivus" class="pb-1 w-full font-medium text-gray-700 dark:text-gray-400">Dopełniacz</label>
      <input type="text" name="genetivus" value="{{ old('genetivus', $artist->genetivus) }}"
        class="w-full form-input">
    </div>

    <div class="flex flex-col gap-2 sm:flex-row sm:gap-5">

      <div class="flex gap-5 w-full sm:w-1/2">

        <div class="flex flex-col items-stretch w-1/2">
          <label for="year" class="pb-1 w-full font-medium text-gray-700 dark:text-gray-400">Discogs</label>
          <div class="relative w-full">
            <input
              type="text" class="w-full form-input" autocomplete="off"
              x-model="discogs.value" name="discogs"
              value="{{ old('discogs', $artist->discogs) }}"
              x-on:keydown.arrow-up="arrow('discogs', 'up')" x-on:keydown.arrow-down="arrow('discogs', 'down')"
              x-on:keydown.enter.prevent="enter('discogs')" x-on:input.debounce="findPeople('discogs')"
              x-on:focus="discogs.isOpen = discogs.shouldCloseOnBlur = true" x-on:blur="closeDropdown('discogs')">
            <template x-if="discogs.isOpen && discogs.value.length >= 5">
              <ul class="absolute z-50 py-1 mt-2 w-full text-gray-800 bg-white rounded-md border border-gray-300 shadow-md"
                x-on:mousedown="discogs.shouldCloseOnBlur = false">
                <template x-if="discogs.people.length === 0">
                  <li class="py-1 px-3 w-full text-gray-600">
                    Brak wyników
                  </li>
                </template>
                <template x-for="(person, index) in discogs.people" x-key="person.id">
                  <li
                    x-on:mouseover="discogs.hovered = index" x-on:click="select('discogs', person)"
                    class="flex justify-between py-1 px-3 w-full text-left text-gray-800 select-none"
                    :class="{ 'bg-gray-200': discogs.hovered === index }">
                    <span x-text="person.name"></span>
                    <span class="text-gray-400" x-text="discogs.value === person.id ? '✓ ' : ''"></span>
                  </li>
                </template>
              </ul>
            </template>
          </div>
        </div>

        <div class="flex flex-col items-stretch w-1/2">
          <label for="year" class="pb-1 w-full font-medium text-gray-700 dark:text-gray-400">Film Polski</label>
          <div class="relative w-full">
            <input
              type="text" class="w-full form-input" autocomplete="off"
              x-model="filmPolski.value" name="filmpolski"
              value="{{ old('filmpolski', $artist->filmpolski) }}"
              x-on:keydown.arrow-up="arrow('filmPolski', 'up')" x-on:keydown.arrow-down="arrow('filmPolski', 'down')"
              x-on:keydown.enter.prevent="enter('filmPolski')" x-on:input.debounce="findPeople('filmPolski')"
              x-on:focus="filmPolski.isOpen = filmPolski.shouldCloseOnBlur = true" x-on:blur="closeDropdown('filmPolski')">
            <template x-if="filmPolski.isOpen && filmPolski.value.length >= 5">
              <ul class="absolute z-50 py-1 mt-2 w-full text-gray-800 bg-white rounded-md border border-gray-300 shadow-md"
                x-on:mousedown="filmPolski.shouldCloseOnBlur = false">
                <template x-if="filmPolski.people.length === 0">
                  <li class="py-1 px-3 w-full text-gray-600">
                    Brak wyników
                  </li>
                </template>
                <template x-for="(person, index) in filmPolski.people" x-key="person.id">
                  <li
                    x-on:mouseover="filmPolski.hovered = index" x-on:click="select('filmPolski', person)"
                    class="flex justify-between py-1 px-3 w-full text-left text-gray-800 select-none"
                    :class="{ 'bg-gray-200': filmPolski.hovered === index }">
                    <span x-text="person.name"></span>
                    <span class="text-gray-400" x-text="filmPolski.value === person.id ? '✓ ' : ''"></span>
                  </li>
                </template>
              </ul>
            </template>
          </div>
        </div>

      </div>

      <div class="flex flex-col w-full sm:w-1/2">
        <label for="title" class="pb-1 w-full font-medium text-gray-700 dark:text-gray-400">Wikipedia</label>
        <div class="relative w-full">
          <input
            type="text" class="w-full form-input" autocomplete="off"
            x-model="wikipedia.value" name="wikipedia"
            value="{{ old('wikipedia', $artist->wikipedia) }}"
            x-on:keydown.arrow-up="arrow('wikipedia', 'up')" x-on:keydown.arrow-down="arrow('wikipedia', 'down')"
            x-on:keydown.enter.prevent="enter('wikipedia')" x-on:input.debounce="findPeople('wikipedia')"
            x-on:focus="wikipedia.isOpen = wikipedia.shouldCloseOnBlur = true" x-on:blur="closeDropdown('wikipedia')">
          <template x-if="wikipedia.isOpen && wikipedia.value.length >= 5">
            <ul class="absolute z-50 py-1 mt-2 w-full text-gray-800 bg-white rounded-md border border-gray-300 shadow-md"
              x-on:mousedown="wikipedia.shouldCloseOnBlur = false">
              <template x-if="wikipedia.people.length === 0">
                <li class="py-1 px-3 w-full text-gray-600">
                  Brak wyników
                </li>
              </template>
              <template x-for="(person, index) in wikipedia.people" x-key="person.id">
                <li
                  x-on:mouseover="wikipedia.hovered = index" x-on:click="select('wikipedia', person)"
                  class="flex justify-between py-1 px-3 w-full text-left text-gray-800 select-none"
                  :class="{ 'bg-gray-200': wikipedia.hovered === index }">
                  <span x-text="person.name"></span>
                  <span class="text-gray-400" x-text="wikipedia.value === person.id ? '✓ ' : ''"></span>
                </li>
              </template>
            </ul>
          </template>
        </div>
      </div>

    </div>

    <div class="flex flex-col gap-3">

      <div class="flex flex-col">
        <span for="photo" class="pb-1 w-full font-medium text-gray-700 dark:text-gray-400">Zdjęcie</span>
        <input type="hidden" name="remove_photo" :value="photo.activePicker === 'remove' ? 1 : 0">
        <input type="hidden" name="photo_uri" :value="photo.activePicker === 'uri' ? photo.uri : ''">
        <div class="flex gap-5">
          <label class="flex overflow-hidden flex-grow items-center h-10 bg-white rounded-md border cursor-pointer dark:border-gray-900 dark:bg-gray-800">
            <div class="flex-none w-10 h-10 bg-placeholder-artist">
              <template x-if="photo.uri !== null">
                <img :src="photo.uri" class="object-cover w-10 h-10">
              </template>
            </div>
            <span class="py-2 px-3">
              <span x-text="photo.labelText()">Wybierz plik</span>
              <small x-text="photo.size()" class="pl-1 text-xs font-medium"></small>
            </span>
            <input type="file" name="photo" class="hidden" x-on:change="photo.fileSelected($el.files)">
          </label>
          <template x-if="photo.activePicker !== 'remove'">
            <button type="button" x-on:click="photo.removePhoto()"
                class="flex-none px-3 py-2 bg-white rounded-md border font-medium text-sm
                hover:bg-red-100 hover:text-red-700
                active:bg-red-600 active:cover-red-600 active:text-red-100
                dark:bg-gray-800 dark:text-gray-100 dark:border-gray-900
                dark:hover:bg-red-800 dark:hover:text-red-100
                transition-colors duration-150">
              Usuń
            </button>
          </template>
          <template x-if="photo.activePicker === 'remove'">
            <button type="button" x-on:click="photo.resetPickerToCurrent()"
                class="flex-none px-3 py-2 bg-red-600 text-red-100 rounded-md border-red-600 font-medium text-sm
                hover:bg-red-500 hover:border-red-500 hover:text-white
                active:bg-white active:text-black
                dark:active:bg-gray-800 dark:active:text-gray-100 dark:border-gray-900
                transition-colors duration-150">
              Nie usuwaj
            </button>
          </template>
        </div>
      </div>

      <div class="flex flex-row gap-5 items-center">
        <div class="flex flex-row flex-grow gap-3 items-center">
          <label for="photo_source" class="flex-none text-sm font-medium text-gray-700 dark:text-gray-400">Źródło</label>
          <input type="text" value="{{ old('photo_source', $artist->photo?->source) }}"
            name="photo_source" x-model="photo.source"
            class="py-1 px-2 w-full text-sm form-input">
        </div>

        <div class="flex flex-row flex-none gap-1 items-center">
          <label for="photo-grayscale" class="flex-none text-sm font-medium text-gray-700 dark:text-gray-400">Cz-B.</label>
          <input type="hidden" id="photo-grayscale-hidden" name="photo_grayscale" value="0">
          <input type="checkbox" id="photo-grayscale" name="photo_grayscale"
            {{ old('photo_grayscale', $artist->photo?->grayscale ?? true) ? 'checked' : '' }}
            x-model="photo.grayscale" value="1">
        </div>
      </div>

      <div
        x-show="photo.activePicker !== 'remove' && photo.uri !== null"
        class="flex gap-5 justify-center items-center"
      >
        <div x-ref="faceCropper" class="flex justify-end max-w-1/2"></div>
        <table>
          <tr>
            <td class="px-1 text-sm font-medium text-right">x:</td>
            <td x-text="photo.crop.face.x" class="px-1"></td>
          </tr>
          <tr>
            <td class="px-1 text-sm font-medium text-right">y:</td>
            <td x-text="photo.crop.face.y" class="px-1"></td>
          </tr>
        </table>
        <table>
          <tr>
            <td class="px-1 text-sm font-medium text-right">width:</td>
            <td x-text="photo.crop.face.width" class="px-1"></td>
          </tr>
          <tr>
            <td class="px-1 text-sm font-medium text-right">height:</td>
            <td x-text="photo.crop.face.height" class="px-1"></td>
          </tr>
          <input type="hidden" name="photo_crop[face][x]" :value="photo.crop.face.x">
          <input type="hidden" name="photo_crop[face][y]" :value="photo.crop.face.y">
          <input type="hidden" name="photo_crop[face][size]" :value="photo.crop.face.width">
        </table>
      </div>

      <div
        x-show="photo.activePicker !== 'remove' && photo.uri !== null"
        class="flex gap-5 justify-center items-center"
      >
        <div x-ref="imageCropper" class="flex justify-end max-w-1/2"></div>
        <table>
          <tr>
            <td class="px-1 text-sm font-medium text-right">x:</td>
            <td x-text="photo.crop.image.x" class="px-1"></td>
          </tr>
          <tr>
            <td class="px-1 text-sm font-medium text-right">y:</td>
            <td x-text="photo.crop.image.y" class="px-1"></td>
          </tr>
        </table>
        <table>
          <tr>
            <td class="px-1 text-sm font-medium text-right">width:</td>
            <td x-text="photo.crop.image.width" class="px-1"></td>
          </tr>
          <tr>
            <td class="px-1 text-sm font-medium text-right">height:</td>
            <td x-text="photo.crop.image.height" class="px-1"></td>
          </tr>
          <input type="hidden" name="photo_crop[image][x]" :value="photo.crop.image.x">
          <input type="hidden" name="photo_crop[image][y]" :value="photo.crop.image.y">
          <input type="hidden" name="photo_crop[image][width]" :value="photo.crop.image.width">
          <input type="hidden" name="photo_crop[image][height]" :value="photo.crop.image.height">
        </table>
      </div>

    </div>

    <button type="submit" class="self-center py-2 px-4 text-sm font-medium bg-white rounded-full shadow-md dark:bg-gray-800">
      Zapisz
    </button>

    <div class="flex flex-col gap-3 items-center">

      <div class="flex flex-col gap-2 items-center py-3 sm:flex-row sm:gap-5">
        <a
          href="https://www.google.com/search?q={{ urlencode($artist->name) }}&tbm=isch" target="_blank"
          class="text-sm font-medium"
        >
          <span class="shadow-link">Google</span> →
        </a>
        <a
          href="http://fototeka.fn.org.pl/pl/strona/wyszukiwarka.html?key={{
              urlencode(Str::afterLast($artist->name, ' ').' '.Str::beforeLast($artist->name, ' '))
            }}&search_type_in=osoba&filter[charakter][]=portret" target="_blank"
          class="text-sm font-medium"
        >
          <span class="shadow-link">Fototeka</span> →
        </a>
      </div>

      <div class="flex flex-wrap justify-around w-full">
        @foreach ($artist->discogsPhotos() as $photo)
          @php $ref = 'discogs_'.$loop->iteration @endphp
          <button
            class="group relative m-1.5 shadow-lg rounded-lg overflow-hidden focus:outline-none"
            type="button" x-on:click="photo.setPhotoUri('{{ $photo->uri }}', 'discogs')"
          >
            <img
              class="h-40" src="{{ $photo->uri }}" x-ref="{{ $ref }}"
              x-on:load="dimensions.{{ $ref }} = $event.target.naturalWidth + '×' + $event.target.naturalHeight"
            >
            <div
              class="absolute top-0 right-0 pl-8 pb-2
              opacity-0 group-hover:opacity-100 transition-all duration-300"
              style="background-image: radial-gradient(ellipse farthest-side at top right, rgba(0, 0, 0, .4), transparent);"
            >
              <span
                class="px-2 text-white text-2xs"
                x-text="$refs.{{ $ref }}.complete
                      ? $refs.{{ $ref }}.naturalWidth + '×' + $refs.{{ $ref }}.naturalHeight
                      : dimensions.{{ $ref }}">
              </span>
            </div>
            <div
              class="absolute inset-0 rounded-lg group-focus:inset-shadow-light
              transition-all duration-300"
              :class="{ 'inset-shadow-hard opacity-100': photo.activePicker === 'uri' && photo.uri === '{{ $photo->uri }}' }"
            >
            </div>
          </button>
        @endforeach
      </div>

      <div class="flex flex-wrap justify-around w-full">
        @foreach ($artist->filmPolskiPhotos() as $title => $movie)
          @foreach ($movie['photos'] as $photo)
            @php $ref = 'filmpolski_'.$loop->parent->iteration.'_'.$loop->iteration @endphp
            <button
              class="group relative m-1.5 shadow-lg rounded-lg overflow-hidden focus:outline-none"
              type="button" x-on:click="photo.setPhotoUri('https://filmpolski.pl{{ $photo }}', 'filmpolski')"
            >
              <img
                class="h-40" src="https://filmpolski.pl{{ $photo }}" x-ref="{{ $ref }}"
                x-on:load="dimensions.{{ $ref }} = $event.target.naturalWidth + '×' + $event.target.naturalHeight"
              >
              @if ($title !== 'main' && $title !== '')
                <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent opacity-0 group-hover:opacity-75 transition-all duration-300"></div>
              @endif
              <div class="absolute bottom-0 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                <div class="flex flex-col p-2 text-white">
                  <small class="text-xs">{{ $movie['year'] }}</small>
                  <span class="text-sm font-medium leading-tight">{{ $title !== 'main' ? Str::title($title) : '' }}</span>
                </div>
              </div>
              <div
                class="absolute top-0 right-0 pl-8 pb-2
                opacity-0 group-hover:opacity-100 transition-all duration-300"
                style="background-image: radial-gradient(ellipse farthest-side at top right, rgba(0,0,0,.4), transparent);"
              >
                <span
                  class="px-2 text-white text-2xs"
                  x-text="$refs.{{ $ref }}.complete
                        ? $refs.{{ $ref }}.naturalWidth + '×' + $refs.{{ $ref }}.naturalHeight
                        : dimensions.{{ $ref }}">
                </span>
              </div>
              <div
                class="absolute inset-0 rounded-lg group-focus:inset-shadow-light
                transition-all duration-300"
                :class="{ 'inset-shadow-hard opacity-100': photo.activePicker === 'uri' && photo.uri === 'https://filmpolski.pl{{ $photo }}' }"
              >
              </div>
            </button>
          @endforeach
        @endforeach
      </div>

    </div>

  </form>

@endsection
