<?php

namespace App\Models;

use App\Images\Photo;
use App\Images\Values\ArtistPhotoCrop;
use App\Values\CreditType;
use App\Values\Discogs\PhotoCollection as DiscogsPhotoCollection;
use Facades\App\Services\Discogs;
use Facades\App\Services\FilmPolski;
use Facades\App\Services\Wikipedia;
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

class Artist extends Model
{
    use HasSlug, HasFactory;

    protected $with = ['photo'];

    public $fillable = [
        'name', 'genetivus',
        'discogs', 'filmpolski', 'wikipedia',
    ];

    protected $casts = [
        'discogs' => 'int',
        'filmpolski' => 'int',
        'photo_crop' => ArtistPhotoCrop::class,
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

    public static function findBySlug(string $slug, array $columns = ['*']): ?self
    {
        $query = self::where('slug', $slug);

        return $query->first($columns);
    }

    public static function findBySlugOrNew(string $name): self
    {
        $artist = self::findBySlug(Str::slug($name));

        return $artist ?? tap((new self(compact('name'))))->save();
    }

    public function getDiscogsUrlAttribute(): ?string
    {
        return $this->discogs ? Discogs::url($this->discogs) : null;
    }

    public function getFilmpolskiUrlAttribute(): ?string
    {
        return $this->filmpolski ? FilmPolski::url($this->filmpolski) : null;
    }

    public function getWikipediaUrlAttribute(): ?string
    {
        return $this->wikipedia ? Wikipedia::url($this->wikipedia) : null;
    }

    public function photo(): BelongsTo
    {
        return $this->belongsTo(Photo::class, 'photo_filename', 'filename');
    }

    public function getWikipediaExtractAttribute(): ?string
    {
        if (! $this->wikipedia) {
            return null;
        }

        return Wikipedia::extract($this->wikipedia);
    }

    public function discogsPhotos(): DiscogsPhotoCollection
    {
        if (! $this->discogs) {
            return new DiscogsPhotoCollection();
        }

        return Discogs::photos($this->discogs);
    }

    public function filmPolskiPhotos(): array
    {
        if (! $this->filmpolski) {
            return [];
        }

        return FilmPolski::photos($this->filmpolski);
    }

    public function discogsPhoto(string $type = 'normal'): ?string
    {
        return match ($type) {
            'normal' => $this->discogsPhotos()->primary()?->uri,
            '150' => $this->discogsPhotos()->primary()?->uri150,
            default => throw new InvalidArgumentException(),
        };
    }

    public function asActor(): BelongsToMany
    {
        return $this->belongsToMany(Tale::class, 'tales_actors')
            ->withPivot('characters')->withTimestamps()
            ->orderBy('year')->orderBy('title');
    }

    public function credits(): BelongsToMany
    {
        return $this->belongsToMany(Tale::class, 'credits')
            ->using(Credit::class)->as('credit')
            ->withPivot('id', 'type', 'as', 'nr')->withTimestamps()
            ->orderBy('year')->orderBy('title');
    }

    public function creditsFor(CreditType $type): EloquentCollection
    {
        return $this->credits
            ->filter(fn ($tale) => $tale->credit->ofType($type))
            ->values();
    }

    public function orderedCredits(): Collection
    {
        return $this->credits
            ->sortBy(fn ($tale) => $tale->credit->type->order())
            ->groupBy(fn ($tale) => $tale->credit->type->label);
    }

    public function scopeCountAppearances(Builder $query): void
    {
        $query->addSelect(['appearances' => DB::table(
                DB::table('credits')->select('tale_id')
                    ->whereColumn('artist_id', 'artists.id')
                ->union(DB::table('tales_actors')->select('tale_id')
                    ->whereColumn('artist_id', 'artists.id')
                )
            )->select(DB::raw('count(*) as appearances')),
        ])->withCasts(['appearances' => 'int']);
    }

    public function appearances(): int
    {
        return DB::table(
                DB::table('credits')->select('tale_id')
                    ->where('artist_id', $this->id)
                ->union(DB::table('tales_actors')->select('tale_id')
                    ->where('artist_id', $this->id)
                )
            )->count();
    }

    public function flushCache(): bool
    {
        return ($this->discogs ? Discogs::forget($this->discogs) : true)
            && ($this->filmpolski ? FilmPolski::forget($this->filmpolski) : true)
            && ($this->wikipedia ? Wikipedia::forget($this->wikipedia) : true);
    }
}
