@php
  $size ??= 'normal';
@endphp

@if ($count > 1)
  <small class="inline-flex items-center justify-center bg-yellow-300 text-yellow-800 rounded-full shadow-md
    @if ($size === 'small') ml-1.5 size-4.5 text-2xs
    @else ml-1.5 size-6 text-xs
    @endif ">
   {{ $count }}
  </small>
@endif
