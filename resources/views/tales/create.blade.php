@extends('layouts.app')

@section('content')

    <h2 class="text-2xl font-medium leading-7">
        <span class="shadow-title px-1.5">Nowa bajka</span>
    </h2>

    @include('tales.form', ['tale' => (new App\Models\Tale)->fill(['cover' => null]), 'action' => 'create'])

@endsection
