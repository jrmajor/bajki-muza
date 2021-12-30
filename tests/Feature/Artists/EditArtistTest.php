<?php

namespace Tests\Feature\Artists;

use App\Models\Artist;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

final class EditArtistTest extends TestCase
{
    private array $oldAttributes;

    private array $newAttributes;

    private Artist $artist;

    protected function setUp(): void
    {
        parent::setUp();

        $this->oldAttributes = [
            'slug' => 'ilona-kusmierska',
            'name' => 'Ilona Kuśmierska',
            'discogs' => 602488,
            'filmpolski' => 11623,
            'wikipedia' => 'Ilona_Kuśmierska',
        ];

        $this->newAttributes = [
            'slug' => 'tadeusz-bartosik',
            'name' => 'Tadeusz Bartosik',
            'discogs' => 1023394,
            'filmpolski' => 116251,
            'wikipedia' => 'Tadeusz_Bartosik',
        ];

        $this->artist = Artist::factory()->createOne($this->oldAttributes);
    }

    #[TestDox('guests are asked to log in when attempting to view edit artist form')]
    public function testGuestView(): void
    {
        $this->get("artysci/{$this->artist->slug}/edit")->assertRedirect('login');
    }

    #[TestDox('guests are asked to log in when attempting to view edit form for nonexistent artist')]
    public function testGuestNonexistent(): void
    {
        $this->get('artysci/2137/edit')->assertRedirect('login');
    }

    #[TestDox('users can view edit artist form')]
    public function testUserView(): void
    {
        Http::fake();

        $this->asUser()->get("artysci/{$this->artist->slug}/edit")->assertOk();
    }

    #[TestDox('guests cannot edit artist')]
    public function testGuestEdit(): void
    {
        $this->put("artysci/{$this->artist->slug}", $this->newAttributes)
            ->assertRedirect('login');

        $this->artist->refresh();

        foreach ($this->oldAttributes as $key => $attribute) {
            $this->assertSame($attribute, $this->artist->{$key});
        }
    }

    #[TestDox('users with permissions can edit artist')]
    public function testUserEdit(): void
    {
        $this->asUser()
            ->put("artysci/{$this->artist->slug}", $this->newAttributes)
            ->assertRedirect("artysci/{$this->newAttributes['slug']}");

        $this->artist->refresh();

        foreach ($this->newAttributes as $key => $attribute) {
            $this->assertSame($attribute, $this->artist->{$key});
        }
    }
}
