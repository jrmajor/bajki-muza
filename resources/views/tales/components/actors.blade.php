<?php /** @var App\Models\Tale $tale */ ?>

<div class="w-full flex flex-col items-center space-y-3">
  <h3 class="text-xl font-medium shadow-subtitle">
    Obsada
  </h3>
  <div class="w-full md:w-5/6 xl:w-2/3 flex flex-col space-y-2.5">
    @foreach ($tale->actors as $actor)
      <a href="{{ route('artists.show', $actor) }}"
        class="w-full h-14 flex items-center bg-gray-50 dark:bg-gray-900 rounded-lg shadow-lg overflow-hidden">
        <div class="flex-none bg-placeholder-artist w-14 h-14"
          @if ($actor->photo()) style="background-image: url(&quot;{{ $actor->photo_face_placeholder }}&quot;)" @endif
          >
          @if ($actor->photo())
            <img src="{{ $actor->photo('112') }}"
              srcset="
                {{ $actor->photo('56') }} 1x,
                {{ $actor->photo('84') }} 1.5x,
                {{ $actor->photo('112') }} 2x"
              loading="lazy"
              class="w-14 h-14 object-cover transition-opacity duration-300 opacity-0">
          @elseif ($actor->discogsPhoto() && Auth::guest())
            <img src="{{ $actor->discogsPhoto('150') }}"
              class="w-14 h-14 object-cover">
          @endif
        </div>
        <div class="flex-grow flex flex-col justify-between p-2 pl-3">
          <div class="text-sm sm:text-base font-medium leading-tight">
            {{ $actor->name }}
          </div>
          @if ($actor->pivot->characters)
            <small>
              jako
              @foreach (explode('; ', $actor->pivot->characters) as $character)
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
