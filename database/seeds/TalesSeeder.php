<?php

use App\Models\Artist;
use App\Models\Tale;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TalesSeeder extends Seeder
{
    public function run()
    {
        $tale = new Tale();
        $tale->title = 'Ali Baba i czterdziestu rozbójników';
        $tale->year = '1969';
        $director = Artist::where('slug', 'wieslaw-opalek')->get()->first();
        $tale->director_id = $director->id;
        $tale->nr = '10';
        $tale->save();
        DB::update(
            'update tales set created_at = ?, updated_at = ? where id = ?',
            ['2019-08-25 03:35:51', '2019-08-25 05:04:10', $tale->id]
        );

        $tale = new Tale();
        $tale->title = 'Alicja w krainie czarów';
        $tale->year = '1976';
        $director = Artist::where('slug', 'wieslaw-opalek')->get()->first();
        $tale->director_id = $director->id;
        $tale->nr = '2';
        $tale->save();
        DB::update(
            'update tales set created_at = ?, updated_at = ? where id = ?',
            ['2019-08-25 03:28:27', '2019-08-25 05:04:32', $tale->id]
        );

        $tale = new Tale();
        $tale->title = 'Calineczka';
        $tale->year = '1979';
        $director = Artist::where('slug', 'wieslaw-opalek')->get()->first();
        $tale->director_id = $director->id;
        $tale->nr = '27';
        $tale->save();
        DB::update(
            'update tales set created_at = ?, updated_at = ? where id = ?',
            ['2019-08-23 06:30:37', '2019-08-25 04:16:02', $tale->id]
        );

        $tale = new Tale();
        $tale->title = 'Czarodziejski Młyn';
        $tale->year = '1973';
        $director = Artist::where('slug', 'ludwik-rene')->get()->first();
        $tale->director_id = $director->id;
        $tale->nr = '41';
        $tale->save();
        DB::update(
            'update tales set created_at = ?, updated_at = ? where id = ?',
            ['2019-08-23 08:02:11', '2019-08-25 04:11:48', $tale->id]
        );

        $tale = new Tale();
        $tale->title = 'Deszczem wyszywane';
        $tale->year = '1989';
        $director = Artist::where('slug', 'slawomir-pietrzykowski')->get()->first();
        $tale->director_id = $director->id;
        $tale->nr = '85';
        $tale->save();
        DB::update(
            'update tales set created_at = ?, updated_at = ? where id = ?',
            ['2019-08-26 04:09:41', '2019-08-26 04:09:41', $tale->id]
        );

        $tale = new Tale();
        $tale->title = 'Doktor Nieboli';
        $tale->year = '1987';
        $director = Artist::where('slug', 'wojciech-maciejewski')->get()->first();
        $tale->director_id = $director->id;
        $tale->nr = '38';
        $tale->save();
        DB::update(
            'update tales set created_at = ?, updated_at = ? where id = ?',
            ['2019-08-23 07:58:25', '2019-08-25 04:19:30', $tale->id]
        );

        $tale = new Tale();
        $tale->title = 'Drzewko Aby Baby';
        $tale->year = '1986';
        $director = Artist::where('slug', 'krzysztof-wierzbianski')->get()->first();
        $tale->director_id = $director->id;
        $tale->nr = '58';
        $tale->save();
        DB::update(
            'update tales set created_at = ?, updated_at = ? where id = ?',
            ['2019-08-23 08:34:08', '2019-08-25 04:21:26', $tale->id]
        );

        $tale = new Tale();
        $tale->title = 'Król Bul';
        $tale->year = '1981';
        $director = Artist::where('slug', 'andrzej-pruski')->get()->first();
        $tale->director_id = $director->id;
        $tale->nr = '';
        $tale->save();
        DB::update(
            'update tales set created_at = ?, updated_at = ? where id = ?',
            ['2019-08-23 09:53:29', '2019-08-25 04:18:13', $tale->id]
        );

        $tale = new Tale();
        $tale->title = 'Lampa Aladyna';
        $tale->year = '1982';
        $director = Artist::where('slug', 'tadeusz-bartosik')->get()->first();
        $tale->director_id = $director->id;
        $tale->nr = '73';
        $tale->save();
        DB::update(
            'update tales set created_at = ?, updated_at = ? where id = ?',
            ['2019-08-25 04:48:34', '2019-08-25 04:48:34', $tale->id]
        );

        $tale = new Tale();
        $tale->title = 'Legenda o sielawowym królu';
        $tale->year = '1988';
        $director = Artist::where('slug', 'andrzej-pruski')->get()->first();
        $tale->director_id = $director->id;
        $tale->nr = '34b';
        $tale->save();
        DB::update(
            'update tales set created_at = ?, updated_at = ? where id = ?',
            ['2019-08-25 22:15:40', '2019-08-26 04:02:46', $tale->id]
        );

        $tale = new Tale();
        $tale->title = 'O dwóch takich co ukradli księżyc';
        $tale->year = '1976';
        $director = Artist::where('slug', 'wieslaw-opalek')->get()->first();
        $tale->director_id = $director->id;
        $tale->nr = '28';
        $tale->save();
        DB::update(
            'update tales set created_at = ?, updated_at = ? where id = ?',
            ['2019-08-25 01:55:33', '2019-08-25 04:13:24', $tale->id]
        );

        $tale = new Tale();
        $tale->title = 'O krasnoludkach i sierotce Marysi';
        $tale->year = '1972';
        $director = Artist::where('slug', 'wieslaw-opalek')->get()->first();
        $tale->director_id = $director->id;
        $tale->nr = '26';
        $tale->save();
        DB::update(
            'update tales set created_at = ?, updated_at = ? where id = ?',
            ['2019-08-25 02:33:32', '2019-08-25 04:08:40', $tale->id]
        );

        $tale = new Tale();
        $tale->title = 'O Tadku-Niejadku, babci i dziadku';
        $tale->year = '1972';
        $director = Artist::where('slug', 'wieslaw-opalek')->get()->first();
        $tale->director_id = $director->id;
        $tale->nr = '11';
        $tale->save();
        DB::update(
            'update tales set created_at = ?, updated_at = ? where id = ?',
            ['2019-08-25 02:13:14', '2019-08-25 04:09:54', $tale->id]
        );

        $tale = new Tale();
        $tale->title = 'Opowieść o pra-pra-słoniu';
        $tale->year = '1987';
        $director = Artist::where('slug', 'jan-zelnik')->get()->first();
        $tale->director_id = $director->id;
        $tale->nr = '12';
        $tale->save();
        DB::update(
            'update tales set created_at = ?, updated_at = ? where id = ?',
            ['2019-08-25 05:03:04', '2019-08-25 05:03:04', $tale->id]
        );

        $tale = new Tale();
        $tale->title = 'Pan Twardowski na kogucie';
        $tale->year = '1981';
        $director = Artist::where('slug', 'jan-zelnik')->get()->first();
        $tale->director_id = $director->id;
        $tale->nr = '36';
        $tale->save();
        DB::update(
            'update tales set created_at = ?, updated_at = ? where id = ?',
            ['2019-08-25 04:42:20', '2019-08-25 04:42:20', $tale->id]
        );

        $tale = new Tale();
        $tale->title = 'Przygody Piotrusia Pana';
        $tale->year = '1978';
        $director = Artist::where('slug', 'wieslaw-opalek')->get()->first();
        $tale->director_id = $director->id;
        $tale->nr = '8';
        $tale->save();
        DB::update(
            'update tales set created_at = ?, updated_at = ? where id = ?',
            ['2019-08-23 06:08:02', '2019-08-25 04:13:53', $tale->id]
        );

        $tale = new Tale();
        $tale->title = 'Tańczące krasnoludki';
        $tale->year = '1978';
        $director = Artist::where('slug', 'wieslaw-opalek')->get()->first();
        $tale->director_id = $director->id;
        $tale->nr = '31';
        $tale->save();
        DB::update(
            'update tales set created_at = ?, updated_at = ? where id = ?',
            ['2019-08-25 04:28:56', '2019-08-25 04:28:56', $tale->id]
        );

        $tale = new Tale();
        $tale->title = 'Tomcio Paluch';
        $tale->year = '1978';
        $director = Artist::where('slug', 'wieslaw-opalek')->get()->first();
        $tale->director_id = $director->id;
        $tale->nr = '19';
        $tale->save();
        DB::update(
            'update tales set created_at = ?, updated_at = ? where id = ?',
            ['2019-08-23 09:49:43', '2019-08-25 04:15:48', $tale->id]
        );

        $tale = new Tale();
        $tale->title = 'Wielka niedźwiedzica';
        $tale->year = '1985';
        $director = Artist::where('slug', 'marek-kulesza')->get()->first();
        $tale->director_id = $director->id;
        $tale->nr = '54';
        $tale->save();
        DB::update(
            'update tales set created_at = ?, updated_at = ? where id = ?',
            ['2019-08-25 04:54:34', '2019-08-25 04:54:34', $tale->id]
        );
    }
}
