<?php

use App\Models\Actor;
use App\Models\Credit;
use App\Values\CreditType;

test('actor pivot casts values', function () {
    $casts = (new Actor())->getCasts();

    expect($casts['credit_nr'])->toBe('int');
});

test('credit pivot casts values', function () {
    $casts = (new Credit())->getCasts();

    expect($casts['type'])->toBe(CreditType::class);
    expect($casts['nr'])->toBe('int');
});

test('ofType method works in credit pivot', function () {
    $credit = new Credit(['type' => CreditType::Text]);

    expect($credit->ofType(CreditType::Text))->toBeTrue();
    expect($credit->ofType(CreditType::Music))->toBeFalse();
});
