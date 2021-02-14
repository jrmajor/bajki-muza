<div class="w-full flex flex-col items-center space-y-3">
  <h3 class="text-xl font-medium shadow-subtitle">
    Aktor
  </h3>
  <div class="w-full md:w-5/6 xl:w-2/3 flex flex-col space-y-2.5">
    @foreach ($artist->asActor as $tale)
      <a href="{{ route('tales.show', $tale) }}"
        class="w-full h-15 flex items-center bg-gray-50 dark:bg-gray-900 rounded-lg shadow-lg overflow-hidden">
        <div class="flex-none bg-placeholder-cover w-15 h-15"
          @if ($tale->cover) style="background-image: url(&quot;{{ $tale->cover->placeholder() }}&quot;)" @endif
          >
          @if ($tale->cover)
            <x-responsive-image :image="$tale->cover" :size="15"
              alt="Okładka bajki {{ $tale->title }}"/>
          @endif
        </div>
        <div class="flex-grow flex flex-col justify-between p-2 pl-3">
          <div class="text-sm sm:text-base font-medium leading-tight">
            {{ $tale->title }}
          </div>
          @if ($tale->credit->characters)
            <small>
              jako
              @foreach (explode('; ', $tale->credit->characters) as $character)
                {{ $character }}@if ($loop->remaining > 1), @elseif ($loop->remaining > 0) i @endif
              @endforeach
            </small>
          @endif
        </div>
        <div class="hidden sm:block flex-none pr-4">
          <small>{{ $tale->year }}</small>
        </div>
      </a>
    @endforeach
  </div>
</div>
