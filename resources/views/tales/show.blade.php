@extends('layouts.app')

@section('content')

    <div class="flex pb-5 flex-col sm:flex-row">

        <div class="mb-2 sm:hidden flex-grow text-center">
            <h2>
                @auth
                    <a href="{{ route('tales.edit', ['tale' => $tale->slug]) }}" class="hover:no-underline">
                @endauth
                {{ $tale->title }}
                @auth
                    </a>
                @endauth
                @if($tale->year)
                    <small>({{ $tale->year }})</small>
                @endif
            </h2>
        </div>

        <div class="flex-none w-40 h-40 bg-gray-800 self-center">
            @if($tale->cover)
                <img src="{{ $tale->cover('174s') }}">
            @endif
        </div>

        <div class="sm:px-5 py-2 flex-grow flex flex-col justify-between">
            <div class="mb-2 hidden sm:block">
                <h2>
                    @auth
                        <a href="{{ route('tales.edit', ['tale' => $tale->slug]) }}" class="hover:no-underline">
                    @endauth
                    {{ $tale->title }}
                    @auth
                        </a>
                    @endauth
                    @if($tale->year)
                        <small>({{ $tale->year }})</small>
                    @endif
                </h2>
            </div>

            <div>
                <h3 class="inline">Reżyseria:</h3>
                <a href="{{ route('artists.show', ['actor' => $tale->director->slug]) }}">{{ $tale->director->name }}</a>

                <br>

                <h3 class="inline">Słowa:</h3>
                @foreach($tale->lyricists()->orderBy('credit_nr')->get() as $lyricist)
                    <a href="{{ route('artists.show', ['actor' => $lyricist->slug]) }}">{{ $lyricist->name }}</a>@if(! $loop->last),@endif
                @endforeach

                <br>

                <h3 class="inline">Muzyka:</h3>
                @foreach($tale->composers()->orderBy('credit_nr')->get() as $composer)
                    <a href="{{ route('artists.show', ['actor' => $composer->slug]) }}">{{ $composer->name }}</a>@if(! $loop->last),@endif
                @endforeach
            </div>
        </div>
    </div>

    <h3>Obsada:</h3>
    <table>
        @foreach($tale->actors()->orderBy('credit_nr')->get() as $actor)
            <tr>
                <td>
                    <a href="{{ route('artists.show', ['actor' => $actor->slug]) }}">{{ $actor->name }}</a>
                    @if($actor->asActor()->get()->count() > 1)
                        <small class="px-1 py-0 bg-gray-800 rounded-full">{{ $actor->asActor()->get()->count() }}</small>
                    @endif
                </td>
                @if($actor->pivot->characters)
                    <td>
                        <small>jako</small>
                        @foreach(explode('; ', $actor->pivot->characters) as $character)
                            @if(!$loop->first)
                                <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            @endif
                            {{ $character }}
                        @endforeach
                    </td>
                @endif
            </tr>
        @endforeach
    <table>

@endsection