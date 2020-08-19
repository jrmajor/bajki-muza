<?php

namespace App;

use App\Traits\FindsBySlug;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Artist extends Model
{
    use FindsBySlug;

    public static function findBySlugOrNew($name)
    {
        $artist = self::findBySlug(Str::slug($name));
        if ($artist == null) {
            $artist = new self();
            $artist->slug = Str::slug($name);
            $artist->name = $name;
            $artist->save();
        }

        return $artist;
    }

    public function countAppearances()
    {
        $count = DB::select('
            select count(*) as count
            from (
                select id from tales where director_id = ?
                union all
                select id from tales_lyricists where artist_id = ?
                union all
                select id from tales_composers where artist_id = ?
                union all
                select id from tales_actors where artist_id = ?
            ) as relationships
        ', [$this->id, $this->id, $this->id, $this->id]);

        return $count[0]->count;
    }

    public function getDiscogsUrlAttribute()
    {
        return "https://www.discogs.com/artist/$this->discogs";
    }

    public function getImdbUrlAttribute()
    {
        return "https://www.imdb.com/name/nm$this->imdb";
    }

    public function getWikipediaUrlAttribute()
    {
        return "https://pl.wikipedia.org/wiki/$this->wikipedia";
    }

    public function getWikipediaExtractAttribute()
    {
        if (! $this->wikipedia) {
            return false;
        }

        return Cache::remember("artist-$this->id-wiki", CarbonInterval::week(), function () {
            $response = Http::get('https://pl.wikipedia.org/w/api.php', [
                'action' => 'query',
                'prop' => 'extracts',
                'exintro' => 1,
                'format' => 'json',
                'redirects' => 1,
                'titles' => $this->wikipedia,
            ]);

            return Arr::first($response['query']['pages'])['extract'];
        });
    }

    public function getPhotoAttribute()
    {
        if (! $this->discogs) {
            return;
        }

        return Cache::remember("a-$this->id-photo", CarbonInterval::week(), function () {
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
        return $this->photo[$type] ?? null;
    }

    public function asDirector()
    {
        return $this->hasMany('App\Tale', 'director_id');
    }

    public function asLyricist()
    {
        return $this->belongsToMany('App\Tale', 'tales_lyricists')->withTimestamps();
    }

    public function asComposer()
    {
        return $this->belongsToMany('App\Tale', 'tales_composers')->withTimestamps();
    }

    public function asActor()
    {
        return $this->belongsToMany('App\Tale', 'tales_actors')->withPivot('characters')->withTimestamps();
    }

    public function editData()
    {
        return [
            'name' => $this->name,
            'discogs' => $this->discogs,
            'imdb' => $this->imdb,
            'wikipedia' => $this->wikipedia,
        ];
    }

    public function flushCache()
    {
        Cache::forget("artist-$this->id-photo");
        Cache::forget("artist-$this->id-wiki");
    }
}
