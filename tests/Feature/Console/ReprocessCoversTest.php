<?php

use App\Jobs\ProcessTaleCover;
use App\Models\Tale;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Bus;
use function Tests\fixture;
use function Pest\Laravel\artisan;

it('throws error when tale doesn\'t exist')
    ->artisan('reprocess:covers --tale nonexistent-tale')
    ->expectsOutput('Tale doesn\'t exist.')
    ->assertExitCode(1);

it('throws error when tale doesn\'t have a cover', function () {
    Tale::factory()->create([
        'title' => 'Test tale',
        'cover' => null,
    ]);

    artisan('reprocess:covers --tale test-tale')
        ->expectsOutput('Tale doesn\'t have a cover.')
        ->assertExitCode(1);
});

it('works with single tale', function () {
    Tale::factory()->create([
        'title' => 'Test tale',
        'cover' => 'test.jpg',
    ]);

    // Photo by David Grandmougin on Unsplash
    $file = fopen(fixture('Images/cover.jpg'), 'r');

    Storage::cloud()->put("covers/original/test.jpg", $file, 'public');

    Bus::fake();

    artisan('reprocess:covers --tale test-tale')
        ->assertExitCode(0);

    Bus::assertDispatched(ProcessTaleCover::class);

    Storage::cloud()->delete("covers/original/test.jpg");
})->only();

it('asks for confirmation when processing all covers')
    ->artisan('reprocess:covers')
    ->expectsConfirmation('Do you want to reprocess all covers?', 'no')
    ->assertExitCode(1);
