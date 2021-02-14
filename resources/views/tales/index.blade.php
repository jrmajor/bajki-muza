@push('scripts')
  @livewireStyles
  @livewireScripts
@endpush

<div class="w-full">
  <div class="space-y-5 flex flex-col items-center">
    <input type="search" wire:key="search" wire:model.debounce.500ms="search" autocomplete="off" autofocus
      class="w-full px-4 py-2 rounded-lg shadow-lg overflow-hidden bg-gray-50 dark:bg-gray-900
        border-none focus:outline-none focus:ring ring-yellow-kox ring-opacity-25">

    @forelse ($tales as $tale)
      <a href="{{ route('tales.show', $tale) }}" wire:key="{{ $tale->id }}"
        class="w-full h-32 flex items-center bg-gray-50 dark:bg-gray-900 rounded-lg shadow-lg overflow-hidden">
        <div class="flex-none bg-placeholder-cover w-32 h-32"
          @if ($tale->cover) style="background-image: url(&quot;{{ $tale->cover->placeholder() }}&quot;)" @endif
          >
          @if ($tale->cover)
            <x-responsive-image :image="$tale->cover" :size="32"
              alt="Okładka bajki {{ $tale->title }}"/>
          @endif
        </div>
        <div class="flex-grow self-stretch flex flex-col justify-between p-4 sm:p-5 sm:pl-6">
          <div class="text-lg sm:text-xl font-medium leading-tight">
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
    <div class="w-full flex flex-col items-center mt-8">
      {{ $tales->links() }}
    </div>
  @endif
</div>
