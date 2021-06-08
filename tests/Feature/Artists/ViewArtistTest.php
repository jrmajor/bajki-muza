<?php

use App\Models\Artist;
use App\Services\Discogs;
use App\Services\Wikipedia;

use function Pest\Laravel\get;
use function Pest\Laravel\partialMock;

it('works', function () {
    $artist = Artist::factory()->create();

    partialMock(Wikipedia::class)->shouldReceive('extract')->andReturn('test');
    partialMock(Discogs::class)->shouldReceive('photos')->andReturn(collect());

    get("artysci/{$artist->slug}")
        ->assertOk();
});

it('returns 404 when attempting to view nonexistent artist')
    ->get('artysci/nonexistent-artist')
    ->assertStatus(404);
