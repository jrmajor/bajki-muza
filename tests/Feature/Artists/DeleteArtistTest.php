<?php

use App\Artist;

use function Pest\Laravel\delete;
use function Tests\asUser;

test('guests can not delete artist', function () {
    $artist = factory(Artist::class)->create();

    delete("/artysci/$artist->slug")
        ->assertRedirect('login');

    assertNotNull($artist->fresh());

});

test('users can delete artist', function () {
    $artist = factory(Artist::class)->create();

    asUser()
        ->delete("/artysci/$artist->slug")
        ->assertRedirect('/artysci');

    assertNull($artist->fresh());

});
