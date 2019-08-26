@extends('layouts.app')

@section('content')

    <h2 class="mb-2">Edycja: {{ $tale->title }}</h2>

    <form method="post" action="{{ route('tales.update', ['tale' => $tale->slug]) }}">
        @csrf
        @method('PUT')
        <tale-form :tale-data="{{ json_encode($tale->editData()) }}"></tale-form>
    </form>

@endsection