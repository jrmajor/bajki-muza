<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArtist extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['string', 'max:100'],

            'discogs' => ['integer', 'nullable'],
            'filmpolski' => ['integer', 'nullable'],
            'wikipedia' => ['string', 'max:100', 'nullable'],

            'photo' => ['file', 'mimes:jpeg,png', 'nullable'],
            'photo_source' => ['string', 'max:128', 'nullable'],
            'photo_uri' => ['string', 'ends_with:.jpg', 'nullable'],
            'remove_photo' => ['boolean', 'nullable'],
        ];
    }
}
