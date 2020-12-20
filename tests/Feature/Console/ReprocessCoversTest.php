<?php

use App\Images\Jobs\GenerateTaleCoverPlaceholder;
use App\Images\Jobs\GenerateTaleCoverVariants;
use App\Models\Tale;
use Illuminate\Support\Facades\Queue;
use function Pest\Laravel\artisan;
use function Tests\fixture;

it('throws error when tale does not exist')
    ->artisan('reprocess:covers --tale nonexistent-tale')
    ->expectsOutput('Tale does not exist.')
    ->assertExitCode(1);

it('throws error when tale does not have a cover', function () {
    Tale::factory()->create([
        'title' => 'Test tale',
        'cover' => null,
    ]);

    artisan('reprocess:covers --tale test-tale')
        ->expectsOutput('Tale does not have a cover.')
        ->assertExitCode(1);
});

it('works with single tale', function () {
    Storage::fake('testing');

    Tale::factory()->create([
        'title' => 'Test tale',
        'cover' => 'test.jpg',
    ]);

    // Photo by David Grandmougin on Unsplash
    $file = fopen(fixture('Images/cover.jpg'), 'r');

    Storage::cloud()->put('covers/original/test.jpg', $file, 'public');

    Queue::fake();

    artisan('reprocess:covers --tale test-tale')
        ->assertExitCode(0);

    Queue::assertPushedWithChain(
        GenerateTaleCoverPlaceholder::class,
        [GenerateTaleCoverVariants::class],
    );
});

it('asks for confirmation when processing all covers')
    ->artisan('reprocess:covers')
    ->expectsConfirmation('Do you want to reprocess all covers?', 'no')
    ->assertExitCode(1);
