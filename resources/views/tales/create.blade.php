@extends('layouts.app')

@section('content')

    <h2 class="mb-2 text-2xl font-medium">Nowa bajka</h2>

    <form method="post" action="{{ route('tales.store') }}">
        @csrf

        @php
            $tale = [
                'title' => '', 'year' => '', 'director' => '', 'cover' => '', 'nr' => '',
                'lyricists' => [['credit_nr' => '', 'artist' => '']],
                'composers' => [['credit_nr' => '', 'artist' => '']],
                'actors' => [
                    ['credit_nr' => '1', 'artist' => '', 'characters' => ''],
                    ['credit_nr' => '2', 'artist' => '', 'characters' => ''],
                    ['credit_nr' => '3', 'artist' => '', 'characters' => ''],
                    ['credit_nr' => '4', 'artist' => '', 'characters' => ''],
                    ['credit_nr' => '5', 'artist' => '', 'characters' => ''],
                    ['credit_nr' => '6', 'artist' => '', 'characters' => ''],
                    ['credit_nr' => '7', 'artist' => '', 'characters' => ''],
                    ['credit_nr' => '8', 'artist' => '', 'characters' => ''],
                    ['credit_nr' => '9', 'artist' => '', 'characters' => ''],
                    ['credit_nr' => '10', 'artist' => '', 'characters' => ''],
                ],
            ]
        @endphp

        <tale-form :tale-data="{{ json_encode($tale) }}"></tale-form>
    </form>

@endsection
