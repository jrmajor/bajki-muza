<?php

namespace Tests\Feature\Console;

use App\Models\Artist;
use App\Services\Discogs;
use App\Services\FilmPolski;
use App\Services\Wikipedia;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

final class RefreshArtistsCacheTest extends TestCase
{
    #[TestDox('it can refresh artists cache')]
    public function testOk(): void
    {
        Artist::factory(48)->sequence(
            [],
            ['discogs' => null, 'filmpolski' => null, 'wikipedia' => null],
        )->create();

        $this->mock(Discogs::class)->shouldReceive('refreshCache')->times(24);
        $this->mock(FilmPolski::class)->shouldReceive('refreshCache')->times(24);
        $this->mock(Wikipedia::class)->shouldReceive('refreshCache')->times(24);

        $this->artisan('artist-cache:refresh')->assertExitCode(0);
    }
}
