<?php

use Illuminate\Database\Migrations\Migration;

class MigrateDirectorsDataToCreditsTable extends Migration
{
    public function up()
    {
        $directors = DB::table('tales')
            ->whereNotNull('director_id')->get()
            ->map(fn ($tale) => [
                'tale_id' => $tale->id,
                'artist_id' => $tale->director_id,
                'type' => CreditType::director(),
                'nr' => 0,
                'created_at' => $tale->created_at,
                'updated_at' => $tale->updated_at,
            ])->all();

        DB::table('credits')->insert($directors);
    }
}
