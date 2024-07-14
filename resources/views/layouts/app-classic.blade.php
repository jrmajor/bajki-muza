@extends('layouts.app')

@section('head')
  @hasSection('title')
    <title>@yield('title')</title>
  @else
    <title>{{ config('app.name') }}</title>
  @endif

  @hasSection('meta')
    @yield('meta')
  @endif

  @unless (app()->runningUnitTests())
    @vite(['resources/js/classicApp.js'])
  @endunless
@endsection

@section('main')
  @yield('content')
@endsection
