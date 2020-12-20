<?php

use Illuminate\Support\Facades\Http;
use function Pest\Laravel\get;
use function Tests\asUser;

get('ajax/artists')
    ->assertRedirect('login');

test()->asUser()
    ->get('ajax/artists')
    ->assertOk();

get('ajax/discogs')
    ->assertRedirect('login');

test()->asUser()
    ->get('ajax/discogs')
    ->assertOk();

get('ajax/filmpolski')
    ->assertRedirect('login');

test("asUser → get 'ajax/filmpolski' → assertOk", function () {
    Http::fake();

    asUser()
        ->get('ajax/filmpolski')
        ->assertOk();
});

get('ajax/wikipedia')
    ->assertRedirect('login');

test("asUser → get 'ajax/wikipedia' → assertOk", function () {
    Http::fake();

    asUser()
        ->get('ajax/wikipedia')
        ->assertOk();
});
