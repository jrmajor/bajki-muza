<?php

use App\Artist;
use App\Tale;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tale = new Tale();
        $tale->slug = 'ali-baba-i-czterdziestu-rozbojnikow';
        $tale->title = 'Ali Baba i czterdziestu rozbójników';
        $tale->year = '1969';
        $director = Artist::where('slug', 'wieslaw-opalek')->get()->first();
        $tale->director_id = $director->id;
        $tale->nr = '10';
        $tale->cover = 'c88e2f5e64d806adfaab6986bd8aec90';
        $tale->save();
        DB::update(
            'update tales set created_at = ?, updated_at = ? where id = ?',
            ['2019-08-25 03:35:51', '2019-08-25 05:04:10', $tale->id]
        );

        $tale = new Tale();
        $tale->slug = 'alicja-w-krainie-czarow';
        $tale->title = 'Alicja w krainie czarów';
        $tale->year = '1976';
        $director = Artist::where('slug', 'wieslaw-opalek')->get()->first();
        $tale->director_id = $director->id;
        $tale->nr = '2';
        $tale->cover = '5836f196f72d4d69cdd19364bf50900f';
        $tale->save();
        DB::update(
            'update tales set created_at = ?, updated_at = ? where id = ?',
            ['2019-08-25 03:28:27', '2019-08-25 05:04:32', $tale->id]
        );

        $tale = new Tale();
        $tale->slug = 'calineczka';
        $tale->title = 'Calineczka';
        $tale->year = '1979';
        $director = Artist::where('slug', 'wieslaw-opalek')->get()->first();
        $tale->director_id = $director->id;
        $tale->nr = '27';
        $tale->cover = '63418332e1112c2c334ca7fdf5d59622';
        $tale->save();
        DB::update(
            'update tales set created_at = ?, updated_at = ? where id = ?',
            ['2019-08-23 06:30:37', '2019-08-25 04:16:02', $tale->id]
        );

        $tale = new Tale();
        $tale->slug = 'czarodziejski-mlyn';
        $tale->title = 'Czarodziejski Młyn';
        $tale->year = '1973';
        $director = Artist::where('slug', 'ludwik-rene')->get()->first();
        $tale->director_id = $director->id;
        $tale->nr = '41';
        $tale->cover = '5f553001aaee338faba5c96a956650f4';
        $tale->save();
        DB::update(
            'update tales set created_at = ?, updated_at = ? where id = ?',
            ['2019-08-23 08:02:11', '2019-08-25 04:11:48', $tale->id]
        );

        $tale = new Tale();
        $tale->slug = 'deszczem-wyszywane';
        $tale->title = 'Deszczem wyszywane';
        $tale->year = '1989';
        $director = Artist::where('slug', 'slawomir-pietrzykowski')->get()->first();
        $tale->director_id = $director->id;
        $tale->nr = '85';
        $tale->cover = '63aa620414ea24730cff56754c05c6e7';
        $tale->save();
        DB::update(
            'update tales set created_at = ?, updated_at = ? where id = ?',
            ['2019-08-26 04:09:41', '2019-08-26 04:09:41', $tale->id]
        );

        $tale = new Tale();
        $tale->slug = 'doktor-nieboli';
        $tale->title = 'Doktor Nieboli';
        $tale->year = '1987';
        $director = Artist::where('slug', 'wojciech-maciejewski')->get()->first();
        $tale->director_id = $director->id;
        $tale->nr = '38';
        $tale->cover = '487160f3e291efe763e5720ef117e971';
        $tale->save();
        DB::update(
            'update tales set created_at = ?, updated_at = ? where id = ?',
            ['2019-08-23 07:58:25', '2019-08-25 04:19:30', $tale->id]
        );

        $tale = new Tale();
        $tale->slug = 'drzewko-aby-baby';
        $tale->title = 'Drzewko Aby Baby';
        $tale->year = '1986';
        $director = Artist::where('slug', 'krzysztof-wierzbianski')->get()->first();
        $tale->director_id = $director->id;
        $tale->nr = '58';
        $tale->cover = '3019cfd0454e8c057e6af28b7b19e074';
        $tale->save();
        DB::update(
            'update tales set created_at = ?, updated_at = ? where id = ?',
            ['2019-08-23 08:34:08', '2019-08-25 04:21:26', $tale->id]
        );

        $tale = new Tale();
        $tale->slug = 'krol-bul';
        $tale->title = 'Król Bul';
        $tale->year = '1981';
        $director = Artist::where('slug', 'andrzej-pruski')->get()->first();
        $tale->director_id = $director->id;
        $tale->nr = '';
        $tale->cover = 'fa10a83ce933b6fbfb539d9e037bf40a';
        $tale->save();
        DB::update(
            'update tales set created_at = ?, updated_at = ? where id = ?',
            ['2019-08-23 09:53:29', '2019-08-25 04:18:13', $tale->id]
        );

        $tale = new Tale();
        $tale->slug = 'lampa-aladyna';
        $tale->title = 'Lampa Aladyna';
        $tale->year = '1982';
        $director = Artist::where('slug', 'tadeusz-bartosik')->get()->first();
        $tale->director_id = $director->id;
        $tale->nr = '73';
        $tale->cover = 'cf35ed2a2da84f5ee58484852fbe283f';
        $tale->save();
        DB::update(
            'update tales set created_at = ?, updated_at = ? where id = ?',
            ['2019-08-25 04:48:34', '2019-08-25 04:48:34', $tale->id]
        );

        $tale = new Tale();
        $tale->slug = 'legenda-o-sielawowym-krolu';
        $tale->title = 'Legenda o sielawowym królu';
        $tale->year = '1988';
        $director = Artist::where('slug', 'andrzej-pruski')->get()->first();
        $tale->director_id = $director->id;
        $tale->nr = '34b';
        $tale->cover = '04e9327a11961a173ad03cc6bb42ee0c';
        $tale->save();
        DB::update(
            'update tales set created_at = ?, updated_at = ? where id = ?',
            ['2019-08-25 22:15:40', '2019-08-26 04:02:46', $tale->id]
        );

        $tale = new Tale();
        $tale->slug = 'o-dwoch-takich-co-ukradli-ksiezyc';
        $tale->title = 'O dwóch takich co ukradli księżyc';
        $tale->year = '1976';
        $director = Artist::where('slug', 'wieslaw-opalek')->get()->first();
        $tale->director_id = $director->id;
        $tale->nr = '28';
        $tale->cover = '7681e9ebb8bc22bfaa2dad3f947ddb8c';
        $tale->save();
        DB::update(
            'update tales set created_at = ?, updated_at = ? where id = ?',
            ['2019-08-25 01:55:33', '2019-08-25 04:13:24', $tale->id]
        );

        $tale = new Tale();
        $tale->slug = 'o-krasnoludkach-i-sierotce-marysi';
        $tale->title = 'O krasnoludkach i sierotce Marysi';
        $tale->year = '1972';
        $director = Artist::where('slug', 'wieslaw-opalek')->get()->first();
        $tale->director_id = $director->id;
        $tale->nr = '26';
        $tale->cover = 'c4fde6e42c164433c4477730c06ffcef';
        $tale->save();
        DB::update(
            'update tales set created_at = ?, updated_at = ? where id = ?',
            ['2019-08-25 02:33:32', '2019-08-25 04:08:40', $tale->id]
        );

        $tale = new Tale();
        $tale->slug = 'o-tadku-niejadku-babci-i-dziadku';
        $tale->title = 'O Tadku-Niejadku, babci i dziadku';
        $tale->year = '1972';
        $director = Artist::where('slug', 'wieslaw-opalek')->get()->first();
        $tale->director_id = $director->id;
        $tale->nr = '11';
        $tale->cover = '3b8846ef9d3d0f1807edde1da2027aa0';
        $tale->save();
        DB::update(
            'update tales set created_at = ?, updated_at = ? where id = ?',
            ['2019-08-25 02:13:14', '2019-08-25 04:09:54', $tale->id]
        );

        $tale = new Tale();
        $tale->slug = 'opowiesc-o-pra-pra-sloniu';
        $tale->title = 'Opowieść o pra-pra-słoniu';
        $tale->year = '1987';
        $director = Artist::where('slug', 'jan-zelnik')->get()->first();
        $tale->director_id = $director->id;
        $tale->nr = '12';
        $tale->cover = '438c47652d01e11289d4a3959a566132';
        $tale->save();
        DB::update(
            'update tales set created_at = ?, updated_at = ? where id = ?',
            ['2019-08-25 05:03:04', '2019-08-25 05:03:04', $tale->id]
        );

        $tale = new Tale();
        $tale->slug = 'pan-twardowski-na-kogucie';
        $tale->title = 'Pan Twardowski na kogucie';
        $tale->year = '1981';
        $director = Artist::where('slug', 'jan-zelnik')->get()->first();
        $tale->director_id = $director->id;
        $tale->nr = '36';
        $tale->cover = '3bbf98604fa0200ad10114f86b2cb8ca';
        $tale->save();
        DB::update(
            'update tales set created_at = ?, updated_at = ? where id = ?',
            ['2019-08-25 04:42:20', '2019-08-25 04:42:20', $tale->id]
        );

        $tale = new Tale();
        $tale->slug = 'przygody-piotrusia-pana';
        $tale->title = 'Przygody Piotrusia Pana';
        $tale->year = '1978';
        $director = Artist::where('slug', 'wieslaw-opalek')->get()->first();
        $tale->director_id = $director->id;
        $tale->nr = '8';
        $tale->cover = '490437831b2fc678f6a740080ca348e2';
        $tale->save();
        DB::update(
            'update tales set created_at = ?, updated_at = ? where id = ?',
            ['2019-08-23 06:08:02', '2019-08-25 04:13:53', $tale->id]
        );

        $tale = new Tale();
        $tale->slug = 'tanczace-krasnoludki';
        $tale->title = 'Tańczące krasnoludki';
        $tale->year = '1978';
        $director = Artist::where('slug', 'wieslaw-opalek')->get()->first();
        $tale->director_id = $director->id;
        $tale->nr = '31';
        $tale->cover = '71b017dded52f62e53cdc9d6431de0b7';
        $tale->save();
        DB::update(
            'update tales set created_at = ?, updated_at = ? where id = ?',
            ['2019-08-25 04:28:56', '2019-08-25 04:28:56', $tale->id]
        );

        $tale = new Tale();
        $tale->slug = 'tomcio-paluch';
        $tale->title = 'Tomcio Paluch';
        $tale->year = '1978';
        $director = Artist::where('slug', 'wieslaw-opalek')->get()->first();
        $tale->director_id = $director->id;
        $tale->nr = '19';
        $tale->cover = '2e3d4cfa3f48c1669c5d463896049010';
        $tale->save();
        DB::update(
            'update tales set created_at = ?, updated_at = ? where id = ?',
            ['2019-08-23 09:49:43', '2019-08-25 04:15:48', $tale->id]
        );

        $tale = new Tale();
        $tale->slug = 'wielka-niedzwiedzica';
        $tale->title = 'Wielka niedźwiedzica';
        $tale->year = '1985';
        $director = Artist::where('slug', 'marek-kulesza')->get()->first();
        $tale->director_id = $director->id;
        $tale->nr = '54';
        $tale->cover = '21a38c2d3e4aecf9121d6f7e9df8e1d2';
        $tale->save();
        DB::update(
            'update tales set created_at = ?, updated_at = ? where id = ?',
            ['2019-08-25 04:54:34', '2019-08-25 04:54:34', $tale->id]
        );
    }
}
