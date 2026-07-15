<?php

namespace Tests\Feature;

use Inertia\Testing\AssertableInertia as Assert;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

final class NotFoundTest extends TestCase
{
    #[TestDox('browser 404 renders the error page')]
    public function testBrowserNotFound(): void
    {
        $this
            ->get('missing-page')
            ->assertNotFound()
            ->assertInertia(function (Assert $page) {
                $page->component('Errors/404');
            });
    }

    #[TestDox('json 404 use json responses')]
    public function testJsonNotFound(): void
    {
        $this
            ->getJson('missing-page')
            ->assertNotFound()
            ->assertHeader('content-type', 'application/json')
            ->assertExactJson(['message' => 'The route missing-page could not be found.']);
    }
}
