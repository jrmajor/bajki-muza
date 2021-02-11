<?php

namespace App\Http\Requests;

use App\Images\Values\ArtistPhotoCrop;
use Illuminate\Foundation\Http\FormRequest;

class StoreArtist extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['string', 'max:100'],
            'genetivus' => ['string', 'max:100', 'nullable'],

            'discogs' => ['integer', 'nullable'],
            'filmpolski' => ['integer', 'nullable'],
            'wikipedia' => ['string', 'max:100', 'nullable'],

            'photo' => ['file', 'mimes:jpeg,png', 'nullable'],
            'photo_crop' => ['array', 'required_with:photo,photo_uri'],
            'photo_crop.face.x' => ['integer'],
            'photo_crop.face.y' => ['integer'],
            'photo_crop.face.size' => ['integer'],
            'photo_crop.image.x' => ['integer'],
            'photo_crop.image.y' => ['integer'],
            'photo_crop.image.width' => ['integer'],
            'photo_crop.image.height' => ['integer'],
            'photo_source' => ['string', 'max:128', 'nullable'],
            'photo_uri' => ['string', 'ends_with:.jpg', 'nullable'],
            'remove_photo' => ['boolean', 'nullable'],
        ];
    }

    public function photoCrop(): ?ArtistPhotoCrop
    {
        return $this->photo_crop ?? false
            ? ArtistPhotoCrop::fromStrings($this->photo_crop)
            : null;
    }
}
