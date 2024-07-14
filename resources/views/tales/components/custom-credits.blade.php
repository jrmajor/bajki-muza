<?php /** @var App\Models\Tale $tale */ ?>

<div class="flex flex-col">
  @foreach($tale->customCredits() as $label => $credits)
    <div>
      <strong>{{ $label }}:</strong>
      @foreach ($credits as $artist)
        <x-name :$artist :$loop/>
      @endforeach
    </div>
  @endforeach
</div>
