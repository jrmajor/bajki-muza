@extends('layouts.app')

@section('title', $tale->title)

@section('content')

    <div class="flex flex-col sm:flex-row items-center mb-4">

        <div class="sm:hidden text-center">
            <h2 class="text-2xl font-medium">
                @auth <a href="{{ route('tales.edit', $tale) }}"> @endauth
                    {{ $tale->title }}
                @auth </a> @endauth
            </h2>
            @if ($tale->year)
                {{ $tale->year }}
            @endif
        </div>

        <div class="mt-5 mb-2 sm:my-0 sm:mr-6 flex-none self-center bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <div class="relative bg-gray-800 bg-cover h-40 w-40"
                style="background-image: url(&quot;data:image/svg+xml;utf8,%3Csvg viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill-rule='evenodd' d='M20 34.5c8.008 0 14.5-6.492 14.5-14.5S28.008 5.5 20 5.5 5.5 11.992 5.5 20 11.992 34.5 20 34.5zm0 1.5c8.837 0 16-7.163 16-16S28.837 4 20 4 4 11.163 4 20s7.163 16 16 16z M20 24.5a4.5 4.5 0 100-9 4.5 4.5 0 000 9zm0 1.5a6 6 0 100-12 6 6 0 000 12z M21.25 20a1.25 1.25 0 11-2.5 0 1.25 1.25 0 012.5 0z M11.345 30.061l4.1-4.746c-.27-.23-.52-.481-.751-.75L9.98 28.623c.418.513.875.994 1.365 1.44zM30.06 11.344a13.34 13.34 0 00-1.436-1.363l-4.048 4.722c.261.225.505.47.73.731l4.754-4.09z' fill='%234a5568'/%3E%3C/svg%3E&quot;)">
                @if ($tale->cover)
                    <img src="{{ $tale->cover('174s') }}" class="inset-0">
                @endif
            </div>
        </div>

        <div class="sm:py-2 flex-grow self-center sm:self-stretch flex flex-col justify-between space-y-3">

            <div class="hidden sm:block">
                <h2 class="text-2xl font-medium">
                    @auth <a href="{{ route('tales.edit', $tale) }}"> @endauth
                        {{ $tale->title }}
                    @auth </a> @endauth
                </h2>
                @if ($tale->year)
                    {{ $tale->year }}
                @endif
            </div>

            <div>
                @if ($tale->director)
                    <strong>Reżyseria:</strong>
                    <a href="{{ route('artists.show', $tale->director) }}">{{ $tale->director->name }}</a>
                    <br>
                @endif

                @if ($tale->lyricists->count() > 0)
                    <strong>Słowa:</strong>
                    @foreach ($tale->lyricists as $lyricist)
                        <a href="{{ route('artists.show', $lyricist) }}">{{ $lyricist->name }}</a>@if (! $loop->last),@endif
                    @endforeach
                    <br>
                @endif

                @if ($tale->composers->count() > 0)
                    <strong>Muzyka:</strong>
                    @foreach ($tale->composers as $composer)
                        <a href="{{ route('artists.show', $composer) }}">{{ $composer->name }}</a>@if (! $loop->last),@endif
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <h3 class="text-lg font-medium">Obsada:</h3>
    <table>
        @foreach ($tale->actors as $actor)
            <tr>
                <td class="py-0.5">
                    <div class="flex items-center">
                        <a href="{{ route('artists.show', $actor) }}">{{ $actor->name }}</a>
                        @if ($actor->asActor()->count() > 1)
                            <small class="ml-1.5 h-4.5 w-4.5 text-xs inline-flex items-center justify-center bg-yellow-300 text-yellow-800 rounded-full shadow-md">{{ $actor->asActor()->count() }}</small>
                        @endif
                    </div>
                </td>
                <td class="py-0.5 pl-2">
                    @if ($actor->pivot->characters)
                        <small>jako</small>
                        @foreach (explode('; ', $actor->pivot->characters) as $character)
                            @if (! $loop->first)
                                <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            @endif
                            {{ $character }}
                        @endforeach
                    @endif
                </td>
            </tr>
        @endforeach
    <table>

@endsection
