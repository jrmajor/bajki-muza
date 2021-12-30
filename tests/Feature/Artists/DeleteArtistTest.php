<?php

namespace Tests\Feature\Artists;

use App\Models\Artist;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

final class DeleteArtistTest extends TestCase
{
    #[TestDox('guests can not delete artist')]
    public function testGuest(): void
    {
        $artist = Artist::factory()->createOne();

        $this->delete("artysci/{$artist->slug}")
            ->assertRedirect('login');

        $this->assertNotNull($artist->fresh());
    }

    #[TestDox('users can delete artist')]
    public function testUser(): void
    {
        $artist = Artist::factory()->createOne();

        $this->asUser()
            ->delete("artysci/{$artist->slug}")
            ->assertRedirect('artysci');

        $this->assertNull($artist->fresh());
    }
}
