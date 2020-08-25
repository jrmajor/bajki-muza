<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'director' => ['string', 'max:100', 'nullable'],
            'nr' => ['string', 'max:4', 'nullable'],
            'cover' => ['file', 'mimes:jpeg,png', 'nullable'],
            'remove_cover' => ['boolean', 'nullable'],

            'lyricists.*.artist' => ['string', 'max:100'],
            'lyricists.*.credit_nr' => ['integer'],

            'composers.*.artist' => ['string', 'max:100'],
            'composers.*.credit_nr' => ['integer'],

            'actors.*.artist' => ['string', 'max:100'],
            'actors.*.credit_nr' => ['integer'],
            'actors.*.characters' => ['string', 'max:100', 'nullable'],
        ];
    }
}
