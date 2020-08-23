@extends('layouts.app')

@section('content')

    <div class="text-center">
        <h2 class="text-2xl font-medium leading-7">
            @auth <a href="{{ route('tales.show', $tale) }}"> @endauth
                @foreach (explode(' ', $tale->title) as $word)
                    <span class="shadow-title px-1.5 @if (! $loop->last) -mx-1.5 @else -ml-1.5 @endif">{{ $word }}</span>
                @endforeach
            @auth </a> @endauth
        </h2>
    </div>

    @include('tales.form', ['tale' => $tale, 'action' => 'edit'])

@endsection
