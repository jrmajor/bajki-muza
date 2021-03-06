<?php

namespace App\Jobs;

use App\Models\Artist;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class RefreshArtistsCache implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    public function handle()
    {
        Artist::chunk(50, function (Collection $artists) {
            foreach ($artists as $artist) {
                $artist->refreshCache();
            }
        });
    }
}
