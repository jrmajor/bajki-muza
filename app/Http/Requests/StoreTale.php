<?php

namespace App\Http\Requests;

use App\Models\Artist;
use App\Values\CreditData;
use App\Values\CreditType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rules\Enum;

class StoreTale extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['string', 'max:100'],
            'year' => ['digits:4', 'nullable'],
            'nr' => ['string', 'max:4', 'nullable'],

            'discogs' => ['integer', 'nullable'],

            'cover' => ['file', 'mimes:jpeg,png', 'nullable'],
            'remove_cover' => ['boolean', 'nullable'],

            'credits.*.artist' => ['string', 'max:100', 'required'],
            'credits.*.type' => [new Enum(CreditType::class), 'required'],
            'credits.*.as' => ['string', 'max:32', 'nullable'],
            'credits.*.nr' => ['integer', 'required'],

            'actors.*.artist' => ['string', 'max:100'],
            'actors.*.characters' => ['string', 'max:100', 'nullable'],
            'actors.*.credit_nr' => ['integer'],

            'notes' => ['string', 'max:4096', 'nullable'],
        ];
    }

    public function creditsData(): Collection
    {
        return collect($this->credits)
            ->groupBy(fn ($credit) => Artist::findBySlugOrNew($credit['artist'])->id)
            ->map->mapInto(CreditData::class);
    }

    public function actorsData(): Collection
    {
        return collect($this->actors)
            ->keyBy(fn ($credit) => Artist::findBySlugOrNew($credit['artist'])->id)
            ->map(fn ($credit) => [
                'characters' => $credit['characters'],
                'credit_nr' => $credit['credit_nr'],
            ]);
    }
}
