<?php

use App\Values\CreditType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class MigrateDataToCreditsTable extends Migration
{
    public function up()
    {
        $lyricists = DB::table('tales_lyricists')->get()
            ->map(fn ($credit) => [
                'tale_id' => $credit->tale_id,
                'artist_id' => $credit->artist_id,
                'type' => CreditType::lyricist(),
                'nr' => $credit->credit_nr ?? 1,
                'created_at' => $credit->created_at,
                'updated_at' => $credit->updated_at,
            ])->all();

        DB::table('credits')->insert($lyricists);

        $composers = DB::table('tales_composers')->get()
            ->map(fn ($credit) => [
                'tale_id' => $credit->tale_id,
                'artist_id' => $credit->artist_id,
                'type' => CreditType::composer(),
                'nr' => $credit->credit_nr ?? 1,
                'created_at' => $credit->created_at,
                'updated_at' => $credit->updated_at,
            ])->all();

        DB::table('credits')->insert($composers);
    }
}
