<?php /** @var App\Models\Tale $tale */ ?>

<h2 class="text-2xl font-medium">
  @auth <a href="{{ route('tales.edit', $tale) }}"> @endauth
    @foreach (explode(' ', $tale->title) as $word)
      <span class="shadow-title">{{ $word }}</span>
    @endforeach
  @auth </a> @endauth
</h2>
@if ($tale->year)
  <div class="mt-1">{{ $tale->year }}</div>
@endif
