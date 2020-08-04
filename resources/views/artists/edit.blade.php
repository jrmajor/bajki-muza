@extends('layouts.app')

@section('content')

    <h2 class="mb-2">
    	Edycja:
    	<a href="{{ route('artists.show', $artist->slug) }}" class="hover:no-underline">
    		{{ $artist->name }}
    	</a>
    </h2>

    <form method="post" action="{{ route('artists.update', $artist->slug) }}">
        @csrf
        @method('PUT')
        <artist-form :artist-data="{{ json_encode($artist->editData()) }}"></artist-form>
    </form>

@endsection
