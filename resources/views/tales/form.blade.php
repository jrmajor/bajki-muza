<?php /** @var App\Models\Tale $tale */ ?>

@php

  $data = [
    'credits' => $tale->credits->map(fn ($artist) => [
        'artist' => $artist->name,
        'type' => $artist->credit->type,
        'nr' => $artist->credit->nr,
    ]),
    'actors' => $tale->actors->map(fn ($actor) => [
        'artist' => $actor->name,
        'characters' => $actor->pivot->characters,
    ]),
  ];

@endphp

<form
  method="post"
  action="{{ $action == 'create' ? route('tales.store') : route('tales.update', $tale) }}"
  enctype="multipart/form-data"
  class="flex flex-col space-y-5"
  x-data="taleFormData(@encodedjson($data))" x-init="init($dispatch)">
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
      <label for="title" class="w-full font-medium pb-1 text-gray-700 dark:text-gray-400">Tytuł</label>
      <input type="text" name="title" value="{{ old('title', $tale->title) }}"
        class="w-full form-input">
    </div>
    <div class="w-full sm:w-1/2 flex space-x-5">
      <div class="w-1/2 items-stretch flex flex-col">
        <label for="year" class="w-full font-medium pb-1 text-gray-700 dark:text-gray-400">Rok</label>
        <input type="text" name="year" value="{{ old('year', $tale->year) }}"
          class="w-full form-input">
      </div>
      <div class="w-1/2 items-stretch flex flex-col">
        <label for="nr" class="w-full font-medium pb-1 text-gray-700 dark:text-gray-400">№</label>
        <input type="text" name="nr" value="{{ old('nr', $tale->nr) }}"
          class="w-full form-input">
      </div>
    </div>
  </div>

  <div class="flex flex-col">
    <span for="cover" class="w-full font-medium pb-1 text-gray-700 dark:text-gray-400">Okładka</span>
    <input type="hidden" name="remove_cover" :value="cover.remove ? 1 : 0">
    <div class="flex space-x-5">
      <label class="flex-grow h-10 flex items-center bg-white rounded-md border overflow-hidden cursor-pointer dark:border-gray-900 dark:bg-gray-800">
        <div class="flex-none bg-placeholder-cover w-10 h-10">
          @if ($tale->cover())
            <img src="{{ $tale->cover('128') }}"
              class="w-10 h-10 object-cover bg-cover"
              style="background-image: url(&quot;{{ $tale->cover_placeholder }}&quot;)"
              x-show="cover.file === '' && cover.remove == false">
          @endif
          <template x-if="cover.file !== ''">
            <img :src="cover.preview"
              class="w-10 h-10 object-cover">
          </template>
        </div>
        <span class="px-3 py-2">
          <span
            x-text="cover.file !== '' ? $refs.cover.files[0].name : 'Wybierz plik'">
            Wybierz plik
          </span>
          <small class="pl-1 text-xs font-medium"
            x-text="cover.file !== '' ? prettyBytes($refs.cover.files[0].size) : ''"></small>
        </span>
        <template x-if="cover.file !== ''">
          <button type="button" x-on:click="cover.file = ''" class="flex-none"></button>
        </template>
        <input type="file" name="cover" class="hidden"
          x-ref="cover" x-model="cover.file">
      </label>
      @if ($tale->exists)
        <template x-if="!cover.remove">
          <button type="button" x-on:click="cover.remove = true; cover.file = ''"
              class="flex-none px-3 py-2 bg-white rounded-md border font-medium text-sm
                hover:bg-red-100 hover:text-red-700
                active:bg-red-600 active:cover-red-600 active:text-red-100
                dark:bg-gray-800 dark:text-gray-100 dark:border-gray-900
                dark:hover:bg-red-800 dark:hover:text-red-100
                transition-colors duration-150">
            Usuń
          </button>
        </template>
        <template x-if="cover.remove">
          <button type="button" x-on:click="cover.remove = false"
              class="flex-none px-3 py-2 bg-red-600 text-red-100 rounded-md border-red-600 font-medium text-sm
                hover:bg-red-500 hover:border-red-500 hover:text-white
                active:bg-white active:text-black
                dark:active:bg-gray-800 dark:active:text-gray-100 dark:border-gray-900
                transition-colors duration-150">
            Nie usuwaj
          </button>
        </template>
      @endif
    </div>
  </div>

  <div class="flex flex-col">
    <div class="relative -space-y-1 mb-0.5">
      <span class="w-full font-medium text-gray-700 dark:text-gray-400">Solidna robota</span>
      <div class="w-full flex items-center space-x-2">
        <div class="w-1/2 px-1"><span class="w-full text-xs font-medium text-gray-700 dark:text-gray-400">Artysta</span></div>
        <div class="w-1/2 px-1"><span class="w-full text-xs font-medium text-gray-700 dark:text-gray-400">Robota</span></div>
        <div class="w-8 flex-0 px-1"><span class="w-full text-xs font-medium text-gray-700 dark:text-gray-400">№</span></div>
        <div class="w-5"></div>
      </div>
      <div class="absolute right-0 top-0 h-full flex items-center">
        <button type="button" x-on:click="addCredit()"
          class="w-5 h-5 flex items-center justify-center rounded-full bg-green-500 dark:bg-green-600 text-green-100 focus:bg-green-700">
          <span>+</span>
        </button>
      </div>
    </div>
    <div class="w-full flex flex-wrap space-y-1.5">
      <template x-for="(credit, index) in credits" :key="credit.key">
        <div class="w-full flex items-center space-x-2">
          <div class="w-1/2" :data-picker-name="'credits[' + index + '][artist]'" :data-picker-value="credit.artist">
            <x-artist-picker/>
          </div>
          <div class="w-1/2">
            <select :name="'credits[' + index + '][type]'" x-model="credit.type"
              class="w-full form-select">
              @foreach (CreditType::toArray() as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
              @endforeach
            </select>
          </div>
          <div class="w-8 flex-0 self-stretch flex items-center justify-center">
            <input type="text" :name="'credits[' + index + '][nr]'" x-model="credit.nr"
              class="w-8 px-1.5 py-1.5 text-center text-sm font-bold form-input">
          </div>
          <button type="button" x-on:click="removeArtist('credits', index)"
            class="flex-none w-5 h-5 flex items-center justify-center rounded-full bg-red-500 dark:bg-red-600 text-red-100 focus:bg-red-700">
            <span>-</span>
          </button>
        </div>
      </template>
    </div>
  </div>

  <div class="flex flex-col">
    <div class="relative -space-y-1 mb-0.5">
      <span class="w-full font-medium text-gray-700 dark:text-gray-400">Obsada</span>
      <div class="w-full flex items-center space-x-2">
        <div class="w-6 flex-0 px-1"><span class="w-full text-xs font-medium text-gray-700 dark:text-gray-400">№</span></div>
        <div class="w-1/2 px-1"><span class="w-full text-xs font-medium text-gray-700 dark:text-gray-400">Artysta</span></div>
        <div class="w-1/2 px-1"><span class="w-full text-xs font-medium text-gray-700 dark:text-gray-400">Postaci</span></div>
        <div class="w-5"></div>
      </div>
      <div class="absolute right-0 top-0 h-full flex items-center">
        <button type="button" x-on:click="addActor()"
          class="w-5 h-5 flex items-center justify-center rounded-full bg-green-500 dark:bg-green-600 text-green-100 focus:bg-green-700">
          <span>+</span>
        </button>
      </div>
    </div>
    <div class="w-full flex flex-wrap space-y-1.5">
      <template x-for="(actor, index) in actors" :key="actor.key">
        <div class="w-full flex items-center space-x-2"
          :class="{
            'opacity-0': actor.isDragged,
            'pt-12': actor.isDraggedOver === 'fromBelow' || actor.hasDeletedElement === 'above',
            'pb-12': actor.isDraggedOver === 'fromAbove' || actor.hasDeletedElement === 'below',
            'transition-all duration-300': !actor.noTransitions,
          }"
          draggable="true"
          x-on:dragstart="onDragStart($event, 'actors', index)" x-on:dragend="onDragEnd(actor)"
          x-on:dragover="onDragOver($event, 'actors', index);" x-on:dragleave="onDragLeave(actor)"
          x-on:drop="
            callback = onDrop($event, 'actors', index);
            $nextTick(() => $dispatch('artists-indexes-updated'));
          ">
          <div class="w-6 flex-0 self-stretch flex items-center justify-center">
            <input type="hidden" :name="'actors[' + index + '][credit_nr]'" :value="index + 1">
            <span class="text-sm font-bold text-gray-800 select-none" x-text="index + 1"></span>
          </div>
          <div class="w-1/2" :data-picker-name="'actors[' + index + '][artist]'" :data-picker-value="actor.artist">
            <x-artist-picker/>
          </div>
          <div class="w-1/2">
            <input type="text" :name="'actors[' + index + '][characters]'" x-model="actor.characters"
              class="w-full form-input">
          </div>
          <button type="button" x-on:click="removeArtist('actors', index)"
            class="flex-none w-5 h-5 flex items-center justify-center rounded-full bg-red-500 dark:bg-red-600 text-red-100 focus:bg-red-700">
            <span>-</span>
          </button>
        </div>
      </template>
    </div>
  </div>

  <button type="submit"
    class="self-center px-4 py-2 bg-white dark:bg-gray-800 text-sm font-medium rounded-full shadow-md">
    Zapisz
  </button>

</form>
