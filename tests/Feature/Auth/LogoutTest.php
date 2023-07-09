<?php

namespace Tests\Feature\Auth;

use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

final class LogoutTest extends TestCase
{
    #[TestDox('it logs user out')]
    public function testOk(): void
    {
        $this->asUser()->post('logout');

        $this->assertGuest();
    }

    #[TestDox('it redirects to welcome page after logging out')]
    public function testRedirect(): void
    {
        $this->asUser()
            ->post('logout')
            ->assertFound()
            ->assertRedirect('bajki');
    }

    #[TestDox('it redirects to welcome page if no user is authenticated')]
    public function testUnauthenticated(): void
    {
        $this->post('logout')
            ->assertFound()
            ->assertRedirect('bajki');
    }
}
