@extends('layouts.app-classic')

@section('content')

  <div class="text-center pb-4">
    <h2 class="text-2xl font-medium">
      <a href="{{ route('tales.show', $tale) }}">
        @foreach (explode(' ', $tale->title) as $word)
          <span class="shadow-title">{{ $word }}</span>
        @endforeach
      </a>
    </h2>
  </div>

  @include('tales.form', ['tale' => $tale, 'action' => 'edit'])

@endsection
