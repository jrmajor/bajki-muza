<?php

namespace Tests\Unit\Images;

use App\Images\Cover;
use App\Images\Jobs\GenerateImageVariants;
use App\Images\Jobs\GenerateTaleCoverPlaceholder;
use App\Images\Jobs\GenerateTaleCoverVariants;
use App\Models\Tale;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

final class CoverTest extends TestCase
{
    #[TestDox('it can get list of sizes')]
    public function testSizes(): void
    {
        $this->assertSame([60, 90, 120, 128, 192, 288, 256, 384], Cover::sizes());
    }

    #[TestDox('it can get list of variants')]
    public function testVariants(): void
    {
        $this->assertSame(['default'], Cover::variants());
    }

    #[TestDox('it stores new cover in correct path and dispatches necessary jobs to process it')]
    public function testStore(): void
    {
        Storage::fake('testing');

        Queue::fake();

        $image = Cover::store(UploadedFile::fake()->image('test.jpg'));

        $this->assertStringEndsWith('.jpg', $image->filename());

        $this->assertCount(1, Cover::disk()->files('covers/original'));

        Queue::assertPushedWithChain(
            GenerateTaleCoverPlaceholder::class,
            [GenerateImageVariants::class, GenerateTaleCoverVariants::class],
        );
    }

    #[TestDox('it returns correct original path')]
    public function testOriginalPath(): void
    {
        $this->assertSame(
            'covers/original/test.jpg',
            (new Cover(['filename' => 'test.jpg']))->originalPath(),
        );
    }

    #[TestDox('it returns correct path for given size')]
    public function testSizePath(): void
    {
        $this->assertSame(
            'covers/384/test.jpg',
            (new Cover(['filename' => 'test.jpg']))->path(384),
        );
    }

    #[TestDox('it can get its tales')]
    public function testTales(): void
    {
        $cover = Cover::create([
            'filename' => 'tXySLaaEbhfyzLXm6QggZY5VSFulyN2xLp4OgYSy.png',
        ]);

        $tales = Tale::factory(2)->cover($cover)->create();

        $cover->refresh();

        $this->assertCount(2, $cover->tales);
        $this->assertSameModel($tales[0], $cover->tales[0]);
        $this->assertSameModel($tales[1], $cover->tales[1]);
    }
}
