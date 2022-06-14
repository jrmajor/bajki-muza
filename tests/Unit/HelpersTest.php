<?php

namespace Tests\Unit;

use App;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

final class HelpersTest extends TestCase
{
    #[TestDox('append_to_filename helper works')]
    public function testAppendToFilename(): void
    {
        $this->assertSame(
            'desiredFilename_tiny.jpg',
            App\append_to_file_name('/var/folders/0k/T/desiredFilename.jpg', 'tiny'),
        );

        $this->assertSame(
            'test.temp.jpeg',
            App\append_to_file_name('test.jpeg', 'temp', '.'),
        );
    }
}
