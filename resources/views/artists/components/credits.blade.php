@foreach ($artist->orderedCredits() as $type => $tales)
  <div class="flex flex-col gap-3 items-center w-full">
    <h3 class="text-xl font-medium shadow-subtitle">
      {{ Str::ucfirst($type) }}
    </h3>
    <div class="flex flex-col gap-2.5 w-full md:w-5/6 xl:w-2/3">
      @foreach ($tales as $tale)
        <a href="{{ route('tales.show', $tale) }}"
          class="flex overflow-hidden items-center w-full bg-gray-50 rounded-lg shadow-lg h-13 dark:bg-gray-900">
          <div class="flex-none bg-placeholder-cover size-13"
            @if ($tale->cover) style="background-image: url(&quot;{{ $tale->cover->placeholder() }}&quot;)" @endif
            >
            @if ($tale->cover)
              <x-responsive-image :image="$tale->cover"
                :size="13" :imageSize="60"
                alt="Okładka bajki {{ $tale->title }}"/>
            @endif
          </div>
          <div class="flex-grow p-2 pl-3 text-sm font-medium leading-tight sm:text-base">
            {{ $tale->title }}
          </div>
          <div class="pr-5">
            <small>{{ $tale->year }}</small>
          </div>
        </a>
      @endforeach
    </div>
  </div>
@endforeach
