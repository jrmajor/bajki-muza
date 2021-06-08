<?php

use App\Models\Tale;
use App\Services\Discogs;
use App\Services\Wikipedia;

use function Pest\Laravel\get;
use function Pest\Laravel\mock;

it('works', function () {
    $tale = Tale::factory()->create();

    mock(Wikipedia::class)->shouldReceive('extract')->andReturn('test');
    mock(Discogs::class)->shouldReceive('photos')->andReturn(collect());

    get("bajki/{$tale->slug}")
        ->assertOk();
});

it('returns 404 when attempting to view nonexistent tale')
    ->get('bajki/nonexistent-tale')
    ->assertStatus(404);
