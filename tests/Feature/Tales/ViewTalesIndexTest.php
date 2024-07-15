<?php

namespace Tests\Feature\Tales;

use Inertia\Testing\AssertableInertia as Assert;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

final class ViewTalesIndexTest extends TestCase
{
    #[TestDox('it works')]
    public function testOk(): void
    {
        $this
            ->get('bajki')
            ->assertOk()
            ->assertInertia(function (Assert $page) {
                $page->component('Tales/Index');
            });
    }
}
