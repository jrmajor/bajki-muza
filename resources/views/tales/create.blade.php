@extends('layouts.app')

@section('content')

    <h2 class="mb-2 text-2xl font-medium">Nowa bajka</h2>

    <form method="post" action="{{ route('tales.store') }}">
        @csrf
        <tale-form :tale-data="{{ json_encode($tale) }}"></tale-form>
    </form>

@endsection
