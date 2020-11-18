<?php /** @var App\Models\Tale $tale */ ?>

@php
  $text = $tale->creditsFor(CreditType::text());
  $authors = $tale->creditsFor(CreditType::author());
@endphp

@if ($text->isNotEmpty() xor $authors->isNotEmpty())
  <strong>Słowa:</strong>
  @foreach ($text->merge($authors) as $lyricist)
    <a href="{{ route('artists.show', $lyricist) }}"
      class="inline-flex items-center">
      {{ $lyricist->name }}@if (! $loop->last && $lyricist->appearances <= 1),@endif
      <x-appearances :count="$lyricist->appearances" size="small"/>
    </a>
    @if (! $loop->last && $lyricist->appearances > 1),@endif
  @endforeach
  <br>
@elseif ($text->isNotEmpty() && $authors->isNotEmpty())
  <strong>Słowa:</strong>
  @foreach ($text as $lyricist)
    <a href="{{ route('artists.show', $lyricist) }}"
      class="inline-flex items-center">
      {{ $lyricist->name }}@if (! $loop->last && $lyricist->appearances <= 1),@endif
      <x-appearances :count="$lyricist->appearances" size="small"/>
    </a>
    @if (! $loop->last && $lyricist->appearances > 1),@endif
  @endforeach
  <span class="ml-1.5">wg.</span>
  @foreach ($authors as $author)
    <a href="{{ route('artists.show', $author) }}"
      class="inline-flex items-center">
      {{ $author->genetivus ?? $author->name }}@if (! $loop->last && $author->appearances <= 1),@endif
      <x-appearances :count="$author->appearances" size="small"/>
    </a>
    @if (! $loop->last && $author->appearances > 1),@endif
  @endforeach
  <br>
@endif

@unless ($tale->creditsFor(CreditType::lyrics())->isEmpty())
  <strong>Teksty piosenek:</strong>
  @foreach ($tale->creditsFor(CreditType::lyrics()) as $lyricist)
    <a href="{{ route('artists.show', $lyricist) }}"
      class="inline-flex items-center">
      {{ $lyricist->name }}@if (! $loop->last && $lyricist->appearances <= 1),@endif
      <x-appearances :count="$lyricist->appearances" size="small"/>
    </a>
    @if (! $loop->last && $lyricist->appearances > 1),@endif
  @endforeach
  <br>
@endif

@unless ($tale->creditsFor(CreditType::music())->isEmpty())
  <strong>Muzyka:</strong>
  @foreach ($tale->creditsFor(CreditType::music()) as $composer)
    <a href="{{ route('artists.show', $composer) }}"
      class="inline-flex items-center">
      {{ $composer->name }}@if (! $loop->last && $composer->appearances <= 1),@endif
      <x-appearances :count="$composer->appearances" size="small"/>
    </a>
    @if (! $loop->last && $composer->appearances > 1),@endif
  @endforeach
@endif
