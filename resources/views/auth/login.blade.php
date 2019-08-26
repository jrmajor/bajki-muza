@extends('layouts.app')

@section('content')
    <h2 class="mb-2">Login</h2>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <table>
            <tr>
                <td>Username</td>
                <td><input type="text" name="username" value="{{ old('username') }}" required autofocus></td>
            </tr>
            @error('email')
                    <tr>
                        <td colspan="2">{{ $message }}</td>
                    </tr>
            @enderror
            <tr>
                <td>Password</td>
                <td><input type="password" name="password" required></td>
            </tr>
            @error('password')
                <tr>
                    <td colspan="2">{{ $message }}</td>
                </tr>
            @enderror
            <tr>
                <td colspan="2">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> remember
                </td>
            </tr>
        </table>

        <button type="submit" class="mt-2 float-right">login</button>
    </form>

@endsection
