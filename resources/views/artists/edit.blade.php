@extends('layouts.app')

@section('content')

    <h2 class="mb-2">Edycja: {{ $artist->name }}</h2>

    <form method="post" action="{{ route('artists.update', ['artist' => $artist->slug]) }}">
        @csrf
        @method('PUT')
        <artist-form :artist-data="{{ json_encode($artist->editData()) }}"></artist-form>
    </form>

@endsection