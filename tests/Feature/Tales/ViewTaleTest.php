<?php

use App\Tale;

use function Pest\Laravel\get;

it('works', function () {
    $tale = factory(Tale::class)->create();

    get("bajki/$tale->slug")
        ->assertOk();
});

it('returns 404 when attempting to view nonexistent tale')
    ->get("bajki/nonexistent-tale")
    ->assertStatus(404);
