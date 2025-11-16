<?php

namespace Tests\Unit\Models;

use App\Models\Actor;
use App\Models\Credit;
use App\Values\CreditType;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

final class PivotsTest extends TestCase
{
    #[TestDox('actor pivot casts values')]
    public function testActor(): void
    {
        $casts = new Actor()->getCasts();

        $this->assertSame('int', $casts['credit_nr']);
    }

    #[TestDox('credit pivot casts values')]
    public function testCredit(): void
    {
        $casts = new Credit()->getCasts();

        $this->assertSame(CreditType::class, $casts['type']);
        $this->assertSame('int', $casts['nr']);
    }

    #[TestDox('ofType method works in credit pivot')]
    public function testOfType(): void
    {
        $credit = new Credit(['type' => CreditType::Text]);

        $this->assertTrue($credit->ofType(CreditType::Text));
        $this->assertFalse($credit->ofType(CreditType::Music));
    }
}
