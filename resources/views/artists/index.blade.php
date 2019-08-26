@extends('layouts.app')

@section('content')

    <h2 class="mb-2">Artyści</h2>

    <table class="mb-3">
        @foreach($artists as $artist)
            <tr>
                <td class="p-0">
                    <a href="{{ route('artists.show', ['artist' => $artist->slug]) }}">{{ $artist->name }}</a>
                <td>
                <td>{{ $artist->countAppearances() }}</td>
                <td class="pl-4 pr-1">
                    @if($artist->discogs)
                        <a href="{{ $artist->discogs_url }}" target="_blank">
                            <img src="https://img.icons8.com/ios-filled/32/edf2f7/music-record.png" class="inline h-5">
                        </a>
                    @endif
                </td>
                <td class="px-2">
                    @if($artist->imdb)
                        <a href="{{ $artist->imdb_url }}" target="_blank">
                            <img src="https://img.icons8.com/windows/32/edf2f7/imdb.png" class="inline h-5">
                        </a>
                    @endif
                </td>
                <td class="pl-1">
                    @if($artist->wikipedia)
                        <a href="{{ $artist->wikipedia_url }}" target="_blank">
                            <img src="https://img.icons8.com/windows/32/edf2f7/wikipedia.png" class="inline h-5">
                        </a>
                    @endif
                </td>
            </tr>
        @endforeach
    </table>

    {{ $artists->links() }}

@endsection