<?php

namespace App\Models;

use App\Values\ArtistPhotoCrop;
use App\Values\CreditType;
use App\Values\Discogs\PhotoCollection as DiscogsPhotoCollection;
use Facades\App\Services\Discogs;
use Facades\App\Services\FilmPolski;
use Facades\App\Services\Wikipedia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Artist extends Model
{
    use HasSlug, HasFactory;

    public $fillable = [
        'name', 'discogs', 'filmpolski', 'wikipedia', 'photo_source',
    ];

    protected $casts = [
        'discogs' => 'int',
        'filmpolski' => 'int',
        'photo_width' => 'int',
        'photo_height' => 'int',
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
        $query = self::where('slug', '=', $slug);

        return $query->first($columns);
    }

    public static function findBySlugOrNew(string $name): self
    {
        $artist = self::findBySlug(Str::slug($name));

        if ($artist == null) {
            $artist = new self();
            $artist->name = $name;
            $artist->save();
        }

        return $artist;
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

    public function photo($size = null): ?string
    {
        if (optional($this->attributes)['photo'] === null) {
            return null;
        }

        if ($size === null) {
            return $this->photo;
        }

        if ($size === 'original') {
            return Storage::cloud()->temporaryUrl(
                "photos/original/$this->photo",
                now()->addMinutes(15)
            );
        }

        return Storage::cloud()->url("photos/$size/$this->photo");
    }

    public function removePhoto(): bool
    {
        return $this->forceFill([
            'photo' => null,
            'photo_source' => null,
            'photo_width' => null,
            'photo_height' => null,
            'photo_crop' => null,
            'photo_face_placeholder' => null,
            'photo_placeholder' => null,
        ])->save();
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
        if ($type === 'normal') {
            return optional($this->discogsPhotos()->primary())->uri;
        }

        if ($type === '150') {
            return optional($this->discogsPhotos()->primary())->uri150;
        }

        throw new InvalidArgumentException();
    }

    public function credits(): BelongsToMany
    {
        return $this->belongsToMany(Tale::class, 'credits')
            ->using(Credit::class)->as('credit')
            ->withPivot('type', 'nr')->withTimestamps()
            ->orderBy('year')->orderBy('title');
    }

    public function creditsAs(CreditType $type): Collection
    {
        return $this->credits
                    ->filter(fn ($credit) => $credit->credit->ofType($type))
                    ->values();
    }

    public function asActor(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\Tale', 'tales_actors')
            ->withPivot('characters')->withTimestamps()
            ->orderBy('year')->orderBy('title');
    }

    public function scopeCountAppearances(Builder $query): void
    {
        $query->addSelect(['appearances' => DB::table(
                DB::table('credits')->select('id')
                    ->whereColumn('artist_id', 'artists.id')
                ->unionAll(DB::table('tales_actors')->select('id')
                    ->whereColumn('artist_id', 'artists.id')
                )
            )->select(DB::raw('count(*) as appearances')),
        ])->withCasts(['appearances' => 'int']);
    }

    public function appearances(): int
    {
        return DB::table(
                DB::table('credits')->select('id')
                    ->where('artist_id', $this->id)
                ->unionAll(DB::table('tales_actors')->select('id')
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
