<?php

use App\Artist;
use App\Tale;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TalesLyricistsSeeder extends Seeder
{
    public function run()
    {
        $artist = Artist::where('slug', 'krystyna-wodnicka')->get()->first();
        $tale = Tale::where('slug', 'przygody-piotrusia-pana')->get()->first();
        $tale->lyricists()->attach(
            $artist->id,
            ['credit_nr' => null]
        );
        DB::update(
            'update tales_lyricists set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 06:08:02', '2019-08-23 06:08:02', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'elzbieta-bussold')->get()->first();
        $tale = Tale::where('slug', 'calineczka')->get()->first();
        $tale->lyricists()->attach(
            $artist->id,
            ['credit_nr' => null]
        );
        DB::update(
            'update tales_lyricists set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 06:30:37', '2019-08-25 01:44:58', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'krystyna-wodnicka')->get()->first();
        $tale = Tale::where('slug', 'doktor-nieboli')->get()->first();
        $tale->lyricists()->attach(
            $artist->id,
            ['credit_nr' => null]
        );
        DB::update(
            'update tales_lyricists set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 07:58:25', '2019-08-25 04:45:36', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'alina-afanasjew')->get()->first();
        $tale = Tale::where('slug', 'czarodziejski-mlyn')->get()->first();
        $tale->lyricists()->attach(
            $artist->id,
            ['credit_nr' => 1]
        );
        DB::update(
            'update tales_lyricists set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 08:02:11', '2019-08-23 08:02:11', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'jerzy-afanasjew')->get()->first();
        $tale = Tale::where('slug', 'czarodziejski-mlyn')->get()->first();
        $tale->lyricists()->attach(
            $artist->id,
            ['credit_nr' => 2]
        );
        DB::update(
            'update tales_lyricists set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 08:02:11', '2019-08-23 08:02:11', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'miroslaw-lebkowski')->get()->first();
        $tale = Tale::where('slug', 'drzewko-aby-baby')->get()->first();
        $tale->lyricists()->attach(
            $artist->id,
            ['credit_nr' => 1]
        );
        DB::update(
            'update tales_lyricists set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 08:34:10', '2019-08-25 04:21:26', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'stanislaw-werner')->get()->first();
        $tale = Tale::where('slug', 'drzewko-aby-baby')->get()->first();
        $tale->lyricists()->attach(
            $artist->id,
            ['credit_nr' => 2]
        );
        DB::update(
            'update tales_lyricists set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 08:34:10', '2019-08-25 04:21:26', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'antoni-marianowicz')->get()->first();
        $tale = Tale::where('slug', 'tomcio-paluch')->get()->first();
        $tale->lyricists()->attach(
            $artist->id,
            ['credit_nr' => null]
        );
        DB::update(
            'update tales_lyricists set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 09:49:43', '2019-08-25 01:46:28', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'katarzyna-lengren')->get()->first();
        $tale = Tale::where('slug', 'krol-bul')->get()->first();
        $tale->lyricists()->attach(
            $artist->id,
            ['credit_nr' => null]
        );
        DB::update(
            'update tales_lyricists set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 09:53:29', '2019-08-25 01:47:30', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'krystyna-wodnicka')->get()->first();
        $tale = Tale::where('slug', 'o-dwoch-takich-co-ukradli-ksiezyc')->get()->first();
        $tale->lyricists()->attach(
            $artist->id,
            ['credit_nr' => null]
        );
        DB::update(
            'update tales_lyricists set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 01:55:33', '2019-08-25 01:55:33', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'wanda-chotomska')->get()->first();
        $tale = Tale::where('slug', 'o-tadku-niejadku-babci-i-dziadku')->get()->first();
        $tale->lyricists()->attach(
            $artist->id,
            ['credit_nr' => null]
        );
        DB::update(
            'update tales_lyricists set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 02:13:14', '2019-08-25 02:13:14', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'janina-gillowa')->get()->first();
        $tale = Tale::where('slug', 'o-krasnoludkach-i-sierotce-marysi')->get()->first();
        $tale->lyricists()->attach(
            $artist->id,
            ['credit_nr' => 1]
        );
        DB::update(
            'update tales_lyricists set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 02:33:32', '2019-08-26 01:15:56', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'wieslaw-opalek')->get()->first();
        $tale = Tale::where('slug', 'o-krasnoludkach-i-sierotce-marysi')->get()->first();
        $tale->lyricists()->attach(
            $artist->id,
            ['credit_nr' => 2]
        );
        DB::update(
            'update tales_lyricists set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 02:33:32', '2019-08-26 01:15:56', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'antoni-marianowicz')->get()->first();
        $tale = Tale::where('slug', 'alicja-w-krainie-czarow')->get()->first();
        $tale->lyricists()->attach(
            $artist->id,
            ['credit_nr' => null]
        );
        DB::update(
            'update tales_lyricists set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 03:28:27', '2019-08-25 05:04:32', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'antoni-marianowicz')->get()->first();
        $tale = Tale::where('slug', 'ali-baba-i-czterdziestu-rozbojnikow')->get()->first();
        $tale->lyricists()->attach(
            $artist->id,
            ['credit_nr' => null]
        );
        DB::update(
            'update tales_lyricists set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 03:35:51', '2019-08-26 01:15:30', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'antoni-marianowicz')->get()->first();
        $tale = Tale::where('slug', 'tanczace-krasnoludki')->get()->first();
        $tale->lyricists()->attach(
            $artist->id,
            ['credit_nr' => null]
        );
        DB::update(
            'update tales_lyricists set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:28:56', '2019-08-26 01:16:15', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'antoni-marianowicz')->get()->first();
        $tale = Tale::where('slug', 'pan-twardowski-na-kogucie')->get()->first();
        $tale->lyricists()->attach(
            $artist->id,
            ['credit_nr' => 1]
        );
        DB::update(
            'update tales_lyricists set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:42:20', '2019-08-25 04:42:20', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'jacek-podkomorzy')->get()->first();
        $tale = Tale::where('slug', 'lampa-aladyna')->get()->first();
        $tale->lyricists()->attach(
            $artist->id,
            ['credit_nr' => null]
        );
        DB::update(
            'update tales_lyricists set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:48:34', '2019-08-25 04:48:34', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'stefan-majchrowski')->get()->first();
        $tale = Tale::where('slug', 'wielka-niedzwiedzica')->get()->first();
        $tale->lyricists()->attach(
            $artist->id,
            ['credit_nr' => null]
        );
        DB::update(
            'update tales_lyricists set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:54:34', '2019-08-25 04:54:34', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'elzbieta-bussold')->get()->first();
        $tale = Tale::where('slug', 'opowiesc-o-pra-pra-sloniu')->get()->first();
        $tale->lyricists()->attach(
            $artist->id,
            ['credit_nr' => null]
        );
        DB::update(
            'update tales_lyricists set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 05:03:04', '2019-08-25 05:03:04', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'wanda-chotomska')->get()->first();
        $tale = Tale::where('slug', 'legenda-o-sielawowym-krolu')->get()->first();
        $tale->lyricists()->attach(
            $artist->id,
            ['credit_nr' => null]
        );
        DB::update(
            'update tales_lyricists set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 22:15:41', '2019-08-26 04:02:46', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'dorota-gellner')->get()->first();
        $tale = Tale::where('slug', 'deszczem-wyszywane')->get()->first();
        $tale->lyricists()->attach(
            $artist->id,
            ['credit_nr' => null]
        );
        DB::update(
            'update tales_lyricists set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-26 04:09:41', '2019-08-26 04:09:41', $artist->id, $tale->id]
        );
    }
}
