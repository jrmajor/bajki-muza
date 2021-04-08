@extends('layouts.app')

@section('content')

  <div class="text-center">
    <h2 class="text-2xl font-medium">
      <span class="shadow-title">Login</span>
    </h2>
  </div>

  <form
    method="POST" action="{{ route('login') }}"
    class="flex flex-col gap-5 mt-5"
    x-data="{
      remember: {{ old('remember') ? 'true' : 'false' }}
    }">
    @csrf

    <div class="flex flex-col gap-2 items-center sm:flex-row">
      <input type="text" name="username" value="{{ old('username') }}"
        class="w-full form-input">
      <input type="password" name="password"
        class="w-full form-input">
    </div>

    <div class="flex justify-between items-center">
      <div>
        <input type="checkbox" name="remember" id="remember"
          x-model="remember" class="hidden">
        <label for="remember" class="text-2xl"
          x-text="remember ? '🦞' : '🦎'"></label>
      </div>

      <button type="submit" class="text-2xl">
        🐠
      </button>
    </div>
  </form>

@endsection
