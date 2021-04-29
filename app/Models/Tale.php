<?php

namespace App\Models;

use App\Images\Cover;
use App\Values\CreditData;
use App\Values\CreditType;
use Facades\App\Services\Discogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

final class Tale extends Model
{
    use HasSlug;
    use HasFactory;

    protected $with = ['cover'];

    public $fillable = [
        'title', 'year', 'nr', 'notes', 'discogs',
    ];

    protected $casts = [
        'year' => 'int',
        'discogs' => 'int',
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

    public function getDiscogsUrlAttribute(): ?string
    {
        return $this->discogs ? Discogs::releaseUrl($this->discogs) : null;
    }

    public function cover(): BelongsTo
    {
        return $this->belongsTo(Cover::class);
    }

    public function actors(): BelongsToMany
    {
        return $this->belongsToMany(Artist::class, 'tales_actors')
            ->using(Actor::class)->as('credit')
            ->withPivot('characters', 'credit_nr')->withTimestamps()
            ->orderBy('tales_actors.credit_nr');
    }

    public function credits(): BelongsToMany
    {
        return $this->belongsToMany(Artist::class, 'credits')
            ->using(Credit::class)->as('credit')
            ->withPivot('id', 'type', 'as', 'nr')->withTimestamps()
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
            ->groupBy(fn ($artist) => $artist->credit->as ?? $artist->credit->type->label);
    }

    public function orderedCredits(): Collection
    {
        return $this->credits
            ->sortBy(fn ($artist) => $artist->credit->type->order())
            ->values();
    }

    /**
     * @param array<int, CreditData[]> $credits (keys are artists ids)
     */
    public function syncCredits(array|Collection $credits)
    {
        $allCreditsToSync = collect($credits)->map('collect');

        // Delete credits for artists who don't exist in new credit list.
        $this->credits()
            ->whereIntegerNotInRaw(
                'artists.id', $allCreditsToSync->keys(),
            )
            ->get()
            ->map->credit
            ->map->delete();

        // Refresh existing credits after deleting some of them
        // and format them the same way input is formatted.
        $allExistingCredits = $this->credits()->get()
            ->groupBy('id')
            ->map->map(fn ($artist) => $artist->credit);

        foreach ($allCreditsToSync as $artistId => $newCredits) {
            $existingCredits = $allExistingCredits[$artistId] ?? collect();

            // Add new credits if artist should have more of them.
            // They will be filled with more data and saved later on.
            while ($newCredits->count() > $existingCredits->count()) {
                $existingCredits->push(
                    new Credit(['tale_id' => $this->id, 'artist_id' => $artistId]),
                );
            }

            // Remove some credits if artist should have less.
            while ($existingCredits->count() > $newCredits->count()) {
                $existingCredits->pop()->delete();
            }

            // Now that we have as many credits, as we should, we can update them.
            foreach ($newCredits as $credit) {
                $existingCredits->shift()
                    ->fill($credit->toArray())->save();
            }
        }
    }

    public function scopeWithActorsPopularity(Builder $query): void
    {
        $query->addSelect(['popularity' => DB::table(
                DB::table('tales_actors')
                    ->selectSub(
                        DB::table('tales_actors', 'appearances')
                            ->whereColumn('artist_id', 'tales_actors.artist_id')
                            ->selectRaw('count(*) as appearances'),
                        as: 'appearances',
                    )->whereColumn('tales_actors.tale_id', 'tales.id'),
            )->selectRaw('sum(appearances) as popularity'),
        ])->withCasts(['popularity' => 'int']);
    }
}
