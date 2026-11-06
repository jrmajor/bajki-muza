<?php

namespace Tests\Feature\Tales;

use App\Models\Tale;
use Inertia\Testing\AssertableInertia as Assert;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\TestWith;
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

    #[TestDox('it filters tales by search query')]
    public function testSearch(): void
    {
        Tale::factory()->createOne(['title' => 'Kot w butach']);
        Tale::factory()->createOne(['title' => 'Calineczka']);

        $this
            ->get('bajki?search=butach')
            ->assertOk()
            ->assertInertia(function (Assert $page) {
                $page
                    ->component('Tales/Index')
                    ->has('data', 1)
                    ->where('data.0.title', 'Kot w butach');
            });
    }

    #[TestDox('it filters tales by discogs presence')]
    #[TestWith(['1', 'Kot w butach'])]
    #[TestWith(['0', 'Calineczka'])]
    public function testDiscogs(string $discogs, string $expected): void
    {
        Tale::factory()->createOne(['title' => 'Kot w butach', 'discogs' => 123456]);
        Tale::factory()->createOne(['title' => 'Calineczka', 'discogs' => null]);

        $this
            ->get("bajki?discogs={$discogs}")
            ->assertOk()
            ->assertInertia(function (Assert $page) use ($expected) {
                $page
                    ->component('Tales/Index')
                    ->has('data', 1)
                    ->where('data.0.title', $expected);
            });
    }

    #[TestDox('it ignores array search query')]
    public function testArraySearch(): void
    {
        Tale::factory(2)->create();

        $this
            ->get('bajki?search[]=x')
            ->assertOk()
            ->assertInertia(function (Assert $page) {
                $page->component('Tales/Index')->has('data', 2);
            });
    }
}
