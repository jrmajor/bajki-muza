<?php

namespace App\Models;

use App\Images\Cover;
use App\Services\Discogs;
use App\Values\CreditData;
use App\Values\CreditType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Psl\Type;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @property-read Actor|Credit|null $credit
 */
final class Tale extends Model
{
    use HasFactory;
    use HasSlug;

    /** @var list<string> */
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
        return $this->discogs ? app(Discogs::class)->releaseUrl($this->discogs) : null;
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

    /**
     * @return EloquentCollection<int, Artist>
     */
    public function creditsFor(CreditType $type): EloquentCollection
    {
        return $this->credits
            ->filter(fn (Artist $a) => $a->credit->ofType($type))
            ->values();
    }

    /**
     * @return Collection<string, EloquentCollection<int, Artist>>
     */
    public function customCredits(): Collection
    {
        /** @phpstan-ignore-next-line */
        return $this->credits
            ->filter(fn (Artist $a) => $a->credit->isCustom())
            ->sortBy(fn (Artist $a) => $a->credit->type->order())
            ->groupBy(fn (Artist $a) => $a->credit->as ?? $a->credit->type->label());
    }

    /**
     * @return EloquentCollection<int, Artist>
     */
    public function orderedCredits(): EloquentCollection
    {
        return $this->credits
            ->sortBy(fn (Artist $a) => $a->credit->type->order())
            ->values();
    }

    /**
     * @param array<int, list<CreditData>> $credits (keys are artists ids)
     */
    public function syncCredits(array $credits): void
    {
        $allCreditsToSync = collect($credits)->map(collect(...));

        // Delete credits for artists who don't exist in new credit list.
        $this->credits()->whereIntegerNotInRaw(
            'artists.id', $allCreditsToSync->keys(),
        )->get()->map(function (Artist $a) {
            return Type\instance_of(Credit::class)->coerce($a->credit);
        })->each(function (Credit $credit): void {
            $credit->delete();
        });

        // Refresh existing credits after deleting some of them
        // and format them the same way input is formatted.
        $allExistingCredits = $this->credits()->get()
            ->groupBy('id')
            ->map->map(fn ($artist) => $artist->credit);

        foreach ($allCreditsToSync as $artistId => $newCredits) {
            /** @var Collection<int, Credit> */
            $existingCredits = collect($allExistingCredits[$artistId] ?? []);

            // Add new credits if artist should have more of them.
            // They will be filled with more data and saved later on.
            while ($newCredits->count() > $existingCredits->count()) {
                $existingCredits->push(
                    new Credit(['tale_id' => $this->id, 'artist_id' => $artistId]),
                );
            }

            // Remove some credits if artist should have less.
            while ($existingCredits->count() > $newCredits->count()) {
                /** @phpstan-ignore-next-line */
                $existingCredits->pop()->delete();
            }

            // Now that we have as many credits, as we should, we can update them.
            foreach ($newCredits as $credit) {
                // Can't use Model::update() because Pivot::$exists = false
                /** @phpstan-ignore-next-line */
                $existingCredits->shift()
                    ->fill($credit->toArray())->save();
            }
        }
    }

    public function scopeWithActorsPopularity(Builder $query): void
    {
        $query->addSelect([
            'popularity' => DB::table(
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
