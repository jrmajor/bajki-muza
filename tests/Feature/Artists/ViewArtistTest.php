<?php

namespace Tests\Feature\Artists;

use App\Models\Artist;
use App\Services\Discogs;
use App\Services\Wikipedia;
use App\Values\Discogs\DiscogsPhotos;
use Inertia\Testing\AssertableInertia as Assert;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

final class ViewArtistTest extends TestCase
{
    #[TestDox('it works')]
    public function testOk(): void
    {
        $artist = Artist::factory()->createOne();

        $this->partialMock(Wikipedia::class)->shouldReceive('extract')->andReturn('test');
        $this->partialMock(Discogs::class)->shouldReceive('photos')->andReturn(new DiscogsPhotos([]));

        $this
            ->get("artysci/{$artist->slug}")
            ->assertOk()
            ->assertInertia(function (Assert $page) {
                $page->component('Artists/Show');
            });
    }

    #[TestDox('it returns 404 when attempting to view nonexistent artist')]
    public function test404(): void
    {
        $this->get('artysci/nonexistent-artist')->assertNotFound();
    }

    #[TestDox('it uses slug as the route key')]
    public function testRouteKey(): void
    {
        $artist = Artist::factory()->createOne(['name' => 'Jan Kowalski']);

        $this->assertSame(url('artysci/jan-kowalski'), route('artists.show', $artist));

        $this->get("artysci/{$artist->id}")->assertNotFound();
    }
}
