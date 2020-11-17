<?php

use App\Models\Credit;
use App\Values\CreditType;

it('casts values', function () {
    $casts = (new Credit())->getCasts();

    expect($casts['type'])->toBe(CreditType::class);
    expect($casts['nr'])->toBe('int');
});

test('ofType method works', function () {
    $credit = new Credit([
        'type' => CreditType::lyricist(),
    ]);

    expect($credit->ofType(CreditType::lyricist()))->toBeTrue();
    expect($credit->ofType(CreditType::composer()))->toBeFalse();
});
