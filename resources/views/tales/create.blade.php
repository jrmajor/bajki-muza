@extends('layouts.app')

@section('content')

  <div class="text-center pb-4">
    <h2 class="text-2xl font-medium">
      <span class="shadow-title">Nowa bajka</span>
    </h2>
  </div>

  @include('tales.form', ['tale' => new App\Models\Tale(), 'action' => 'create'])

@endsection
