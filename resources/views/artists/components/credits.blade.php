@foreach ($artist->orderedCredits() as $type => $tales)
  <div class="w-full flex flex-col items-center space-y-3">
    <h3 class="text-xl font-medium shadow-subtitle">
      {{ Str::ucfirst($type) }}
    </h3>
    <div class="w-full md:w-5/6 xl:w-2/3 flex flex-col space-y-2.5">
      @foreach ($tales as $tale)
        <a href="{{ route('tales.show', $tale) }}"
          class="w-full h-13 flex items-center bg-gray-50 dark:bg-gray-900 rounded-lg shadow-lg overflow-hidden">
          <div class="flex-none bg-placeholder-cover w-13 h-13"
            @if ($tale->cover) style="background-image: url(&quot;{{ $tale->cover->placeholder() }}&quot;)" @endif
            >
            @if ($tale->cover)
              <x-responsive-image :image="$tale->cover"
                :size="13" :imageSize="60"
                alt="Okładka bajki {{ $tale->title }}"/>
            @endif
          </div>
          <div class="flex-grow p-2 pl-3 text-sm sm:text-base font-medium leading-tight">
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
