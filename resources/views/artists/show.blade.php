@extends('layouts.app')

@section('content')

    @if ($artist->photo)
        <div class="flex pb-2 sm:pb-5">
            <div class="flex-none h-40 bg-gray-800">
                @auth
                    <form id="flush-cache-form" method="post" action="{{ route('cache.flush') }}" class="hidden">
                        @csrf
                        <input type="text" name="type" value="artist">
                        <input type="text" name="slug" value="{{ $artist->slug }}">
                    </form>
                @endauth
                <img src="{{ $artist->photo }}" class="h-40 object-cover"
                @auth
                    onclick="document.getElementById('flush-cache-form').submit()"
                @endauth
                    >
            </div>

            <div class="px-5 py-2 flex-grow flex flex-col justify-between">
                <div class="mb-2">
                    <h2>
                        @auth
                            <a href="{{ route('artists.edit', ['artist' => $artist->slug]) }}" class="hover:no-underline">
                        @endauth
                        {{ $artist->name }}
                        @auth
                            </a>
                        @endauth
                    </h2>
                </div>

                <div>
                    @if ($artist->wikipedia)
                        <div class="p-1 pt-0 hidden sm:block">
                            {!! strip_tags($artist->wikipedia_extract) !!}
                        </div>
                    @endif
                    @if ($artist->discogs || $artist->imdb || $artist->wikipedia)
                        <div class="w-full ml-0">
                            @if ($artist->discogs)
                                <img src="https://img.icons8.com/ios-filled/32/edf2f7/music-record.png" class="inline h-5">
                                <a href="{{ $artist->discogs_url }}" target="_blank" class="mr-5">Discogs</a>
                            @endif
                            <br class="sm:hidden">
                            @if ($artist->imdb)
                                <img src="https://img.icons8.com/windows/32/edf2f7/imdb.png" class="inline h-5">
                                <a href="{{ $artist->imdb_url }}" target="_blank" class="mr-5">imdb</a>
                            @endif
                            <br class="sm:hidden">
                            @if ($artist->wikipedia)
                                <img src="https://img.icons8.com/windows/32/edf2f7/wikipedia.png" class="inline h-5">
                                <a href="{{ $artist->wikipedia_url }}" target="_blank">Wikipedia</a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="p-1 pt-0 mb-3 sm:hidden">
            {!! strip_tags($artist->wikipedia_extract) !!}
        </div>

    @else
        <h2>
            @auth
                <a href="{{ route('artists.edit', ['artist' => $artist->slug]) }}" class="hover:no-underline">
            @endauth
            {{ $artist->name }}
            @auth
                </a>
            @endauth
        </h2>

        <div>
            @if ($artist->wikipedia)
                <div class="p-1 pt-0">
                    {!! strip_tags($artist->wikipedia_extract) !!}
                </div>
            @endif
        </div>
        @if ($artist->discogs || $artist->imdb || $artist->wikipedia)
            <div class="w-full mb-2 ml-0">
                @if ($artist->discogs)
                    <img src="https://img.icons8.com/ios-filled/32/edf2f7/music-record.png" class="inline h-5">
                    <a href="{{ $artist->discogs_url }}" target="_blank" class="mr-5">Discogs</a>
                @endif
                @if ($artist->imdb)
                    <img src="https://img.icons8.com/windows/32/edf2f7/imdb.png" class="inline h-5">
                    <a href="{{ $artist->imdb_url }}" target="_blank" class="mr-5">imdb</a>
                @endif
                @if ($artist->wikipedia)
                    <img src="https://img.icons8.com/windows/32/edf2f7/wikipedia.png" class="inline h-5">
                    <a href="{{ $artist->wikipedia_url }}" target="_blank">Wikipedia</a>
                @endif
            </div>
        @endif
    @endif

    @if ($artist->asDirector()->get()->count())
        <h3>Reżyser:</h3>
        <table class="mb-1">
            @foreach ($artist->asDirector()->orderBy('year')->orderBy('title')->get() as $tale)
                <tr>
                    <td>
                        <div style="width: 34px; height: 34px;" class="relative bg-gray-800">
                        @if ($tale->cover)
                                <img src="{{ $tale->cover('34s') }}" class="inset-0">
                        @else
                        <img src="https://img.icons8.com/windows/32/4a5568/music-record.png" class="absolute" style="top: 1px; right: 1px; bottom: 1px; left: 1px">
                        @endif
                            </div>
                    </td>
                    <td>{{ $tale->year }}</td>
                    <td><a href="{{ route('tales.show', ['tale' => $tale->slug]) }}">{{ $tale->title }}</a></td>
                </tr>
            @endforeach
        <table>
    @endif

    @if ($artist->asLyricist()->get()->count())
        <h3>Autor:</h3>
        <table class="mb-1">
            @foreach ($artist->asLyricist()->orderBy('year')->orderBy('title')->get() as $tale)
                <tr>
                    <td>
                        <div style="width: 34px; height: 34px;" class="relative bg-gray-800">
                        @if ($tale->cover)
                                <img src="{{ $tale->cover('34s') }}" class="inset-0">
                        @else
                        <img src="https://img.icons8.com/windows/32/4a5568/music-record.png" class="absolute" style="top: 1px; right: 1px; bottom: 1px; left: 1px">
                        @endif
                            </div>
                    </td>
                    <td>{{ $tale->year }}</td>
                    <td><a href="{{ route('tales.show', ['tale' => $tale->slug]) }}">{{ $tale->title }}</a></td>
                </tr>
            @endforeach
        <table>
    @endif

    @if ($artist->asComposer()->get()->count())
        <h3>Kompozytor:</h3>
        <table class="mb-1">
            @foreach ($artist->asComposer()->orderBy('year')->orderBy('title')->get() as $tale)
                <tr>
                    <td>
                        <div style="width: 34px; height: 34px;" class="relative bg-gray-800">
                        @if ($tale->cover)
                                <img src="{{ $tale->cover('34s') }}" class="inset-0">
                        @else
                        <img src="https://img.icons8.com/windows/32/4a5568/music-record.png" class="absolute" style="top: 1px; right: 1px; bottom: 1px; left: 1px">
                        @endif
                            </div>
                    </td>
                    <td>{{ $tale->year }}</td>
                    <td><a href="{{ route('tales.show', ['tale' => $tale->slug]) }}">{{ $tale->title }}</a></td>
                </tr>
            @endforeach
        <table>
    @endif

    @if ($artist->asActor()->get()->count())
        <h3>Aktor:</h3>
        <table class="mb-1">
            @foreach ($artist->asActor()->orderBy('year')->orderBy('title')->get() as $tale)
                <tr>
                    <td>
                        <div style="width: 34px; height: 34px;" class="relative bg-gray-800">
                        @if ($tale->cover)
                                <img src="{{ $tale->cover('34s') }}" class="inset-0">
                        @else
                        <img src="https://img.icons8.com/windows/32/4a5568/music-record.png" class="absolute" style="top: 1px; right: 1px; bottom: 1px; left: 1px">
                        @endif
                            </div>
                    </td>
                    <td>{{ $tale->year }}</td>
                    <td><a href="{{ route('tales.show', ['tale' => $tale->slug]) }}">{{ $tale->title }}</a></td>
                    @if ($tale->pivot->characters)
                        <td><small>jako</small> {{ $tale->pivot->characters }}</td>
                    @endif
                </tr>
            @endforeach
        <table>
    @endif

    @if ($artist->countAppearances() == 0)
        <form method="post" action="{{ route('artists.destroy', ['artist' => $artist->slug]) }}">
            @csrf
            @method('DELETE')
            <button class="bg-red-700 text-red-100 focus:bg-red-600">usuń</button>
        </form>
    @endif

@endsection