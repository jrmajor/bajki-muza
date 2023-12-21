<div class="flex flex-col gap-3 items-center w-full">
  <h3 class="text-xl font-medium shadow-subtitle">
    Aktor
  </h3>
  <div class="flex flex-col gap-2.5 w-full md:w-5/6 xl:w-2/3">
    @foreach ($artist->asActor as $tale)
      <a href="{{ route('tales.show', $tale) }}"
        class="flex overflow-hidden items-center w-full bg-gray-50 rounded-lg shadow-lg h-15 dark:bg-gray-900">
        <div class="flex-none bg-placeholder-cover size-15"
          @if ($tale->cover) style="background-image: url(&quot;{{ $tale->cover->placeholder() }}&quot;)" @endif
          >
          @if ($tale->cover)
            <x-responsive-image :image="$tale->cover" :size="15"
              alt="Okładka bajki {{ $tale->title }}"/>
          @endif
        </div>
        <div class="flex flex-col flex-grow justify-between p-2 pl-3">
          <div class="text-sm font-medium leading-tight sm:text-base">
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
        <div class="hidden flex-none pr-4 sm:block">
          <small>{{ $tale->year }}</small>
        </div>
      </a>
    @endforeach
  </div>
</div>
