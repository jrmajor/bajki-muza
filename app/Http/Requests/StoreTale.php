<?php

namespace App\Http\Requests;

use App\Values\CreditType;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\Enum\Laravel\Rules\EnumRule;

class StoreTale extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => ['string', 'max:100'],
            'year' => ['digits:4', 'nullable'],
            'nr' => ['string', 'max:4', 'nullable'],

            'cover' => ['file', 'mimes:jpeg,png', 'nullable'],
            'remove_cover' => ['boolean', 'nullable'],

            'credits.*.artist' => ['string', 'max:100', 'required'],
            'credits.*.type' => [new EnumRule(CreditType::class), 'required'],
            'credits.*.nr' => ['integer', 'required'],

            'actors.*.artist' => ['string', 'max:100'],
            'actors.*.credit_nr' => ['integer'],
            'actors.*.characters' => ['string', 'max:100', 'nullable'],
        ];
    }
}
