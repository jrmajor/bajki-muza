<?php

use App\Models\Artist;
use Illuminate\Support\Facades\Cache;
use function Pest\Laravel\get;

it('works', function () {
    $artist = Artist::factory()->create();

    get("artysci/$artist->slug")
        ->assertOk();
});

it('returns 404 when attempting to view nonexistent artist')
    ->get("artysci/nonexistent-artist")
    ->assertStatus(404);
