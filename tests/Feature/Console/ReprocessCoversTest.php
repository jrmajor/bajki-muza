<?php

namespace Tests\Feature\Console;

use App\Images\Cover;
use App\Images\Jobs\GenerateImageVariants;
use App\Images\Jobs\GenerateTaleCoverPlaceholder;
use App\Models\Tale;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\TestDox;
use Tests;
use Tests\TestCase;

final class ReprocessCoversTest extends TestCase
{
    #[TestDox('it throws error when tale does not exist')]
    public function testNonexistentTale(): void
    {
        $this->a('reprocess:covers --tale nonexistent-tale')
            ->expectsOutput('Tale does not exist.')
            ->assertExitCode(1);
    }

    #[TestDox('it throws error when tale does not have a cover')]
    public function testNoCover(): void
    {
        Tale::factory()->noCover()->create(['title' => 'Test tale']);

        $this->a('reprocess:covers --tale test-tale')
            ->expectsOutput('Tale does not have a cover.')
            ->assertExitCode(1);
    }

    #[TestDox('it works with single tale')]
    public function testSingleTale(): void
    {
        Storage::fake('testing');

        Tale::factory()->cover('test.jpg')->create(['title' => 'Test tale']);

        // Photo by David Grandmougin on Unsplash
        Cover::disk()->put(
            'covers/original/test.jpg',
            Tests\read_fixture('Images/cover.jpg'),
        );

        Queue::fake();

        $this->a('reprocess:covers --tale test-tale')->assertExitCode(0);

        Queue::assertPushedWithChain(
            GenerateTaleCoverPlaceholder::class,
            [GenerateImageVariants::class],
        );
    }

    #[TestDox('it asks for confirmation when processing all covers')]
    public function testConfirmation(): void
    {
        $this->a('reprocess:covers')
            ->expectsConfirmation('Do you want to reprocess all covers?', 'no')
            ->assertExitCode(1);
    }
}
