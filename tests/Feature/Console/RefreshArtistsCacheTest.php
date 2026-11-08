<?php

namespace Tests\Feature\Console;

use App\Models\Artist;
use App\Services\Discogs;
use App\Services\FilmPolski;
use App\Services\Wikipedia;
use Exception;
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

    #[TestDox('it continues refreshing when an artist fails')]
    public function testFailure(): void
    {
        Artist::factory(3)->create(['discogs' => null, 'wikipedia' => null]);

        $this->mock(FilmPolski::class)
            ->shouldReceive('refreshCache')
            ->times(3)
            ->andReturnUsing(fn () => null, fn () => throw new Exception('Connection timeout'), fn () => null);

        $this->a('artist-cache:refresh')
            ->expectsOutputToContain('Connection timeout')
            ->expectsOutputToContain('Refreshed cache for 2 artists')
            ->expectsOutputToContain('Failed to refresh cache for 1 artists')
            ->assertExitCode(1);
    }
}
