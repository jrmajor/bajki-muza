@push('scripts')
  @livewireStyles
  @livewireScripts
@endpush

<div class="w-full">
  <div class="flex flex-col gap-3 items-center">
    <input type="search" wire:key="search" wire:model.debounce.100ms="search" autocomplete="off" autofocus
      class="w-full px-4 py-2 rounded-lg shadow-lg overflow-hidden bg-gray-50 dark:bg-gray-900
        border-none focus:outline-none focus:ring ring-brand-primary ring-opacity-25">

    @forelse ($artists as $artist)
      <a href="{{ route('artists.show', $artist) }}" wire:key="{{ $artist->id }}"
        class="flex overflow-hidden items-center w-full h-12 bg-gray-50 rounded-lg shadow-lg sm:h-14 dark:bg-gray-900">
        <div class="flex-none w-12 h-12 bg-placeholder-artist sm:w-14 sm:h-14"
          @if ($artist->photo) style="background-image: url(&quot;{{ $artist->photo->facePlaceholder() }}&quot;)" @endif
          >
          @if ($artist->photo)
            <x-responsive-image :image="$artist->photo" :size="14"
              class="w-12 h-12 sm:w-14 sm:h-14"/>
          @elseif ($artist->discogsPhoto() && Auth::guest())
            <img src="{{ $artist->discogsPhoto('thumb') }}"
              class="object-cover w-12 h-12 sm:w-14 sm:h-14 grayscale">
          @endif
        </div>
        <div class="flex-grow p-2 pl-3">
          <span class="flex-shrink-0 font-medium">{{ $artist->name }}</span>
        </div>
        <div class="flex-none pr-4">
          <x-appearances :count="$artist->appearances"/>
        </div>
      </a>
    @empty
      <div class="pt-6 text-lg font-medium leading-tight text-gray-700 dark:text-gray-400">Nie ma takich zwierząt.</div>
    @endforelse
  </div>

  @if ($artists->hasPages())
    <div class="flex flex-col items-center mt-8 w-full">
      {{ $artists->links() }}
    </div>
  @endif
</div>
