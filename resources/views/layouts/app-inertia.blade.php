@extends('layouts.app')

@section('head')
  <title inertia>Bajki Polskich Nagrań „Muza”</title>

  @unless (app()->runningUnitTests())
    @vite(['resources/js/inertiaApp.ts', "resources/js/Pages/{$page['component']}.svelte"])
  @endunless

  @inertiaHead
@endsection

@section('outerContent')
  @inertia
@endsection
