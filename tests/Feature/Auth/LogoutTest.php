<?php

use App\Models\User;

use function Pest\Laravel\assertGuest;
use function Tests\asUser;

it('logs user out', function () {
    asUser()
        ->post('logout');

    assertGuest();
});

it('redirects to welcome page after logging out')
    ->asUser()
    ->post('logout')
    ->assertStatus(302)
    ->assertRedirect('/');

it('redirects to welcome page if no user is authenticated')
    ->post('logout')
    ->assertStatus(302)
    ->assertRedirect('/');
