<?php

namespace App\Models;

use App\Values\ArtistPhotoCrop;
use Facades\App\Services\Discogs;
use Facades\App\Services\FilmPolski;
use Facades\App\Services\Wikipedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public static function findBySlug($id, $columns = ['*'])
    {
        $query = self::where('slug', '=', $id);

        return $query->first($columns);
    }

    public static function findBySlugOrNew($name)
    {
        $artist = self::findBySlug(Str::slug($name));

        if ($artist == null) {
            $artist = new self();
            $artist->name = $name;
            $artist->save();
        }

        return $artist;
    }

    public function getDiscogsUrlAttribute()
    {
        return $this->discogs ? Discogs::url($this->discogs) : null;
    }

    public function getFilmpolskiUrlAttribute()
    {
        return $this->filmpolski ? FilmPolski::url($this->filmpolski) : null;
    }

    public function getWikipediaUrlAttribute()
    {
        return $this->wikipedia ? Wikipedia::url($this->wikipedia) : null;
    }

    public function photo($size = null)
    {
        if (optional($this->attributes)['photo'] === null) {
            return;
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

    public function getWikipediaExtractAttribute(): ?string
    {
        if (! $this->wikipedia) {
            return null;
        }

        return Wikipedia::extract($this->wikipedia);
    }

    public function discogsPhotos(): array
    {
        if (! $this->discogs) {
            return [];
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

    public function discogsPhoto($type = 'normal'): ?string
    {
        $type = $type == '150' ? 'uri150' : 'uri';

        return $this->discogsPhotos()['0'][$type] ?? null;
    }

    public function asDirector()
    {
        return $this->hasMany('App\Models\Tale', 'director_id')
            ->orderBy('year')->orderBy('title');
    }

    public function asLyricist()
    {
        return $this->belongsToMany('App\Models\Tale', 'tales_lyricists')
            ->withTimestamps()
            ->orderBy('year')->orderBy('title');
    }

    public function asComposer()
    {
        return $this->belongsToMany('App\Models\Tale', 'tales_composers')
            ->withTimestamps()
            ->orderBy('year')->orderBy('title');
    }

    public function asActor()
    {
        return $this->belongsToMany('App\Models\Tale', 'tales_actors')
            ->withPivot('characters')->withTimestamps()
            ->orderBy('year')->orderBy('title');
    }

    public function scopeCountAppearances($query)
    {
        $query->addSelect(['appearances' => DB::table(
                DB::table('tales')->select('id')
                    ->whereColumn('director_id', 'artists.id')
                ->unionAll(DB::table('tales_lyricists')->select('id')
                    ->whereColumn('artist_id', 'artists.id')
                )->unionAll(DB::table('tales_composers')->select('id')
                    ->whereColumn('artist_id', 'artists.id')
                )->unionAll(DB::table('tales_actors')->select('id')
                    ->whereColumn('artist_id', 'artists.id')
                )
            )->select(DB::raw('count(*) as appearances')),
        ])->withCasts(['appearances' => 'int']);
    }

    public function appearances()
    {
        return DB::table(
                DB::table('tales')->select('id')
                    ->where('director_id', $this->id)
                ->unionAll(DB::table('tales_lyricists')->select('id')
                    ->where('artist_id', $this->id)
                )->unionAll(DB::table('tales_composers')->select('id')
                    ->where('artist_id', $this->id)
                )->unionAll(DB::table('tales_actors')->select('id')
                    ->where('artist_id', $this->id)
                )
            )->count();
    }

    public function flushCache()
    {
        return ($this->discogs ? Discogs::forget($this->discogs) : true)
            && ($this->filmpolski ? FilmPolski::forget($this->filmpolski) : true)
            && ($this->wikipedia ? Wikipedia::forget($this->wikipedia) : true);
    }
}
