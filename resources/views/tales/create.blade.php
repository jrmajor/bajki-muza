@extends('layouts.app')

@section('content')

    <div class="text-center">
        <h2 class="text-2xl font-medium leading-7 shadow-title">
            <span class="shadow-title px-1.5">Nowa bajka</span>
        </h2>
    </div>

    @include('tales.form', ['tale' => (new App\Tale)->fill(['cover' => null]), 'action' => 'create'])

@endsection
