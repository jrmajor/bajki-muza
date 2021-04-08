<?php /** @var App\Models\Artist $artist */ ?>

@extends('layouts.app')

@section('title', $artist->name)

@section('meta')
  @if($artist->photo)
    <meta property="og:image" content="{{ $artist->photo->url(320) }}">
    <meta name="twitter:image" content="{{ $artist->photo->url(320) }}">
  @endif
@endsection

@section('content')

  <div class="flex flex-col sm:flex-row items-center mb-6">

    <div class="sm:hidden text-center">
      <h2 class="text-2xl font-medium">
        @auth
          <a href="{{ route('artists.edit', $artist) }}">
        @endauth
        @foreach (explode(' ', $artist->name) as $word)
          <span class="shadow-title">{{ $word }}</span>
        @endforeach
        @auth
          </a>
        @endauth
      </h2>
    </div>

    @if ($artist->photo)
      <div style="width: {{ $artist->photo->aspectRatio() * 10 }}rem"
        class="relative mt-5 mb-2 sm:my-0 sm:mr-6 -p-px flex-none self-center h-40 shadow-lg rounded-lg overflow-hidden">
        <div class="bg-gray-400 dark:bg-gray-800 bg-center bg-cover absolute -inset-px"
          style="background-image: url(&quot;{{ $artist->photo->placeholder() }}&quot;)">
          <x-responsive-image :image="$artist->photo"
            size="full" :imageSize="160" loading="eager"/> {{-- @todo add alt --}}
        </div>
      </div>
    @elseif ($artist->discogsPhoto() && Auth::guest())
      <div class="mt-5 mb-2 sm:my-0 sm:mr-6 flex-none self-center h-40 shadow-lg rounded-lg overflow-hidden">
        <img src="{{ $artist->discogsPhoto() }}" class="h-40 filter grayscale">
      </div>
    @endif

    <div class="
      flex flex-col flex-grow justify-between space-y-3
      @if ($artist->photo || $artist->discogsPhoto()) sm:py-2 @endif
      @if ($artist->wikipedia) self-stretch @endif
    ">

      <div class="
        hidden sm:block
        @if ($artist->photo || $artist->discogsPhoto() || $artist->wikipedia) self-start @else self-center @endif">
        <h2 class="text-2xl font-medium">
          @auth <a href="{{ route('artists.edit', $artist) }}"> @endauth
            <span class="shadow-title">{{ $artist->name }}</span>
          @auth </a> @endauth
        </h2>
      </div>

      @if ($artist->discogs || $artist->filmpolski || $artist->wikipedia)
        <div class="
          flex flex-col space-y-2
          @if ($artist->photo || $artist->discogsPhoto() || $artist->wikipedia) self-stretch @else self-center @endif
        ">
          @if ($artist->wikipedia)
            <div>
              {!! strip_tags($artist->wikipedia_extract) !!}
            </div>
          @endif

          <div class="self-center sm:self-start flex items-center space-x-5">
            @if ($artist->discogs)
              <a href="{{ $artist->discogs_url }}" target="_blank">
                <x-icons.discogs class="fill-current h-5"/>
              </a>
            @endif
            @if ($artist->filmpolski)
              <a href="{{ $artist->filmpolski_url }}" target="_blank"
                class="font-medium">
                <span class="text-[#566ea1] dark:text-[#7393d9]">FILM</span>{{--
            --}}<span class="text-[#010101] dark:text-[#eeeeee]">POLSKI</span>{{--
            --}}<span class="text-[#566ea1] dark:text-[#7393d9]">.PL</span>
              </a>
            @endif
            @if ($artist->wikipedia)
              <a href="{{ $artist->wikipedia_url }}" target="_blank">
                <x-icons.wikipedia class="fill-current h-4"/>
              </a>
            @endif
          </div>
        </div>
      @endif

    </div>

  </div>

  <div class="w-full space-y-6">

    @unless ($artist->asActor->isEmpty())
      @include('artists.components.as-actor')
    @endif

    @unless ($artist->credits->isEmpty())
      @include('artists.components.credits')
    @endif

  </div>

  @auth
    @if ($artist->appearances() === 0)
      <form method="post" action="{{ route('artists.destroy', $artist) }}">
        @csrf
        @method('delete')
        <button class="px-3 py-1.5 bg-red-700 text-red-100 text-sm tracking-wide font-medium uppercase shadow-lg rounded-full
            hover:bg-red-600 active:bg-red-800 transition-colors duration-150 ease out">
          Usuń
        </button>
      </form>
    @endif
  @endauth

@endsection
