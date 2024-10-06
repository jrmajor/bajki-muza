<?php

namespace App\Models;

use App\Images\Photo;
use App\Services\Discogs;
use App\Services\FilmPolski;
use App\Services\Wikipedia;
use App\Values\CreditType;
use App\Values\Discogs\DiscogsPhotos;
use App\Values\FilmPolski\PhotoGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @property-read Actor|Credit|null $credit
 */
final class Artist extends Model
{
    use HasFactory;
    use HasSlug;

    /** @var list<string> */
    protected $with = ['photo'];

    public $fillable = [
        'name', 'genetivus',
        'discogs', 'filmpolski', 'wikipedia',
    ];

    protected $casts = [
        'discogs' => 'int',
        'filmpolski' => 'int',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(100);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public static function findBySlugOrNew(string $name): self
    {
        $artist = self::findBySlug(Str::slug($name));

        return $artist ?? self::create(['name' => $name]);
    }

    protected static function booted(): void
    {
        self::updated(function (self $artist) {
            if ($artist->isDirty('discogs', 'filmpolski', 'wikipedia')) {
                $artist->flushCache();
            }
        });
    }

    public function getDiscogsUrlAttribute(): ?string
    {
        return $this->discogs ? app(Discogs::class)->url($this->discogs) : null;
    }

    public function getFilmpolskiUrlAttribute(): ?string
    {
        return $this->filmpolski ? app(FilmPolski::class)->url($this->filmpolski) : null;
    }

    public function getWikipediaUrlAttribute(): ?string
    {
        return $this->wikipedia ? app(Wikipedia::class)->url($this->wikipedia) : null;
    }

    public function photo(): BelongsTo
    {
        return $this->belongsTo(Photo::class);
    }

    public function getWikipediaExtractAttribute(): ?string
    {
        if (! $this->wikipedia) {
            return null;
        }

        return app(Wikipedia::class)->extract($this->wikipedia);
    }

    public function discogsPhotos(): DiscogsPhotos
    {
        return $this->discogs ? app(Discogs::class)->photos($this->discogs) : new DiscogsPhotos([]);
    }

    /**
     * @return list<PhotoGroup>
     */
    public function filmPolskiPhotos(): array
    {
        return $this->filmpolski ? app(FilmPolski::class)->photos($this->filmpolski) : [];
    }

    public function discogsPhoto(string $type = 'normal'): ?string
    {
        return match ($type) {
            'normal' => $this->discogsPhotos()->primary()?->uri,
            'thumb' => $this->discogsPhotos()->primary()?->thumbUri,
            default => throw new InvalidArgumentException(),
        };
    }

    public function asActor(): BelongsToMany
    {
        return $this->belongsToMany(Tale::class, 'tales_actors')
            ->using(Actor::class)->as('credit')
            ->withPivot('characters', 'credit_nr')->withTimestamps()
            ->orderBy('year')->orderBy('title');
    }

    public function credits(): BelongsToMany
    {
        return $this->belongsToMany(Tale::class, 'credits')
            ->using(Credit::class)->as('credit')
            ->withPivot('id', 'type', 'as', 'nr')->withTimestamps()
            ->orderBy('year')->orderBy('title');
    }

    /**
     * @return EloquentCollection<int, Tale>
     */
    public function creditsFor(CreditType $type): EloquentCollection
    {
        return $this->credits
            ->filter(fn (Tale $t) => $t->credit->ofType($type))
            ->values();
    }

    /**
     * @return Collection<string, EloquentCollection<int, Tale>>
     */
    public function orderedCredits(): Collection
    {
        return $this->credits
            ->sortBy(fn (Tale $t) => $t->credit->type->order())
            ->groupBy(fn (Tale $t) => $t->credit->type->label());
    }

    public function scopeCountAppearances(Builder $query): void
    {
        $query->addSelect([
            'appearances' => DB::table(
                DB::table('credits')->select('tale_id')->whereColumn('artist_id', 'artists.id')->union(
                    DB::table('tales_actors')->select('tale_id')->whereColumn('artist_id', 'artists.id'),
                ),
            )->selectRaw('count(*) as appearances'),
        ])->withCasts(['appearances' => 'int']);
    }

    public function appearances(): int
    {
        return DB::table('credits')->select('tale_id')->where('artist_id', $this->id)->union(
            DB::table('tales_actors')->select('tale_id')->where('artist_id', $this->id),
        )->count();
    }

    public function refreshCache(): void
    {
        if ($this->discogs) {
            app(Discogs::class)->refreshCache($this->discogs);
        }

        if ($this->filmpolski) {
            app(FilmPolski::class)->refreshCache($this->filmpolski);
        }

        if ($this->wikipedia) {
            app(Wikipedia::class)->refreshCache($this->wikipedia);
        }
    }

    public function flushCache(): bool
    {
        return ($this->discogs ? app(Discogs::class)->forget($this->discogs) : true)
            && ($this->filmpolski ? app(FilmPolski::class)->forget($this->filmpolski) : true)
            && ($this->wikipedia ? app(Wikipedia::class)->forget($this->wikipedia) : true);
    }
}
