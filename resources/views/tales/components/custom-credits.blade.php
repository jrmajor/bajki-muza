<?php /** @var App\Models\Tale $tale */ ?>

<div class="flex flex-col">
  @foreach($tale->customCredits() as $label => $credits)
    <div>
      <strong>{{ Str::ucfirst($label) }}:</strong>
      @foreach ($credits as $artist)
        <a href="{{ route('artists.show', $artist) }}" class="inline-flex items-center">
          {{ $artist->name }}@if (! $loop->last && $artist->appearances <= 1),@endif
          <x-appearances :count="$artist->appearances" size="small"/>
        </a>@if (! $loop->last && $artist->appearances > 1),@endif
      @endforeach
    </div>
  @endforeach
</div>
