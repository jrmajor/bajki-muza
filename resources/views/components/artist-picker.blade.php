<div class="relative w-full"
    x-data="artistPickerData()"
    x-init="
        name = $el.parentElement.getAttribute('data-picker-name');
        value = $el.parentElement.getAttribute('data-picker-value');
    "
    x-on:artists-indexes-updated.window="
        name = $el.parentElement.getAttribute('data-picker-name');
    ">
    <input
        type="text" class="w-full form-input" autocomplete="off"
        x-model="value" :name="name"
        x-on:keydown.arrow-up="arrow('up')" x-on:keydown.arrow-down="arrow('down')"
        x-on:keydown.enter.prevent="enter" x-on:input="findArtists"
        x-on:focus="isOpen = shouldCloseOnBlur = true" x-on:blur="closeDropdown">
    <template x-if="isOpen && ! (search.length > 1 && artists.length === 0)">
        <ul class="absolute mt-2 z-50 py-1 w-full text-gray-800 bg-white rounded-md shadow-md border border-gray-300"
            x-on:mousedown="shouldCloseOnBlur = false">
            <template x-if="artists.length === 0">
                <li class="w-full px-3 py-1 text-gray-600">
                    Brak wyników
                </li>
            </template>
            <template x-for="(artist, index) in artists" x-key="artist">
                <li
                    x-on:mouseover="hovered = index" x-on:click="select(artist)"
                    class="select-none flex w-full px-3 py-1 text-gray-800 text-left justify-between"
                    :class="{ 'bg-cool-gray-100': hovered === index }">
                    <span x-text="artist"></span>
                    <span class="text-gray-800" x-text="value === artist ? '✓ ' : ''"></span>
                </li>
            </template>
        </ul>
    </template>
</div>
