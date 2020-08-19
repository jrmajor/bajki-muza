@extends('layouts.app')

@section('content')

    <h2 class="mb-2 text-2xl font-medium">
    	<a href="{{ route('tales.show', $tale) }}">
    		{{ $tale->title }}
    	</a>
    </h2>

    <form method="post" action="{{ route('tales.update', $tale) }}">
        @csrf
        @method('PUT')
        <tale-form :tale-data="{{ json_encode($tale->editData()) }}"></tale-form>
    </form>

@endsection
