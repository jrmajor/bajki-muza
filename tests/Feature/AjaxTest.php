<?php

use function Pest\Laravel\get;

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

test()->asUser()
    ->get('ajax/filmpolski')
    ->assertOk();

get('ajax/wikipedia')
    ->assertRedirect('login');

test()->asUser()
    ->get('ajax/wikipedia')
    ->assertOk();

