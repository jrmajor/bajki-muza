<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\FindsBySlug;

class Tale extends Model
{
    use FindsBySlug;

    public function cover($size) {
        return "https://lastfm-img2.akamaized.net/i/u/{$size}/$this->cover.webp";
    }

    public function director() {
        return $this->belongsTo('App\Artist', 'director_id');
    }

    public function lyricists() {
        return $this->belongsToMany('App\Artist', 'tales_lyricists')->withPivot('credit_nr')->withTimestamps();
    }

    public function composers() {
        return $this->belongsToMany('App\Artist', 'tales_composers')->withPivot('credit_nr')->withTimestamps();
    }

    public function actors() {
        return $this->belongsToMany('App\Artist', 'tales_actors')->withPivot('credit_nr', 'characters')->withTimestamps();
    }

    public function editData() {
        $data = [
            'title' => $this->title,
            'year' => $this->year,
            'director' => $this->director->name,
            'nr' => $this->nr,
            'cover' => $this->cover,
            'lyricists' => [],
            'composers' => [],
            'actors' => [],
        ];

        $i = 0;
        foreach($this->lyricists()->get() as $lyricist) {
            $data['lyricists'][$i]['credit_nr'] = $lyricist->pivot->credit_nr;
            $data['lyricists'][$i]['artist'] = $lyricist->name;
            $i++;
        }

        $i = 0;
        foreach($this->composers()->get() as $composer) {
            $data['composers'][$i]['credit_nr'] = $composer->pivot->credit_nr;
            $data['composers'][$i]['artist'] = $composer->name;
            $i++;
        }

        $i = 0;
        foreach($this->actors()->get() as $actor) {
            $data['actors'][$i]['credit_nr'] = $actor->pivot->credit_nr;
            $data['actors'][$i]['artist'] = $actor->name;
            $data['actors'][$i]['characters'] = $actor->pivot->characters;
            $i++;
        }

        return $data;
    }
}
