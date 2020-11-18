<?php /** @var App\Models\Tale $tale */ ?>

@extends('layouts.app')

@section('title', $tale->title)

@section('content')

  <div class="flex flex-col sm:flex-row items-center mb-6">

    <div class="sm:hidden text-center">
      <h2 class="text-2xl font-medium">
        @auth <a href="{{ route('tales.edit', $tale) }}"> @endauth
          @foreach (explode(' ', $tale->title) as $word)
            <span class="shadow-title">{{ $word }}</span>
          @endforeach
        @auth </a> @endauth
      </h2>
      @if ($tale->year)
        <div class="mt-1.5">{{ $tale->year }}</div>
      @endif
    </div>

    <div class="mt-5 mb-2 sm:my-0 sm:mr-6 flex-none self-center shadow-lg rounded-lg overflow-hidden">
      <div class="bg-placeholder-cover w-48 h-48"
        @if ($tale->cover()) style="background-image: url(&quot;{{ $tale->cover_placeholder }}&quot;)" @endif
        >
        @if ($tale->cover())
          <img src="{{ $tale->cover('384') }}"
            srcset="
              {{ $tale->cover('192') }} 1x,
              {{ $tale->cover('288') }} 1.5x,
              {{ $tale->cover('384') }} 2x"
            loading="eager"
            alt="Okładka bajki {{ $tale->title }}"
            class="w-full h-full object-center object-cover transition-opacity duration-300 opacity-0">
        @endif
      </div>
    </div>

    <div class="sm:py-2 flex-grow self-center sm:self-stretch flex flex-col justify-between space-y-3">

      <div class="hidden sm:block self-start">
        <h2 class="text-2xl font-medium">
          @auth <a href="{{ route('tales.edit', $tale) }}"> @endauth
            <span class="shadow-title">{{ $tale->title }}</span>
          @auth </a> @endauth
        </h2>
        @if ($tale->year)
          <div class="mt-1">{{ $tale->year }}</div>
        @endif
      </div>

      <div>
        @unless ($tale->creditsFor(CreditType::directing())->isEmpty())
          <strong>Reżyseria:</strong>
          @foreach ($tale->creditsFor(CreditType::directing()) as $director)
            <a href="{{ route('artists.show', $director) }}"
              class="inline-flex items-center">
              {{ $director->name }}@if (! $loop->last && $director->appearances <= 1),@endif
              <x-appearances :count="$director->appearances" size="small"/>
            </a>
            @if (! $loop->last && $director->appearances > 1),@endif
          @endforeach
          <br>
        @endif

        @unless ($tale->creditsFor(CreditType::text())->isEmpty())
          <strong>Słowa:</strong>
          @foreach ($tale->creditsFor(CreditType::text()) as $lyricist)
            <a href="{{ route('artists.show', $lyricist) }}"
              class="inline-flex items-center">
              {{ $lyricist->name }}@if (! $loop->last && $lyricist->appearances <= 1),@endif
              <x-appearances :count="$lyricist->appearances" size="small"/>
            </a>
            @if (! $loop->last && $lyricist->appearances > 1),@endif
          @endforeach
          <br>
        @endif

        @unless ($tale->creditsFor(CreditType::music())->isEmpty())
          <strong>Muzyka:</strong>
          @foreach ($tale->creditsFor(CreditType::music()) as $composer)
            <a href="{{ route('artists.show', $composer) }}"
              class="inline-flex items-center">
              {{ $composer->name }}@if (! $loop->last && $composer->appearances <= 1),@endif
              <x-appearances :count="$composer->appearances" size="small"/>
            </a>
            @if (! $loop->last && $composer->appearances > 1),@endif
          @endforeach
        @endif
      </div>
    </div>
  </div>

  @if ($tale->actors->count())
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
  @endif

@endsection
