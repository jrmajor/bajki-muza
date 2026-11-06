<?php

namespace Tests\Feature\Artists;

use App\Models\Artist;
use Inertia\Testing\AssertableInertia as Assert;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

final class ViewArtistsIndexTest extends TestCase
{
    #[TestDox('it works')]
    public function testOk(): void
    {
        $this
            ->get('artysci')
            ->assertOk()
            ->assertInertia(function (Assert $page) {
                $page->component('Artists/Index');
            });
    }

    #[TestDox('it filters artists by search query')]
    public function testSearch(): void
    {
        Artist::factory()->createOne(['name' => 'Piotr Fronczewski']);
        Artist::factory()->createOne(['name' => 'Wiesław Michnikowski']);

        $this
            ->get('artysci?search=fronczew')
            ->assertOk()
            ->assertInertia(function (Assert $page) {
                $page
                    ->component('Artists/Index')
                    ->has('data', 1)
                    ->where('data.0.name', 'Piotr Fronczewski');
            });
    }

    #[TestDox('it ignores array search query')]
    public function testArraySearch(): void
    {
        Artist::factory(2)->create();

        $this
            ->get('artysci?search[]=x')
            ->assertOk()
            ->assertInertia(function (Assert $page) {
                $page->component('Artists/Index')->has('data', 2);
            });
    }
}
