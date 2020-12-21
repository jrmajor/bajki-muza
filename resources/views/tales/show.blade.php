<?php /** @var App\Models\Tale $tale */ ?>

@extends('layouts.app')

@section('title', $tale->title)

@section('content')

  <div class="flex flex-col sm:flex-row items-center mb-6">

    <div class="sm:hidden text-center">
      @include ('tales.components.title')
    </div>

    <div class="mt-5 mb-2 sm:my-0 sm:mr-6 flex-none self-center shadow-lg rounded-lg overflow-hidden">
      <div class="bg-placeholder-cover w-48 h-48"
        @if ($tale->cover) style="background-image: url(&quot;{{ $tale->cover->placeholder() }}&quot;)" @endif
        >
        @if ($tale->cover)
          <img src="{{ $tale->cover->url(384) }}"
            srcset="
              {{ $tale->cover->url(192) }} 1x,
              {{ $tale->cover->url(288) }} 1.5x,
              {{ $tale->cover->url(384) }} 2x"
            loading="eager"
            alt="Okładka bajki {{ $tale->title }}"
            class="w-full h-full object-center object-cover transition-opacity duration-300 opacity-0">
        @endif
      </div>
    </div>

    <div class="sm:py-2 flex-grow self-center sm:self-stretch flex flex-col justify-between space-y-3">

      <div class="hidden sm:block self-start">
        @include('tales.components.title')
      </div>

      <div>
        @include('tales.components.main-credits')
      </div>

    </div>

  </div>

  <div class="w-full flex flex-col items-center space-y-8">

    @if ($tale->actors->count())
      @include('tales.components.actors')
    @endif

    @unless ($tale->customCredits()->isEmpty())
      @include('tales.components.custom-credits')
    @endif

  </div>

@endsection
