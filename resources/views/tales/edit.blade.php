@extends('layouts.app')

@section('content')

    <h2 class="mb-2 text-2xl font-medium">
    	<a href="{{ route('tales.show', $tale) }}">
    		{{ $tale->title }}
    	</a>
    </h2>

    @include('tales.form', ['tale' => $tale, 'action' => 'edit'])

@endsection
