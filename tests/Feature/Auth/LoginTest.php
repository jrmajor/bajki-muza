<?php

use App\Models\User;

use function Pest\Laravel\{assertAuthenticated, assertAuthenticatedAs, assertGuest, from, get, post};
use function Tests\asUser;

test('authenticated users are redirected when trying to log in', function () {
    asUser()
        ->post('login')
        ->assertStatus(302)
        ->assertRedirect('bajki');

    assertAuthenticated();
});

it('requires email', function () {
    from('login')
        ->post('login', [
            'password' => 'password',
        ])
        ->assertSessionHasErrors('username')
        ->assertStatus(302)
        ->assertRedirect('login');

    assertGuest();
});

it('requires password', function () {
    from('login')
        ->post('login', [
            'username' => 'gracjan',
        ])
        ->assertSessionHasErrors('password')
        ->assertStatus(302)
        ->assertRedirect('login');

    assertGuest();
});

it('checks if user exists', function () {
    from('login')
        ->post('login', [
            'username' => 'gracjan',
            'password' => 'hasło',
        ])
        ->assertSessionHasErrors('username')
        ->assertStatus(302)
        ->assertRedirect('login');

    assertGuest();
});

it('checks password', function () {
    $user = User::factory()->create([
        'username' => 'gracjan',
    ]);

    from('login')
        ->post('login', [
            'username' => 'gracjan',
            'password' => 'wrong',
        ])
        ->assertSessionHasErrors('username')
        ->assertStatus(302)
        ->assertRedirect('login');

    assertGuest();
});

test('user can log in with correct credentials', function () {
    $user = User::factory()->create([
        'username' => 'gracjan',
        'password' => Hash::make($password = 'secret'),
    ]);

    post('login', [
        'username' => 'gracjan',
        'password' => 'secret',
    ])->assertSessionHasNoErrors()
        ->assertStatus(302)
        ->assertRedirect('bajki');

    assertAuthenticatedAs($user);
});
