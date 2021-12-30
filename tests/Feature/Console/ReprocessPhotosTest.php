<?php

namespace Tests\Feature\Console;

use App\Images\Jobs\GenerateArtistPhotoPlaceholders;
use App\Images\Jobs\GenerateArtistPhotoVariants;
use App\Images\Photo;
use App\Models\Artist;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

use function Tests\read_fixture;

final class ReprocessPhotosTest extends TestCase
{
    #[TestDox('it throws error when artist does not exist')]
    public function testNonexistentArtist(): void
    {
        $this->a('reprocess:photos --artist nonexistent-artist')
            ->expectsOutput('Artist does not exist.')
            ->assertExitCode(1);
    }

    #[TestDox('it throws error when artist does not have a photo')]
    public function testNoPhoto(): void
    {
        Artist::factory()->noPhoto()->create(['name' => 'Test artist']);

        $this->a('reprocess:photos --artist test-artist')
            ->expectsOutput('Artist does not have a photo.')
            ->assertExitCode(1);
    }

    #[TestDox('it works with single artist')]
    public function testSingleArtist(): void
    {
        Storage::fake('testing');

        Artist::factory()->photo('test.jpg')->create(['name' => 'Test Artist']);

        // Photo by David Grandmougin on Unsplash
        Photo::disk()->put(
            'photos/original/test.jpg',
            read_fixture('Images/photo.jpg'),
        );

        Queue::fake();

        $this->a('reprocess:photos --artist test-artist')->assertExitCode(0);

        Queue::assertPushedWithChain(
            GenerateArtistPhotoPlaceholders::class,
            [GenerateArtistPhotoVariants::class],
        );
    }

    #[TestDox('it asks for confirmation when processing all photos')]
    public function testConfirmation(): void
    {
        $this->a('reprocess:photos')
            ->expectsConfirmation('Do you want to reprocess all photos?', 'no')
            ->assertExitCode(1);
    }
}
