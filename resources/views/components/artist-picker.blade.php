<div class="relative w-full"
  x-data="artistPickerData()" x-init="init"
  x-on:artists-indexes-updated.window="updateIndexes">
  <input
    type="text" class="w-full form-input" autocomplete="off"
    x-model="value" :name="name"
    x-on:keydown.arrow-up="arrow('up')" x-on:keydown.arrow-down="arrow('down')"
    x-on:keydown.enter.prevent="enter" x-on:input="findArtists"
    x-on:focus="isOpen = shouldCloseOnBlur = true" x-on:blur="closeDropdown">
  <template x-if="isOpen && ! (search.length > 1 && artists.length === 0)">
    <ul class="absolute z-50 py-1 mt-2 w-full text-gray-800 bg-white rounded-md border border-gray-300 shadow-md"
      x-on:mousedown="shouldCloseOnBlur = false">
      <template x-if="artists.length === 0">
        <li class="py-1 px-3 w-full text-gray-600">
          Brak wyników
        </li>
      </template>
      <template x-for="(artist, index) in artists" x-key="artist">
        <li
          x-on:mouseover="hovered = index" x-on:click="select(artist)"
          class="flex justify-between py-1 px-3 w-full text-left text-gray-800 select-none"
          :class="{ 'bg-gray-200': hovered === index }">
          <span x-text="artist"></span>
          <span class="text-gray-800" x-text="value === artist ? '✓ ' : ''"></span>
        </li>
      </template>
    </ul>
  </template>
</div>
