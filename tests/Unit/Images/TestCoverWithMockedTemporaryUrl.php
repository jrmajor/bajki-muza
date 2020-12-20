<?php

namespace Tests\Unit\Images;

use Carbon\Carbon;
use Illuminate\Filesystem\FilesystemAdapter;
use Mockery;

class TestCoverWithMockedTemporaryUrl extends TestCover
{
    protected function disk(): FilesystemAdapter
    {
        return Mockery::mock(FilesystemAdapter::class)
            ->shouldReceive('temporaryUrl')
            ->with('covers/original/testFilename.jpg', Carbon::class)
            ->andReturn('testUrl')
            ->mock();
    }
}
