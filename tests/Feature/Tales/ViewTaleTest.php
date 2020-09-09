<?php

use App\Models\Tale;

use function Pest\Laravel\get;

it('works', function () {
    $tale = Tale::factory()->create();

    get("bajki/$tale->slug")
        ->assertOk();
});

it('returns 404 when attempting to view nonexistent tale')
    ->get('bajki/nonexistent-tale')
    ->assertStatus(404);
