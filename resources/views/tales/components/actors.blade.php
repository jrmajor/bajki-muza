<?php /** @var App\Models\Tale $tale */ ?>

<div class="flex flex-col gap-3 items-center w-full">
  <h3 class="text-xl font-medium shadow-subtitle">
    Obsada
  </h3>
  <div class="flex flex-col gap-2.5 w-full md:w-5/6 xl:w-2/3">
    @foreach ($tale->actors as $actor)
      <a href="{{ route('artists.show', $actor) }}"
        class="flex overflow-hidden items-center w-full h-14 bg-gray-50 rounded-lg shadow-lg dark:bg-gray-900">
        <div class="flex-none w-14 h-14 bg-placeholder-artist"
          @if ($actor->photo) style="background-image: url(&quot;{{ $actor->photo->facePlaceholder() }}&quot;)" @endif
          >
          @if ($actor->photo)
            <x-responsive-image :image="$actor->photo" :size="14"/>
          @elseif ($actor->discogsPhoto() && Auth::guest())
            <img src="{{ $actor->discogsPhoto('thumb') }}"
              class="object-cover w-14 h-14 filter grayscale">
          @endif
        </div>
        <div class="flex flex-col flex-grow justify-between p-2 pl-3">
          <div class="text-sm font-medium leading-tight sm:text-base">
            {{ $actor->name }}
          </div>
          @if ($actor->credit->characters)
            <small>
              jako
              @foreach (explode('; ', $actor->credit->characters) as $character)
                {{ $character }}@if ($loop->remaining > 1), @elseif ($loop->remaining > 0) i @endif
              @endforeach
            </small>
          @endif
        </div>
        <div class="flex-none pr-4">
          <x-appearances :count="$actor->appearances"/>
        </div>
      </a>
    @endforeach
  </div>
</div>
