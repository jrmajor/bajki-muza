<?php

namespace Tests\Feature\Artists;

use Inertia\Testing\AssertableInertia as Assert;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

final class ViewArtistsIndexTest extends TestCase
{
    #[TestDox('it works')]
    public function testOk(): void
    {
        $this->get('artysci')->assertOk()->assertInertia(function (Assert $page) {
            $page->component('Artists/Index');
        });
    }
}
