<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\FindsBySlug;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Artist extends Model
{
    use FindsBySlug;

    public static function findBySlugOrNew($name)
    {
        $artist = self::findBySlug(Str::slug($name));
        if($artist == null) {
            $artist = new self();
            $artist->slug = Str::slug($name);
            $artist->name = $name;
            $artist->save();
        }
        return $artist;
    }

    public function countAppearances() {
        $count = DB::select("
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
        ", [$this->id, $this->id, $this->id, $this->id]);

        return $count[0]->count;
    }

    public function getDiscogsUrlAttribute() {
        return "https://www.discogs.com/artist/$this->discogs";
    }

    public function getImdbUrlAttribute() {
        return "https://www.imdb.com/name/nm$this->imdb";
    }

    public function getWikipediaUrlAttribute() {
        return "https://pl.wikipedia.org/wiki/$this->wikipedia";
    }

    public function getWikipediaExtractAttribute() {
        if (!$this->wikipedia)
            return false;

        return Cache::remember("a-$this->id-wiki-extract", 604800, function () {
            $ch = curl_init("https://pl.wikipedia.org/w/api.php");
            curl_setopt($ch, CURLOPT_POSTFIELDS, 'action=query&prop=extracts&exintro=1&format=json&redirects=1&titles=' . $this->wikipedia);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            $json = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($json, true);
            $extract = array_values($data['query']['pages'])[0]['extract'];

            return $extract;
        });
    }

    public function getPhotoAttribute() {
        if(!$this->discogs)
            return null;

        return Cache::remember("a-$this->id-photo", 604800, function () {
            $artist = app()->make('discogs')->getArtist(['id' => $this->discogs]);

            return $artist['images']['0']['uri'] ?? null;
        });
    }

    public function asDirector() {
        return $this->hasMany('App\Tale', 'director_id');
    }

    public function asLyricist() {
        return $this->belongsToMany('App\Tale', 'tales_lyricists')->withTimestamps();
    }

    public function asComposer() {
        return $this->belongsToMany('App\Tale', 'tales_composers')->withTimestamps();
    }

    public function asActor() {
        return $this->belongsToMany('App\Tale', 'tales_actors')->withPivot('characters')->withTimestamps();
    }

    public function editData() {
        return [
            'name' => $this->name,
            'discogs' => $this->discogs,
            'imdb' => $this->imdb,
            'wikipedia' => $this->wikipedia
        ];
    }

    public function flushCache() {
        Cache::forget("a-$this->id-photo");
        Cache::forget("a-$this->id-wiki-extract");
    }
}
