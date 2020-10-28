<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Tale extends Model
{
    use HasSlug, HasFactory;

    public $fillable = [
        'title', 'year', 'director_id', 'nr',
    ];

    protected $casts = [
        'year' => 'int',
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

    public function cover($size = null)
    {
        if (optional($this->attributes)['cover'] === null) {
            return;
        }

        if ($size === null) {
            return $this->cover;
        }

        if ($size === 'original') {
            return Storage::cloud()->temporaryUrl(
                "covers/original/$this->cover",
                now()->addMinutes(15)
            );
        }

        return Storage::cloud()->url("covers/$size/$this->cover");
    }

    public function director()
    {
        return $this->belongsTo('App\Models\Artist', 'director_id');
    }

    public function lyricists()
    {
        return $this->belongsToMany('App\Models\Artist', 'tales_lyricists')
            ->withPivot('credit_nr')->withTimestamps()
            ->orderBy('tales_lyricists.credit_nr');
    }

    public function composers()
    {
        return $this->belongsToMany('App\Models\Artist', 'tales_composers')
            ->withPivot('credit_nr')->withTimestamps()
            ->orderBy('tales_composers.credit_nr');
    }

    public function actors()
    {
        return $this->belongsToMany('App\Models\Artist', 'tales_actors')
            ->withPivot('credit_nr', 'characters')->withTimestamps()
            ->orderBy('tales_actors.credit_nr');
    }
}
