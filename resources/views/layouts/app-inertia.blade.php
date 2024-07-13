@extends('layouts.app')

@section('head')
  <title inertia>Bajki Polskich Nagrań „Muza”</title>

  @unless (app()->runningUnitTests())
    @vite(['resources/js/inertiaApp.js'])
  @endunless

  @inertiaHead
@endsection

@section('main')
  @inertia
@endsection
