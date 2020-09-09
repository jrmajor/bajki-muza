<?php

namespace App;

use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Artist extends Model
{
    use HasSlug, HasFactory;

    public $fillable = [
        'name', 'discogs', 'filmpolski', 'wikipedia',
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
        return $this->discogs ? "https://www.discogs.com/artist/$this->discogs" : null;
    }

    public function getFilmpolskiUrlAttribute()
    {
        return $this->filmpolski ? "http://www.filmpolski.pl/fp/index.php?osoba=$this->filmpolski" : null;
    }

    public function getWikipediaUrlAttribute()
    {
        return $this->filmpolski ? "https://pl.wikipedia.org/wiki/$this->wikipedia" : null;
    }

    public function getWikipediaExtractAttribute()
    {
        if (! $this->wikipedia) {
            return null;
        }

        return Cache::remember("artist-$this->id-wiki", CarbonInterval::week(), function () {
            $response = Http::get('https://pl.wikipedia.org/w/api.php', [
                'action' => 'query',
                'titles' => $this->wikipedia,
                'prop' => 'extracts',
                'exintro' => 1,
                'redirects' => 1,
                'format' => 'json',
            ]);

            return Arr::first($response['query']['pages'])['extract'] ?? null;
        });
    }

    public function photos()
    {
        if (! $this->discogs) {
            return;
        }

        return Cache::remember("artist-$this->id-photo", CarbonInterval::week(), function () {
            $artist = Http::withHeaders([
                'Authorization' => 'Discogs token='.config('services.discogs.token'),
            ])->get("https://api.discogs.com/artists/$this->discogs")->json();

            return [
                'normal' => $artist['images']['0']['uri'] ?? null,
                '150' => $artist['images']['0']['uri150'] ?? null,
            ];
        });
    }

    public function photo($type = 'normal')
    {
        return $this->photos()[$type] ?? null;
    }

    public function asDirector()
    {
        return $this->hasMany('App\Tale', 'director_id');
    }

    public function asLyricist()
    {
        return $this->belongsToMany('App\Tale', 'tales_lyricists')
            ->withTimestamps()
            ->orderBy('year')->orderBy('title');
    }

    public function asComposer()
    {
        return $this->belongsToMany('App\Tale', 'tales_composers')
            ->withTimestamps()
            ->orderBy('year')->orderBy('title');
    }

    public function asActor()
    {
        return $this->belongsToMany('App\Tale', 'tales_actors')
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
            )->select(DB::raw('count(*) as appearances'))
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
        return Cache::forget("artist-$this->id-photo")
            && Cache::forget("artist-$this->id-wiki");
    }
}
