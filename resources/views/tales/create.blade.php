@extends('layouts.app')

@section('content')

    <h2 class="mb-2 text-2xl font-medium">Nowa bajka</h2>

    @include('tales.form', ['tale' => (new App\Tale)->fill(['cover' => null]), 'action' => 'create'])

@endsection
