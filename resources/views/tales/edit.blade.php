@extends('layouts.app')

@section('content')

    <h2 class="text-2xl font-medium leading-7">
        <a href="{{ route('tales.show', $tale) }}">
            @foreach (explode(' ', $tale->title) as $word)
                <span class="shadow-title px-1.5 @if (! $loop->last) -mx-1.5 @else -ml-1.5 @endif">{{ $word }}</span>
            @endforeach
        </a>
    </h2>

    @include('tales.form', ['tale' => $tale, 'action' => 'edit'])

@endsection
