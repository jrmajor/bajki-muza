<?php

use App\Models\Artist;
use Facades\App\Services\Discogs;
use Facades\App\Services\Wikipedia;
use function Pest\Laravel\get;

it('works', function () {
    $artist = Artist::factory()->create();

    Wikipedia::shouldReceive('extract')
        ->andReturn('test');

    Discogs::shouldReceive('photos')
        ->andReturn([]);

    get("artysci/$artist->slug")
        ->assertOk();
});

it('returns 404 when attempting to view nonexistent artist')
    ->get('artysci/nonexistent-artist')
    ->assertStatus(404);
