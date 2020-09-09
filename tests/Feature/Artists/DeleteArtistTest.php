<?php

use App\Models\Artist;

use function Pest\Laravel\delete;
use function Tests\asUser;

test('guests can not delete artist', function () {
    $artist = Artist::factory()->create();

    delete("artysci/$artist->slug")
        ->assertRedirect('login');

    expect($artist->fresh())->not->toBeNull();

});

test('users can delete artist', function () {
    $artist = Artist::factory()->create();

    asUser()
        ->delete("artysci/$artist->slug")
        ->assertRedirect('artysci');

    expect($artist->fresh())->toBeNull();

});
