@extends('layouts.app')

@section('title', $artist->name)

@section('content')

    <div class="flex flex-col sm:flex-row items-center mb-4">

        <div class="sm:hidden">
            <h2 class="text-2xl font-medium">
                @auth <a href="{{ route('artists.edit', $artist) }}"> @endauth
                    {{ $artist->name }}
                @auth </a> @endauth
            </h2>
        </div>

        @if ($artist->photo())
            <div class="mt-5 mb-2 sm:my-0 sm:mr-6 flex-none self-center h-40 bg-gray-800 shadow-lg shadow-lg rounded-lg overflow-hidden">
                @auth
                    <form id="flush-cache-form" method="post" action="{{ route('artists.flushCache', $artist) }}" class="hidden"> @csrf </form>
                @endauth

                <img src="{{ $artist->photo() }}" class="h-40"
                    @auth onclick="document.getElementById('flush-cache-form').submit()" @endauth
                    >
            </div>
        @endif

        <div class="@if ($artist->photo()) sm:py-2 @endif flex-grow self-stretch flex flex-col justify-between space-y-3">

            <div class="hidden sm:block">
                <h2 class="text-2xl font-medium">
                    @auth <a href="{{ route('artists.edit', $artist) }}"> @endauth
                        {{ $artist->name }}
                    @auth </a> @endauth
                </h2>
            </div>

            @if ($artist->discogs || $artist->imdb || $artist->wikipedia)
                <div class="self-stretch flex flex-col space-y-2">
                    @if ($artist->wikipedia)
                        <div>
                            {!! strip_tags($artist->wikipedia_extract) !!}
                        </div>
                    @endif

                    <div class="self-center sm:self-start flex items-center space-x-5">
                        @if ($artist->discogs)
                            <a href="{{ $artist->discogs_url }}" target="_blank">
                                <x-icons.discogs class="fill-current h-5"/>
                            </a>
                        @endif
                        @if ($artist->imdb)
                            <a href="{{ $artist->imdb_url }}" target="_blank">
                                <x-icons.imdb class="fill-current h-5"/>
                            </a>
                        @endif
                        @if ($artist->wikipedia)
                            <a href="{{ $artist->wikipedia_url }}" target="_blank">
                                <x-icons.wikipedia class="fill-current h-4"/>
                            </a>
                        @endif
                    </div>
                </div>
            @endif

        </div>

    </div>

    <div class="space-y-3">
        @if ($artist->asDirector->count())
            <div>
                <h3 class="text-xl font-medium">Reżyser:</h3>
                <table>
                    @foreach ($artist->asDirector as $tale)
                        <tr>
                            <td class="py-1.5">
                                <div class="relative bg-gray-800 bg-cover h-8 w-8 rounded overflow-hidden shadow-md"
                                    style="background-image: url(&quot;data:image/svg+xml;utf8,%3Csvg viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill-rule='evenodd' d='M20 34c7.732 0 14-6.268 14-14S27.732 6 20 6 6 12.268 6 20s6.268 14 14 14zm0 2c8.837 0 16-7.163 16-16S28.837 4 20 4 4 11.163 4 20s7.163 16 16 16z M20 24a4 4 0 100-8 4 4 0 000 8zm0 2a6 6 0 100-12 6 6 0 000 12z M21.5 20a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z M12.46 30.211l3.101-4.165a7.543 7.543 0 01-1.593-1.588l-4.26 3.15c.782.997 1.71 1.876 2.752 2.603zm17.748-17.756a12.823 12.823 0 00-2.596-2.744l-3.133 4.272c.59.441 1.114.966 1.553 1.559l4.176-3.087z' fill='%234a5568'/%3E%3C/svg%3E&quot;)">
                                    @if ($tale->cover)
                                        <img src="{{ $tale->cover('174s') }}" class="inset-0">
                                    @endif
                                </div>
                            </td>
                            <td class="py-1.5 px-2"><small>{{ $tale->year }}</small></td>
                            <td class="py-1.5"><a href="{{ route('tales.show', $tale) }}">{{ $tale->title }}</a></td>
                        </tr>
                    @endforeach
                </table>
            </div>
        @endif

        @if ($artist->asLyricist->count())
            <div>
                <h3 class="text-xl font-medium">Autor:</h3>
                <table>
                    @foreach ($artist->asLyricist as $tale)
                        <tr>
                            <td class="py-1.5">
                                <div class="relative bg-gray-800 bg-cover h-8 w-8 rounded overflow-hidden shadow-md"
                                    style="background-image: url(&quot;data:image/svg+xml;utf8,%3Csvg viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill-rule='evenodd' d='M20 34c7.732 0 14-6.268 14-14S27.732 6 20 6 6 12.268 6 20s6.268 14 14 14zm0 2c8.837 0 16-7.163 16-16S28.837 4 20 4 4 11.163 4 20s7.163 16 16 16z M20 24a4 4 0 100-8 4 4 0 000 8zm0 2a6 6 0 100-12 6 6 0 000 12z M21.5 20a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z M12.46 30.211l3.101-4.165a7.543 7.543 0 01-1.593-1.588l-4.26 3.15c.782.997 1.71 1.876 2.752 2.603zm17.748-17.756a12.823 12.823 0 00-2.596-2.744l-3.133 4.272c.59.441 1.114.966 1.553 1.559l4.176-3.087z' fill='%234a5568'/%3E%3C/svg%3E&quot;)">
                                    @if ($tale->cover)
                                        <img src="{{ $tale->cover('174s') }}" class="inset-0">
                                    @endif
                                </div>
                            </td>
                            <td class="py-1.5 px-2"><small>{{ $tale->year }}</small></td>
                            <td class="py-1.5"><a href="{{ route('tales.show', $tale) }}">{{ $tale->title }}</a></td>
                        </tr>
                    @endforeach
                </table>
            </div>
        @endif

        @if ($artist->asComposer->count())
            <div>
                <h3 class="text-xl font-medium">Kompozytor:</h3>
                <table>
                    @foreach ($artist->asComposer as $tale)
                        <tr>
                            <td class="py-1.5">
                                <div class="relative bg-gray-800 bg-cover h-8 w-8 rounded overflow-hidden shadow-md"
                                    style="background-image: url(&quot;data:image/svg+xml;utf8,%3Csvg viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill-rule='evenodd' d='M20 34c7.732 0 14-6.268 14-14S27.732 6 20 6 6 12.268 6 20s6.268 14 14 14zm0 2c8.837 0 16-7.163 16-16S28.837 4 20 4 4 11.163 4 20s7.163 16 16 16z M20 24a4 4 0 100-8 4 4 0 000 8zm0 2a6 6 0 100-12 6 6 0 000 12z M21.5 20a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z M12.46 30.211l3.101-4.165a7.543 7.543 0 01-1.593-1.588l-4.26 3.15c.782.997 1.71 1.876 2.752 2.603zm17.748-17.756a12.823 12.823 0 00-2.596-2.744l-3.133 4.272c.59.441 1.114.966 1.553 1.559l4.176-3.087z' fill='%234a5568'/%3E%3C/svg%3E&quot;)">
                                    @if ($tale->cover)
                                        <img src="{{ $tale->cover('174s') }}" class="inset-0">
                                    @endif
                                </div>
                            </td>
                            <td class="py-1.5 px-2"><small>{{ $tale->year }}</small></td>
                            <td class="py-1.5"><a href="{{ route('tales.show', $tale) }}">{{ $tale->title }}</a></td>
                        </tr>
                    @endforeach
                </table>
            </div>
        @endif

        @if ($artist->asActor->count())
            <div>
                <h3 class="text-xl font-medium">Aktor:</h3>
                <table>
                    @foreach ($artist->asActor as $tale)
                        <tr>
                            <td class="py-1.5">
                                <div class="relative bg-gray-800 bg-cover h-8 w-8 rounded overflow-hidden shadow-md"
                                    style="background-image: url(&quot;data:image/svg+xml;utf8,%3Csvg viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill-rule='evenodd' d='M20 34c7.732 0 14-6.268 14-14S27.732 6 20 6 6 12.268 6 20s6.268 14 14 14zm0 2c8.837 0 16-7.163 16-16S28.837 4 20 4 4 11.163 4 20s7.163 16 16 16z M20 24a4 4 0 100-8 4 4 0 000 8zm0 2a6 6 0 100-12 6 6 0 000 12z M21.5 20a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z M12.46 30.211l3.101-4.165a7.543 7.543 0 01-1.593-1.588l-4.26 3.15c.782.997 1.71 1.876 2.752 2.603zm17.748-17.756a12.823 12.823 0 00-2.596-2.744l-3.133 4.272c.59.441 1.114.966 1.553 1.559l4.176-3.087z' fill='%234a5568'/%3E%3C/svg%3E&quot;)">
                                    @if ($tale->cover)
                                        <img src="{{ $tale->cover('174s') }}" class="inset-0">
                                    @endif
                                </div>
                            </td>
                            <td class="py-1.5 px-2"><small>{{ $tale->year }}</small></td>
                            <td class="py-1.5"><a href="{{ route('tales.show', $tale) }}">{{ $tale->title }}</a></td>
                            @if ($tale->pivot->characters)
                                <td class="py-1.5 pl-1"><small>jako</small> {{ $tale->pivot->characters }}</td>
                            @endif
                        </tr>
                    @endforeach
                </table>
            </div>
        @endif
    </div>

    {{-- @if ($artist->countAppearances() == 0)
        <form method="post" action="{{ route('artists.destroy', $artist) }}">
            @csrf
            @method('DELETE')
            <button class="bg-red-700 text-red-100 focus:bg-red-600">usuń</button>
        </form>
    @endif --}}

@endsection
