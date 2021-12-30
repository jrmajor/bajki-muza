<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

final class LoginTest extends TestCase
{
    #[TestDox('authenticated users are redirected when trying to log in')]
    public function testAlreadyAuthenticated(): void
    {
        $this->asUser()
            ->post('login')
            ->assertStatus(302)
            ->assertRedirect('bajki');

        $this->assertAuthenticated();
    }

    #[TestDox('it requires email')]
    public function testNoEmail(): void
    {
        $this->from('login')
            ->post('login', ['password' => 'password'])
            ->assertSessionHasErrors('username')
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->assertGuest();
    }

    #[TestDox('it requires password')]
    public function testNoPassword(): void
    {
        $this->from('login')
            ->post('login', ['username' => 'gracjan'])
            ->assertSessionHasErrors('password')
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->assertGuest();
    }

    #[TestDox('it checks if user exists')]
    public function testNoUser(): void
    {
        $this->from('login')
            ->post('login', [
                'username' => 'gracjan',
                'password' => 'hasło',
            ])
            ->assertSessionHasErrors('username')
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->assertGuest();
    }

    #[TestDox('it checks password')]
    public function testInvalidPassword(): void
    {
        User::factory()->create([
            'username' => 'gracjan',
        ]);

        $this->from('login')
            ->post('login', [
                'username' => 'gracjan',
                'password' => 'wrong',
            ])
            ->assertSessionHasErrors('username')
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->assertGuest();
    }

    #[TestDox('user can log in with correct credentials')]
    public function testOk(): void
    {
        $user = User::factory()->createOne([
            'username' => 'gracjan',
            'password' => Hash::make('secret'),
        ]);

        $this->post('login', [
            'username' => 'gracjan',
            'password' => 'secret',
        ])
            ->assertSessionHasNoErrors()
            ->assertStatus(302)
            ->assertRedirect('bajki');

        $this->assertAuthenticatedAs($user);
    }
}
