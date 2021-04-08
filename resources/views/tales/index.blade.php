@push('scripts')
  @livewireStyles
  @livewireScripts
@endpush

<div class="w-full">
  <div class="flex flex-col items-center space-y-5">
    <input type="search" wire:key="search" wire:model.debounce.500ms="search" autocomplete="off" autofocus
      class="w-full px-4 py-2 rounded-lg shadow-lg overflow-hidden bg-gray-50 dark:bg-gray-900
        border-none focus:outline-none focus:ring ring-yellow-kox ring-opacity-25">

    @forelse ($tales as $tale)
      <a href="{{ route('tales.show', $tale) }}" wire:key="{{ $tale->id }}"
        class="flex overflow-hidden items-center w-full h-32 bg-gray-50 rounded-lg shadow-lg dark:bg-gray-900">
        <div class="flex-none w-32 h-32 bg-placeholder-cover"
          @if ($tale->cover) style="background-image: url(&quot;{{ $tale->cover->placeholder() }}&quot;)" @endif
          >
          @if ($tale->cover)
            <x-responsive-image :image="$tale->cover" :size="32"
              alt="Okładka bajki {{ $tale->title }}"/>
          @endif
        </div>
        <div class="flex flex-col flex-grow justify-between self-stretch p-4 sm:p-5 sm:pl-6">
          <div class="text-lg font-medium leading-tight sm:text-xl">
            {{ $tale->title }}
          </div>
          <small>{{ $tale->year }}</small>
        </div>
      </a>
    @empty
      <div class="pt-6 text-lg font-medium leading-tight text-gray-700 dark:text-gray-400">Nie ma takich zwierząt.</div>
    @endforelse
  </div>

  @if ($tales->hasPages())
    <div class="flex flex-col items-center mt-8 w-full">
      {{ $tales->links() }}
    </div>
  @endif
</div>
