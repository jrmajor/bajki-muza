@extends('layouts.app')

@section('title', $tale->title)

@section('content')

    <div class="flex flex-col sm:flex-row items-center mb-6">

        <div class="sm:hidden text-center">
            <h2 class="text-2xl font-medium leading-7">
                @auth <a href="{{ route('tales.edit', $tale) }}"> @endauth
                    @foreach (explode(' ', $tale->title) as $word)
                        <span class="shadow-title px-1.5 @if (! $loop->last) -mx-1.5 @else -ml-1.5 @endif">{{ $word }}</span>
                    @endforeach
                @auth </a> @endauth
            </h2>
            @if ($tale->year)
                {{ $tale->year }}
            @endif
        </div>

        <div class="mt-5 mb-2 sm:my-0 sm:mr-6 flex-none self-center bg-gray-400 shadow-lg rounded-lg overflow-hidden">
            <div class="relative bg-gray-800 bg-cover h-40 w-40"
                style="background-image: url(&quot;data:image/svg+xml;utf8,%3Csvg viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill-rule='evenodd' d='M10.998 8.504C8.682 10.98 7.27 14.744 7.87 19.9l.037.32-.178.266c-.83 1.247-.657 2.119-.304 2.667.412.642 1.144.973 1.576.973h.592l.22.55c1.473 3.681 3.167 6.38 4.932 8.144 1.76 1.76 3.55 2.556 5.256 2.556 1.707 0 3.496-.796 5.256-2.556 1.765-1.765 3.46-4.463 4.932-8.144l.22-.55H31c.432 0 1.164-.331 1.577-.973.352-.548.526-1.42-.305-2.667l-.178-.267.037-.32c.6-5.155-.813-8.92-3.13-11.394C26.672 6.014 23.35 4.75 20 4.75c-3.35 0-6.67 1.264-9.002 3.754zm22.905 11.288c.56-5.444-.96-9.637-3.624-12.484C27.58 4.424 23.775 3 20 3c-3.775 0-7.58 1.424-10.28 4.308-2.664 2.847-4.183 7.04-3.623 12.484-.984 1.647-.878 3.167-.146 4.306.58.9 1.525 1.51 2.432 1.708 1.486 3.584 3.222 6.35 5.123 8.25 1.99 1.99 4.2 3.069 6.494 3.069 2.293 0 4.504-1.079 6.494-3.069 1.9-1.9 3.637-4.666 5.123-8.25.907-.197 1.853-.808 2.431-1.708.733-1.139.84-2.659-.145-4.306z M17 22a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM26 22a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z M16.882 11.892c-.452 1.88-1.68 4.402-4.79 7.253a.875.875 0 11-1.183-1.29c2.89-2.649 3.911-4.876 4.272-6.372a6.635 6.635 0 00.175-2.086 9.324 9.324 0 01-.01-.175V9.22c-.002-.055-.008-.174.008-.293a.964.964 0 01.129-.368.896.896 0 01.767-.434c.302 0 .505.147.586.215.09.074.148.152.178.192.06.083.108.174.137.228.03.054.054.103.08.151.042.084.085.168.149.282a4.8 4.8 0 00.869 1.14c.816.786 2.327 1.654 5.217 1.543 2.107-.081 3.707.253 4.912.861 1.216.613 1.98 1.479 2.447 2.366.46.875.617 1.75.663 2.392a6.347 6.347 0 01-.01 1.028 2.811 2.811 0 01-.008.07l-.003.022v.008l-.001.003v.001c0 .001 0 .002-.866-.127l.865.129a.875.875 0 01-1.731-.254v-.003l.004-.031c.003-.031.008-.081.011-.148.008-.135.011-.333-.006-.572a4.369 4.369 0 00-.468-1.705c-.309-.588-.819-1.18-1.684-1.616-.876-.442-2.164-.748-4.057-.676-3.314.128-5.303-.88-6.498-2.029a5.494 5.494 0 01-.073-.071c-.024.12-.05.242-.081.368z' fill='%23858c99'/%3E%3C/svg%3E&quot;)">
                @if ($tale->cover)
                    <img src="{{ $tale->cover('174s') }}" class="inset-0">
                @endif
            </div>
        </div>

        <div class="sm:py-2 flex-grow self-center sm:self-stretch flex flex-col justify-between space-y-3">

            <div class="hidden sm:block self-start">
                <h2 class="text-2xl font-medium leading-7 shadow-title px-1.5 -ml-1.5">
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
                    <a href="{{ route('artists.show', $tale->director) }}"
                        class="inline-flex items-center">
                        {{ $tale->director->name }}
                        @if ($tale->director->appearances > 1)
                            <small class="ml-1.5 h-4.5 w-4.5 text-2xs inline-flex items-center justify-center bg-yellow-300 text-yellow-800 rounded-full shadow-md">
                                {{ $tale->director->appearances }}
                            </small>
                        @endif
                    </a>
                    <br>
                @endif

                @if ($tale->lyricists->count() > 0)
                    <strong>Słowa:</strong>
                    @foreach ($tale->lyricists as $lyricist)
                        <a href="{{ route('artists.show', $lyricist) }}"
                            class="inline-flex items-center">
                            {{ $lyricist->name }}
                            @if ($lyricist->appearances > 1)
                                <small class="ml-1.5 h-4.5 w-4.5 text-2xs inline-flex items-center justify-center bg-yellow-300 text-yellow-800 rounded-full shadow-md -mr-1">
                                    {{ $lyricist->appearances }}
                                </small>
                            @endif
                        </a>
                        @if (! $loop->last),@endif
                    @endforeach
                    <br>
                @endif

                @if ($tale->composers->count() > 0)
                    <strong>Muzyka:</strong>
                    @foreach ($tale->composers as $composer)
                        <a href="{{ route('artists.show', $composer) }}"
                            class="inline-flex items-center">
                            {{ $composer->name }}
                            @if ($composer->appearances > 1)
                                <small class="ml-1.5 h-4.5 w-4.5 text-2xs inline-flex items-center justify-center bg-yellow-300 text-yellow-800 rounded-full shadow-md -mr-1">
                                    {{ $composer->appearances }}
                                </small>
                            @endif
                        </a>
                        @if (! $loop->last),@endif
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    @if ($tale->actors->count())
        <div class="w-full flex flex-col items-center space-y-3">
            <h3 class="text-xl font-medium leading-6 shadow-subtitle px-1">
                Obsada
            </h3>
            <div class="w-full md:w-5/6 xl:w-2/3 flex flex-col space-y-2.5">
                @foreach ($tale->actors as $actor)
                    <a href="{{ route('artists.show', $actor) }}"
                        class="w-full h-14 flex items-center bg-gray-100 rounded-lg shadow-lg overflow-hidden">
                        <div class="flex-none relative bg-gray-400 bg-cover h-14 w-14"
                            style="background-image: url(&quot;data:image/svg+xml;utf8,%3Csvg viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill-rule='evenodd' d='M10.998 8.504C8.682 10.98 7.27 14.744 7.87 19.9l.037.32-.178.266c-.83 1.247-.657 2.119-.304 2.667.412.642 1.144.973 1.576.973h.592l.22.55c1.473 3.681 3.167 6.38 4.932 8.144 1.76 1.76 3.55 2.556 5.256 2.556 1.707 0 3.496-.796 5.256-2.556 1.765-1.765 3.46-4.463 4.932-8.144l.22-.55H31c.432 0 1.164-.331 1.577-.973.352-.548.526-1.42-.305-2.667l-.178-.267.037-.32c.6-5.155-.813-8.92-3.13-11.394C26.672 6.014 23.35 4.75 20 4.75c-3.35 0-6.67 1.264-9.002 3.754zm22.905 11.288c.56-5.444-.96-9.637-3.624-12.484C27.58 4.424 23.775 3 20 3c-3.775 0-7.58 1.424-10.28 4.308-2.664 2.847-4.183 7.04-3.623 12.484-.984 1.647-.878 3.167-.146 4.306.58.9 1.525 1.51 2.432 1.708 1.486 3.584 3.222 6.35 5.123 8.25 1.99 1.99 4.2 3.069 6.494 3.069 2.293 0 4.504-1.079 6.494-3.069 1.9-1.9 3.637-4.666 5.123-8.25.907-.197 1.853-.808 2.431-1.708.733-1.139.84-2.659-.145-4.306z M17 22a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM26 22a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z M16.882 11.892c-.452 1.88-1.68 4.402-4.79 7.253a.875.875 0 11-1.183-1.29c2.89-2.649 3.911-4.876 4.272-6.372a6.635 6.635 0 00.175-2.086 9.324 9.324 0 01-.01-.175V9.22c-.002-.055-.008-.174.008-.293a.964.964 0 01.129-.368.896.896 0 01.767-.434c.302 0 .505.147.586.215.09.074.148.152.178.192.06.083.108.174.137.228.03.054.054.103.08.151.042.084.085.168.149.282a4.8 4.8 0 00.869 1.14c.816.786 2.327 1.654 5.217 1.543 2.107-.081 3.707.253 4.912.861 1.216.613 1.98 1.479 2.447 2.366.46.875.617 1.75.663 2.392a6.347 6.347 0 01-.01 1.028 2.811 2.811 0 01-.008.07l-.003.022v.008l-.001.003v.001c0 .001 0 .002-.866-.127l.865.129a.875.875 0 01-1.731-.254v-.003l.004-.031c.003-.031.008-.081.011-.148.008-.135.011-.333-.006-.572a4.369 4.369 0 00-.468-1.705c-.309-.588-.819-1.18-1.684-1.616-.876-.442-2.164-.748-4.057-.676-3.314.128-5.303-.88-6.498-2.029a5.494 5.494 0 01-.073-.071c-.024.12-.05.242-.081.368z' fill='%23858c99'/%3E%3C/svg%3E&quot;)">
                            @if ($actor->photo('150'))
                                <img src="{{ $actor->photo('150') }}" class="inset-0">
                            @endif
                        </div>
                        <div class="flex-grow flex flex-col justify-between p-2 pl-3">
                            <div class="text-sm sm:text-base font-medium leading-tight">
                                {{ $actor->name }}
                            </div>
                            @if ($actor->pivot->characters)
                                <small>
                                    jako
                                    @foreach (explode('; ', $actor->pivot->characters) as $character)
                                        {{ $character }}@if ($loop->remaining > 1), @elseif ($loop->remaining > 0) i @endif
                                    @endforeach
                                </small>
                            @endif
                        </div>
                        <div class="flex-none pr-4">
                            @if ($actor->appearances > 1)
                                <small class="ml-1.5 h-6 w-6 text-xs inline-flex items-center justify-center bg-yellow-300 text-yellow-800 rounded-full shadow-md">
                                    {{ $actor->appearances }}
                                </small>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

@endsection
