<?php

namespace App;

use App\Traits\FindsBySlug;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Tale extends Model
{
    use HasSlug;

    public $fillable = [
        'title', 'year', 'director_id', 'nr', 'cover',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(100);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function cover($size)
    {
        return "https://lastfm.freetls.fastly.net/i/u/$size/$this->cover.webp";
    }

    public function director()
    {
        return $this->belongsTo('App\Artist', 'director_id');
    }

    public function lyricists()
    {
        return $this->belongsToMany('App\Artist', 'tales_lyricists')
            ->withPivot('credit_nr')->withTimestamps()
            ->orderBy('tales_lyricists.credit_nr');
    }

    public function composers()
    {
        return $this->belongsToMany('App\Artist', 'tales_composers')
            ->withPivot('credit_nr')->withTimestamps()
            ->orderBy('tales_composers.credit_nr');
    }

    public function actors()
    {
        return $this->belongsToMany('App\Artist', 'tales_actors')
            ->withPivot('credit_nr', 'characters')->withTimestamps()
            ->orderBy('tales_actors.credit_nr');
    }

    public function editData()
    {
        $data = [
            'title'     => $this->title,
            'year'      => $this->year,
            'nr'        => $this->nr,
            'cover'     => $this->cover,
            'lyricists' => [],
            'composers' => [],
            'actors'    => [],
        ];

        if ($this->director) {
            $data['director'] = $this->director->name;
        }

        $i = 0;
        foreach ($this->lyricists()->orderBy('credit_nr')->get() as $lyricist) {
            $data['lyricists'][$i]['credit_nr'] = $lyricist->pivot->credit_nr;
            $data['lyricists'][$i]['artist'] = $lyricist->name;
            $i++;
        }

        $i = 0;
        foreach ($this->composers()->orderBy('credit_nr')->get() as $composer) {
            $data['composers'][$i]['credit_nr'] = $composer->pivot->credit_nr;
            $data['composers'][$i]['artist'] = $composer->name;
            $i++;
        }

        $i = 0;
        foreach ($this->actors()->orderBy('credit_nr')->get() as $actor) {
            $data['actors'][$i]['credit_nr'] = $actor->pivot->credit_nr;
            $data['actors'][$i]['artist'] = $actor->name;
            $data['actors'][$i]['characters'] = $actor->pivot->characters;
            $i++;
        }

        return $data;
    }
}
