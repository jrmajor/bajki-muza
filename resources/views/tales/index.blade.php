@extends('layouts.app')

@section('content')

    <div class="space-y-5 mb-8 flex flex-col items-center">
        @foreach ($tales as $tale)
            <a href="{{ route('tales.show', $tale->slug) }}"
                class="w-full lg:w-2/3 xl:1/2 flex rounded-lg shadow-lg overflow-hidden bg-gray-100">
                <div>
                    <div class="relative bg-gray-700 bg-cover h-32 w-32"
                        style="background-image: url(&quot;data:image/svg+xml;utf8,%3Csvg viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill-rule='evenodd' d='M20 34.5c8.008 0 14.5-6.492 14.5-14.5S28.008 5.5 20 5.5 5.5 11.992 5.5 20 11.992 34.5 20 34.5zm0 1.5c8.837 0 16-7.163 16-16S28.837 4 20 4 4 11.163 4 20s7.163 16 16 16z M20 24.5a4.5 4.5 0 100-9 4.5 4.5 0 000 9zm0 1.5a6 6 0 100-12 6 6 0 000 12z M21.25 20a1.25 1.25 0 11-2.5 0 1.25 1.25 0 012.5 0z M11.345 30.061l4.1-4.746c-.27-.23-.52-.481-.751-.75L9.98 28.623c.418.513.875.994 1.365 1.44zM30.06 11.344a13.34 13.34 0 00-1.436-1.363l-4.048 4.722c.261.225.505.47.73.731l4.754-4.09z' fill='%23718096'/%3E%3C/svg%3E&quot;)">
                        @if ($tale->cover)
                            <img src="{{ $tale->cover('174s') }}" class="inset-0">
                        @endif
                    </div>
                </div>
                <div class="flex-grow flex flex-col justify-between p-4 sm:p-5">
                    <div class="text-lg sm:text-xl font-medium leading-tight">
                        {{ $tale->title }}
                    </div>
                    <small>{{ $tale->year }}</small>
                </div>
            </a>
        @endforeach
    </div>

    <div class="w-full flex flex-col items-center ">
        {{ $tales->links() }}
    </div>

@endsection
