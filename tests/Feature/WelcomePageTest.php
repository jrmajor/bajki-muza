<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

final class WelcomePageTest extends TestCase
{
    #[TestDox('it works')]
    public function testOk(): void
    {
        $this->get('/')->assertRedirect('bajki');
    }
}
