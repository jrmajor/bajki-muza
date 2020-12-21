@push('scripts')
  @livewireStyles
  @livewireScripts
@endpush

<div class="w-full">
  <div class="space-y-3 flex flex-col items-center">
    <input type="search" wire:key="search" wire:model.debounce.100ms="search" autocomplete="off" autofocus
      class="w-full px-4 py-2 rounded-lg shadow-lg overflow-hidden bg-gray-50 dark:bg-gray-900
        border-none focus:outline-none focus:ring ring-yellow-kox ring-opacity-25">

    @forelse ($artists as $artist)
      <a href="{{ route('artists.show', $artist) }}" wire:key="{{ $artist->id }}"
        class="w-full h-12 sm:h-14 flex items-center bg-gray-50 dark:bg-gray-900 rounded-lg shadow-lg overflow-hidden">
        <div class="flex-none bg-placeholder-artist w-12 h-12 sm:w-14 sm:h-14"
          @if ($artist->photo) style="background-image: url(&quot;{{ $artist->photo->facePlaceholder() }}&quot;)" @endif
          >
          @if ($artist->photo)
            <img src="{{ $artist->photo->url(112) }}"
              srcset="
                {{ $artist->photo->url(56) }} 1x,
                {{ $artist->photo->url(84) }} 1.5x,
                {{ $artist->photo->url(112) }} 2x"
              loading="lazy"
              class="w-12 h-12 sm:w-14 sm:h-14 object-cover transition-opacity duration-300 opacity-0">
          @elseif ($artist->discogsPhoto() && Auth::guest())
            <img src="{{ $artist->discogsPhoto('150') }}"
              class="w-12 h-12 sm:w-14 sm:h-14 object-cover">
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
    <div class="w-full flex flex-col items-center mt-8">
      {{ $artists->links() }}
    </div>
  @endif
</div>
