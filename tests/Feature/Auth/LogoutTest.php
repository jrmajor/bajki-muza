<?php

namespace Tests\Feature\Auth;

use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

final class LogoutTest extends TestCase
{
    #[TestDox('logs user out')]
    public function testOk(): void
    {
        $this->asUser()->post('logout');

        $this->assertGuest();
    }

    #[TestDox('redirects to welcome page after logging out')]
    public function testRedirect(): void
    {
        $this->asUser()
            ->post('logout')
            ->assertStatus(302)
            ->assertRedirect('bajki');
    }

    #[TestDox('redirects to welcome page if no user is authenticated')]
    public function testUnauthenticated(): void
    {
        $this->post('logout')
            ->assertStatus(302)
            ->assertRedirect('bajki');
    }
}
