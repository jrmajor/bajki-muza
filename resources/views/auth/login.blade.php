@extends('layouts.app')

@section('content')

    <div class="text-center">
        <h2 class="text-2xl font-medium leading-7">
            <span class="shadow-title px-1.5">Login</span>
        </h2>
    </div>

    <form
        method="POST" action="{{ route('login') }}"
        class="flex flex-col space-y-5"
        x-data="{
            remember: {{ old('remember') ? 'true' : 'false' }}
        }">
        @csrf

        <div class="flex items-center flex-col space-y-2 sm:flex-row sm:space-y-0 sm:space-x-2">
            <input type="text" name="username" value="{{ old('username') }}"
                class="w-full form-input">
            <input type="password" name="password"
                class="w-full form-input">
        </div>

        <div class="flex items-center justify-between">
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
