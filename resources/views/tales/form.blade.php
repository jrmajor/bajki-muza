<?php /** @var App\Models\Tale $tale */ ?>

@php

  $data = new Illuminate\Support\Js([
      'discogs' => old('discogs', $tale->discogs),

      'credits' => $tale->orderedCredits()->map(fn ($artist) => [
          'artist' => $artist->name,
          'type' => $artist->credit->type,
          'as' => $artist->credit->as,
          'nr' => $artist->credit->nr,
      ]),

      'actors' => $tale->actors->map(fn ($actor) => [
          'artist' => $actor->name,
          'characters' => $actor->credit->characters,
      ]),
  ]);

@endphp

<form
  method="post"
  action="{{ $action === 'create' ? route('tales.store') : route('tales.update', $tale) }}"
  enctype="multipart/form-data"
  class="flex flex-col gap-5"
  x-data="taleFormData({{ $data }})" x-init="init($dispatch)">
  @method($action === 'create' ? 'post' : 'put')
  @csrf

  @if ($errors->any())
    <ul class="text-red-700">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  @endif

  <div class="flex flex-col gap-2 sm:flex-row sm:gap-5">
    <div class="flex flex-col w-full sm:w-1/2">
      <label for="title" class="pb-1 w-full font-medium text-gray-700 dark:text-gray-400">Tytuł</label>
      <input type="text" name="title" value="{{ old('title', $tale->title) }}"
        class="w-full form-input">
    </div>
    <div class="flex gap-5 w-full sm:w-1/2">
      <div class="flex flex-col items-stretch w-1/2">
        <label for="year" class="pb-1 w-full font-medium text-gray-700 dark:text-gray-400">Rok</label>
        <input type="text" name="year" value="{{ old('year', $tale->year) }}"
          class="w-full form-input">
      </div>
      <div class="flex flex-col items-stretch w-1/2">
        <label for="nr" class="pb-1 w-full font-medium text-gray-700 dark:text-gray-400">№</label>
        <input type="text" name="nr" value="{{ old('nr', $tale->nr) }}"
          class="w-full form-input">
      </div>
    </div>
  </div>

  <div class="flex flex-col">
    <label for="year" class="pb-1 w-full font-medium text-gray-700 dark:text-gray-400">Discogs</label>
    <div class="flex gap-5 items-center">
      <input type="text" name="discogs"
        x-model="discogs" x-on:input="updatedDiscogs()"
        class="w-full form-input">
      @if ($tale->discogs)
        <div class="flex-grow-0">
          <a href="{{ $tale->discogs_url }}" target="_blank">
            <x-icons.discogs class="h-5 fill-current"/>
          </a>
        </div>
      @endif
    </div>
  </div>

  <div class="flex flex-col">
    <span for="cover" class="pb-1 w-full font-medium text-gray-700 dark:text-gray-400">Okładka</span>
    <input type="hidden" name="remove_cover" :value="cover.remove ? 1 : 0">
    <div class="flex gap-5">
      <label class="flex overflow-hidden flex-grow items-center h-10 bg-white rounded-md border cursor-pointer dark:border-gray-900 dark:bg-gray-800">
        <div class="flex-none size-10 bg-placeholder-cover">
          @if ($tale->cover)
            <img src="{{ $tale->cover->url(128) }}"
              class="object-cover size-10 bg-cover"
              style="background-image: url(&quot;{{ $tale->cover->placeholder() }}&quot;)"
              x-show="cover.file === '' && cover.remove == false">
          @endif
          <template x-if="cover.file !== ''">
            <img :src="cover.preview"
              class="object-cover size-10">
          </template>
        </div>
        <span class="py-2 px-3">
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
      <div class="flex gap-2 items-center w-full">
        <div class="px-1 w-1/2 flex-shrink-1"><span class="w-full text-xs font-medium text-gray-700 dark:text-gray-400">Artysta</span></div>
        <div class="flex-shrink-0 px-1 w-1/4"><span class="w-full text-xs font-medium text-gray-700 dark:text-gray-400">Robota</span></div>
        <div class="flex-shrink-0 px-1 w-1/4"><span class="w-full text-xs font-medium text-gray-700 dark:text-gray-400">Jako</span></div>
        <div class="px-1 w-8 flex-0"><span class="w-full text-xs font-medium text-gray-700 dark:text-gray-400">№</span></div>
        <div class="w-5"></div>
      </div>
      <div class="flex absolute top-0 right-0 items-center h-full">
        <button type="button" x-on:click="addCredit()"
          class="flex justify-center items-center size-5 text-green-100 bg-green-500 rounded-full dark:bg-green-600 focus:bg-green-700">
          <span>+</span>
        </button>
      </div>
    </div>
    <div class="flex flex-wrap gap-1.5 w-full">
      <template x-for="(credit, index) in credits" :key="credit.key">
        <div class="flex items-center gap-2 w-full">
          <div class="w-1/2 flex-shrink-1" :data-picker-name="'credits[' + index + '][artist]'" :data-picker-value="credit.artist">
            <x-artist-picker/>
          </div>
          <div class="flex-shrink-0 w-1/4">
            <select :name="'credits[' + index + '][type]'" x-model="credit.type"
              class="w-full form-select">
              @foreach (CreditType::cases() as $type)
                <option value="{{ $type->value }}">{{ $type->label() }}</option>
              @endforeach
            </select>
          </div>
          <div class="flex-shrink-0 w-1/4">
            <input type="text" :name="'credits[' + index + '][as]'" x-model="credit.as" class="w-full form-input">
          </div>
          <div class="flex justify-center items-center self-stretch w-8 flex-0">
            <input type="text" :name="'credits[' + index + '][nr]'" x-model="credit.nr"
              class="w-8 px-1.5 py-1.5 text-center text-sm font-bold form-input">
          </div>
          <button type="button" x-on:click="removeArtist('credits', index)"
            class="flex flex-none justify-center items-center size-5 text-red-100 bg-red-500 rounded-full dark:bg-red-600 focus:bg-red-700">
            <span>-</span>
          </button>
        </div>
      </template>
    </div>
  </div>

  <div class="flex flex-col">
    <div class="relative -space-y-1 mb-0.5">
      <span class="w-full font-medium text-gray-700 dark:text-gray-400">Obsada</span>
      <div class="flex items-center gap-2 w-full">
        <div class="px-1 w-6 flex-0"><span class="w-full text-xs font-medium text-gray-700 dark:text-gray-400">№</span></div>
        <div class="px-1 w-1/2"><span class="w-full text-xs font-medium text-gray-700 dark:text-gray-400">Artysta</span></div>
        <div class="px-1 w-1/2"><span class="w-full text-xs font-medium text-gray-700 dark:text-gray-400">Postaci</span></div>
        <div class="w-5"></div>
      </div>
      <div class="flex absolute top-0 right-0 items-center h-full">
        <button type="button" x-on:click="addActor()"
          class="flex justify-center items-center size-5 text-green-100 bg-green-500 rounded-full dark:bg-green-600 focus:bg-green-700">
          <span>+</span>
        </button>
      </div>
    </div>
    <div class="w-full flex gap-1.5 flex-wrap">
      <template x-for="(actor, index) in actors" :key="actor.key">
        <div class="flex items-center gap-2 w-full"
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
          <div class="flex justify-center items-center self-stretch w-6 flex-0">
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
            class="flex flex-none justify-center items-center size-5 text-red-100 bg-red-500 rounded-full dark:bg-red-600 focus:bg-red-700">
            <span>-</span>
          </button>
        </div>
      </template>
    </div>
  </div>

  <div class="flex flex-col w-full">
    <label for="notes" class="pb-1 w-full font-medium text-gray-700 dark:text-gray-400">Notatki</label>
    <textarea type="text" name="notes" rows="5" class="w-full form-input">{{ old('notes', $tale->notes) }}</textarea>
  </div>

  <button type="submit"
    class="self-center py-2 px-4 text-sm font-medium bg-white rounded-full shadow-md dark:bg-gray-800">
    Zapisz
  </button>

</form>
