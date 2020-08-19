@extends('layouts.app')

@section('content')

    <h2 class="mb-2 text-2xl font-medium">
    	Edycja:
    	<a href="{{ route('artists.show', $artist->slug) }}">
    		{{ $artist->name }}
    	</a>
    </h2>

    <form method="post" action="{{ route('artists.update', $artist->slug) }}">
        @csrf
        @method('PUT')
        <artist-form :artist-data="{{ json_encode($artist->editData()) }}"></artist-form>
    </form>

@endsection
