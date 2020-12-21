<?php

namespace App\Models;

use App\Images\Cover;
use App\Values\CreditType;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Tale extends Model
{
    use HasSlug, HasFactory;

    public $fillable = [
        'title', 'year', 'nr', 'notes',
    ];

    protected $casts = [
        'year' => 'int',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(100);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function cover(): BelongsTo
    {
        return $this->belongsTo(Cover::class, 'cover_filename', 'filename');
    }

    public function actors(): BelongsToMany
    {
        return $this->belongsToMany(Artist::class, 'tales_actors')
            ->withPivot('credit_nr', 'characters')->withTimestamps()
            ->orderBy('tales_actors.credit_nr');
    }

    public function credits(): BelongsToMany
    {
        return $this->belongsToMany(Artist::class, 'credits')
            ->using(Credit::class)->as('credit')
            ->withPivot('type', 'as', 'nr')->withTimestamps()
            ->orderBy('credits.nr');
    }

    public function creditsFor(CreditType $type): EloquentCollection
    {
        return $this->credits
            ->filter(fn ($artist) => $artist->credit->ofType($type))
            ->values();
    }

    public function customCredits(): Collection
    {
        return $this->credits
            ->filter(fn ($artist) => $artist->credit->isCustom())
            ->sortBy(fn ($artist) => $artist->credit->type->order())
            ->groupBy(function ($artist) {
                return $artist->credit->as ?? $artist->credit->type->label;
            });
    }

    public function orderedCredits(): Collection
    {
        return $this->credits
            ->sortBy(fn ($artist) => $artist->credit->type->order())
            ->values();
    }
}
