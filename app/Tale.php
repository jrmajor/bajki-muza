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
}
