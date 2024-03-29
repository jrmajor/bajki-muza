<?php

namespace App\Http\Requests;

use App\Images\Values\ArtistPhotoCrop;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class StoreArtist extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
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
            'photo_grayscale' => ['boolean'],
            'photo_source' => ['string', 'max:128', 'nullable'],
            'photo_uri' => ['string', 'ends_with:.jpg,.jpeg', 'nullable'],
            'remove_photo' => ['boolean', 'nullable'],
        ];
    }

    /**
     * @return array{crop: ?ArtistPhotoCrop, grayscale: bool, source: ?string}
     */
    public function photoData(): array
    {
        $crop = $this['photo_crop'];

        return [
            'crop' => $crop ? ArtistPhotoCrop::fromArray($crop) : null,
            'grayscale' => $this->boolean('photo_grayscale'),
            'source' => $this->photo_source,
        ];
    }

    public function uploadedPhoto(): UploadedFile|string|null
    {
        return $this->oneFile('photo') ?? $this->photo_uri;
    }
}
