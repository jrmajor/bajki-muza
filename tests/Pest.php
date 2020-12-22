<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Assert;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class)->in(__DIR__);

expect()->extend('toBeModel', function ($expected) {
    $this->toBeInstanceOf(Model::class);

    Assert::assertTrue(
        $this->value->is($expected),
        'Value is not expected Eloquent model.',
    );

    return $this;
});
