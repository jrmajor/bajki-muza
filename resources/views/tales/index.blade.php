@extends('layouts.app')

@section('content')

    <h2 class="mb-2">Bajki Grajki</h2>

    <table>
        @foreach($tales as $tale)
            <tr>
                <td>
                    <div style="width: 34px; height: 34px;" class="relative bg-gray-800">
                    @if($tale->cover)
                            <img src="{{ $tale->cover('34s') }}" class="inset-0">
                    @else
                    <img src="https://img.icons8.com/windows/32/4a5568/music-record.png" class="absolute" style="top: 1px; right: 1px; bottom: 1px; left: 1px">
                    @endif
                        </div>
                </td>
                <td>{{ $tale->year }}</td>
                <td><a href="{{ route('tales.show', ['tale' => $tale->slug]) }}">{{ $tale->title }} <small class="hidden">{{ $tale->nr ? '[' . $tale->nr . ']' : '' }}</small></a></td>
            </tr>
        @endforeach
    </table>

    {{ $tales->links() }}

@endsection