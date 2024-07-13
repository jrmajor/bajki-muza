<?php /** @var App\Models\Tale $tale */ ?>

@extends('layouts.app-classic')

@section('title', $tale->title)

@section('meta')
  @if($tale->cover)
    <meta property="og:image" content="{{ $tale->cover->url(384) }}">
    <meta name="twitter:image" content="{{ $tale->cover->url(384) }}">
  @endif
@endsection

@section('content')

  <div class="flex flex-col items-center mb-6 sm:flex-row">

    <div class="text-center sm:hidden">
      @include ('tales.components.title')
    </div>

    <div class="overflow-hidden flex-none self-center mt-5 mb-2 rounded-lg shadow-lg sm:my-0 sm:mr-6">
      <div class="size-48 bg-placeholder-cover"
        @if ($tale->cover) style="background-image: url(&quot;{{ $tale->cover->placeholder() }}&quot;)" @endif
      >
        @if ($tale->cover)
          <x-responsive-image :image="$tale->cover"
            size="full" :imageSize="192" loading="eager"
            alt="Okładka bajki {{ $tale->title }}"/>
        @endif
      </div>
    </div>

    <div class="flex flex-col flex-grow gap-3 justify-between self-center sm:py-2 sm:self-stretch">

      <div class="hidden self-start sm:block">
        @include('tales.components.title')
      </div>

      <div>
        @include('tales.components.main-credits')
      </div>

    </div>

  </div>

  <div class="flex flex-col gap-8 items-center w-full">

    @if ($tale->actors->count())
      @include('tales.components.actors')
    @endif

    @unless ($tale->customCredits()->isEmpty())
      @include('tales.components.custom-credits')
    @endif

  </div>

@endsection
