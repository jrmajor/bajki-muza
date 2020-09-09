<?php

use App\Models\Artist;
use App\Models\Tale;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TalesActorsSeeder extends Seeder
{
    public function run()
    {
        $artist = Artist::where('slug', 'mieczyslaw-hryniewicz')->get()->first();
        $tale = Tale::where('slug', 'przygody-piotrusia-pana')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 1,
                'characters' => 'Piotruś Pan',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 06:08:03', '2019-08-23 06:08:03', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'hanna-balinska')->get()->first();
        $tale = Tale::where('slug', 'przygody-piotrusia-pana')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 2,
                'characters' => 'Matka',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 06:08:03', '2019-08-23 06:08:03', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'elzbieta-kmiecinska')->get()->first();
        $tale = Tale::where('slug', 'przygody-piotrusia-pana')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 3,
                'characters' => 'Wanda',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 06:08:03', '2019-08-23 06:08:03', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'alicja-rojek')->get()->first();
        $tale = Tale::where('slug', 'przygody-piotrusia-pana')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 4,
                'characters' => 'Janek',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 06:08:03', '2019-08-23 06:08:03', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'jadwiga-lubicz')->get()->first();
        $tale = Tale::where('slug', 'przygody-piotrusia-pana')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 5,
                'characters' => 'Michał',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 06:08:03', '2019-08-23 06:08:03', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'jolanta-russek')->get()->first();
        $tale = Tale::where('slug', 'przygody-piotrusia-pana')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 6,
                'characters' => 'Wróżka',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 06:08:03', '2019-08-23 06:08:03', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'andrzej-stockinger')->get()->first();
        $tale = Tale::where('slug', 'przygody-piotrusia-pana')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 7,
                'characters' => 'Kapitan Hak',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 06:08:03', '2019-08-23 06:08:03', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'czeslaw-mroczek')->get()->first();
        $tale = Tale::where('slug', 'przygody-piotrusia-pana')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 8,
                'characters' => 'Pirat I',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 06:08:03', '2019-08-23 06:08:03', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'janusz-zakrzenski')->get()->first();
        $tale = Tale::where('slug', 'przygody-piotrusia-pana')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 9,
                'characters' => 'Pirat II',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 06:08:03', '2019-08-23 06:08:03', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'jozef-nalberczak')->get()->first();
        $tale = Tale::where('slug', 'przygody-piotrusia-pana')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 10,
                'characters' => 'Pirat III',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 06:08:03', '2019-08-23 06:08:03', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'tadeusz-bartosik')->get()->first();
        $tale = Tale::where('slug', 'przygody-piotrusia-pana')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 11,
                'characters' => 'Krokodyl',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 06:08:03', '2019-08-23 06:08:03', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'ilona-kusmierska')->get()->first();
        $tale = Tale::where('slug', 'przygody-piotrusia-pana')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 12,
                'characters' => 'Tygrysia Lilia',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 06:08:03', '2019-08-23 06:08:03', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'witold-kaluski')->get()->first();
        $tale = Tale::where('slug', 'przygody-piotrusia-pana')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 13,
                'characters' => 'Wódz',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 06:08:03', '2019-08-23 06:08:03', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'tadeusz-bartosik')->get()->first();
        $tale = Tale::where('slug', 'calineczka')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 1,
                'characters' => 'Narrator-Ogrodnik',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 06:30:37', '2019-08-25 01:44:58', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'magda-zawadzka')->get()->first();
        $tale = Tale::where('slug', 'calineczka')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 2,
                'characters' => 'Calineczka',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 06:30:37', '2019-08-25 01:44:58', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'barbara-bargielowska')->get()->first();
        $tale = Tale::where('slug', 'calineczka')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 3,
                'characters' => 'Dziecko; Chrabąszczówna; Jaskółka',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 06:30:37', '2019-08-25 01:44:58', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'ilona-kusmierska')->get()->first();
        $tale = Tale::where('slug', 'calineczka')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 4,
                'characters' => 'Dziecko; Jaskółeczka',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 06:30:37', '2019-08-25 01:44:58', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'elzbieta-kmiecinska')->get()->first();
        $tale = Tale::where('slug', 'calineczka')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 5,
                'characters' => 'Dziecko; Chrabąszczówna; Jaskółka',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 06:30:37', '2019-08-25 01:44:58', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'renata-kossobudzka')->get()->first();
        $tale = Tale::where('slug', 'calineczka')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 6,
                'characters' => 'Żaba',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 06:30:37', '2019-08-25 01:44:58', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'mieczyslaw-gajda')->get()->first();
        $tale = Tale::where('slug', 'calineczka')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 7,
                'characters' => 'Bielinek',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 06:30:38', '2019-08-25 01:44:58', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'tadeusz-wludarski')->get()->first();
        $tale = Tale::where('slug', 'calineczka')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 8,
                'characters' => 'Chrabąszcz',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 06:30:38', '2019-08-25 01:44:58', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'teresa-lipowska')->get()->first();
        $tale = Tale::where('slug', 'calineczka')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 9,
                'characters' => 'Chrabąszczowa',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 06:30:38', '2019-08-25 01:44:58', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'jan-matyjaszkiewicz')->get()->first();
        $tale = Tale::where('slug', 'calineczka')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 10,
                'characters' => 'Pająk',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 06:30:38', '2019-08-25 01:44:58', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'anna-skaros')->get()->first();
        $tale = Tale::where('slug', 'calineczka')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 11,
                'characters' => 'Mysz Polna; Jaskółka',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 06:30:38', '2019-08-25 01:44:58', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'wlodzimierz-twardowski')->get()->first();
        $tale = Tale::where('slug', 'calineczka')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 12,
                'characters' => 'Kret',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 06:30:38', '2019-08-25 01:44:58', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'emilian-kaminski')->get()->first();
        $tale = Tale::where('slug', 'calineczka')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 13,
                'characters' => 'Duszek Kwiatów',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 06:30:38', '2019-08-25 01:44:58', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'bronislaw-pawlik')->get()->first();
        $tale = Tale::where('slug', 'doktor-nieboli')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 1,
                'characters' => 'Doktor',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 07:58:25', '2019-08-25 04:45:36', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'danuta-wodynska')->get()->first();
        $tale = Tale::where('slug', 'doktor-nieboli')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 2,
                'characters' => 'Papuga Polly',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 07:58:25', '2019-08-25 04:45:36', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'wanda-majerowna')->get()->first();
        $tale = Tale::where('slug', 'doktor-nieboli')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 3,
                'characters' => 'Klara',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 07:58:25', '2019-08-25 04:45:36', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'tomasz-marzecki')->get()->first();
        $tale = Tale::where('slug', 'doktor-nieboli')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 4,
                'characters' => 'Pies Bryś',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 07:58:25', '2019-08-25 04:45:36', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'jadwiga-lubicz')->get()->first();
        $tale = Tale::where('slug', 'doktor-nieboli')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 5,
                'characters' => 'Świnka',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 07:58:25', '2019-08-25 04:45:36', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'joanna-sobieska')->get()->first();
        $tale = Tale::where('slug', 'doktor-nieboli')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 6,
                'characters' => 'Małpka Czi-Czi',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 07:58:25', '2019-08-25 04:45:36', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'janina-traczykowna')->get()->first();
        $tale = Tale::where('slug', 'doktor-nieboli')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 7,
                'characters' => 'Kaczka Taś-Taś',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 07:58:25', '2019-08-25 04:45:36', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'lech-ordon')->get()->first();
        $tale = Tale::where('slug', 'doktor-nieboli')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 8,
                'characters' => 'Krokodyl Filip',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 07:58:25', '2019-08-25 04:45:36', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'danuta-przesmycka')->get()->first();
        $tale = Tale::where('slug', 'doktor-nieboli')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 9,
                'characters' => 'Jaskółka',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 07:58:25', '2019-08-25 04:45:36', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'krystyna-miecikowna')->get()->first();
        $tale = Tale::where('slug', 'doktor-nieboli')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 10,
                'characters' => 'Delfin',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 07:58:25', '2019-08-25 04:45:36', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'jerzy-rogowski')->get()->first();
        $tale = Tale::where('slug', 'doktor-nieboli')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 11,
                'characters' => 'Delfin',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 07:58:25', '2019-08-25 04:45:36', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'marek-obertyn')->get()->first();
        $tale = Tale::where('slug', 'doktor-nieboli')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 12,
                'characters' => 'Herszt',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 07:58:26', '2019-08-25 04:45:36', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'emilian-kaminski')->get()->first();
        $tale = Tale::where('slug', 'doktor-nieboli')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 13,
                'characters' => 'Comber',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 07:58:26', '2019-08-25 04:45:36', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'igor-smialowski')->get()->first();
        $tale = Tale::where('slug', 'doktor-nieboli')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 14,
                'characters' => 'Lew',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 07:58:26', '2019-08-25 04:45:36', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'tadeusz-wludarski')->get()->first();
        $tale = Tale::where('slug', 'doktor-nieboli')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 15,
                'characters' => 'Słoń',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 07:58:26', '2019-08-25 04:45:36', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'aleksander-trabczynski')->get()->first();
        $tale = Tale::where('slug', 'doktor-nieboli')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 16,
                'characters' => 'Bambuła',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 07:58:26', '2019-08-25 04:45:36', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'eugeniusz-robaczewski')->get()->first();
        $tale = Tale::where('slug', 'doktor-nieboli')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 17,
                'characters' => 'Szympans Lolo',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 07:58:26', '2019-08-25 04:45:36', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'ilona-kusmierska')->get()->first();
        $tale = Tale::where('slug', 'doktor-nieboli')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 18,
                'characters' => 'Małpa',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 07:58:26', '2019-08-25 04:45:36', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'danuta-rastawicka')->get()->first();
        $tale = Tale::where('slug', 'doktor-nieboli')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 19,
                'characters' => 'Małpa',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 07:58:26', '2019-08-25 04:45:36', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'michal-bukowski')->get()->first();
        $tale = Tale::where('slug', 'doktor-nieboli')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 20,
                'characters' => 'Chłopiec',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 07:58:26', '2019-08-25 04:45:37', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'andrzej-bogucki')->get()->first();
        $tale = Tale::where('slug', 'czarodziejski-mlyn')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 1,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 08:02:11', '2019-08-23 08:02:11', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'zygmunt-boncza-tomaszewski')->get()->first();
        $tale = Tale::where('slug', 'czarodziejski-mlyn')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 2,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 08:02:11', '2019-08-23 08:02:11', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'aleksander-dzwonkowski')->get()->first();
        $tale = Tale::where('slug', 'czarodziejski-mlyn')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 3,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 08:02:11', '2019-08-23 08:02:11', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'wladyslaw-hancza')->get()->first();
        $tale = Tale::where('slug', 'czarodziejski-mlyn')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 4,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 08:02:12', '2019-08-23 08:02:12', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'maria-janecka')->get()->first();
        $tale = Tale::where('slug', 'czarodziejski-mlyn')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 5,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 08:02:12', '2019-08-23 08:02:12', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'irena-kwiatkowska')->get()->first();
        $tale = Tale::where('slug', 'czarodziejski-mlyn')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 6,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 08:02:12', '2019-08-23 08:02:12', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'krystyna-kamienska')->get()->first();
        $tale = Tale::where('slug', 'czarodziejski-mlyn')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 7,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 08:02:12', '2019-08-23 08:02:12', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'danuta-mancewicz')->get()->first();
        $tale = Tale::where('slug', 'czarodziejski-mlyn')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 8,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 08:02:12', '2019-08-23 08:02:12', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'jozef-nowak')->get()->first();
        $tale = Tale::where('slug', 'czarodziejski-mlyn')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 9,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 08:02:12', '2019-08-23 08:02:12', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'piotr-pawlowski')->get()->first();
        $tale = Tale::where('slug', 'czarodziejski-mlyn')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 10,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 08:02:12', '2019-08-23 08:02:12', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'hanna-skarzanka')->get()->first();
        $tale = Tale::where('slug', 'czarodziejski-mlyn')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 11,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 08:02:12', '2019-08-23 08:02:12', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'wilhelm-wichurski')->get()->first();
        $tale = Tale::where('slug', 'czarodziejski-mlyn')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 12,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 08:02:12', '2019-08-23 08:02:12', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'michal-breitenwald')->get()->first();
        $tale = Tale::where('slug', 'drzewko-aby-baby')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 1,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 08:34:10', '2019-08-25 04:21:26', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'ryszard-dembinski')->get()->first();
        $tale = Tale::where('slug', 'drzewko-aby-baby')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 2,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 08:34:10', '2019-08-25 04:21:26', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'stefania-iwinska')->get()->first();
        $tale = Tale::where('slug', 'drzewko-aby-baby')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 3,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 08:34:10', '2019-08-25 04:21:26', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'miroslaw-konarowski')->get()->first();
        $tale = Tale::where('slug', 'drzewko-aby-baby')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 4,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 08:34:10', '2019-08-25 04:21:26', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'ewa-konstanciak')->get()->first();
        $tale = Tale::where('slug', 'drzewko-aby-baby')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 5,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 08:34:10', '2019-08-25 04:21:26', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'krzysztof-kumor')->get()->first();
        $tale = Tale::where('slug', 'drzewko-aby-baby')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 6,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 08:34:10', '2019-08-25 04:21:26', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'gustaw-lutkiewicz')->get()->first();
        $tale = Tale::where('slug', 'drzewko-aby-baby')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 7,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 08:34:10', '2019-08-25 04:21:26', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'elzbieta-nowacka')->get()->first();
        $tale = Tale::where('slug', 'drzewko-aby-baby')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 8,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 08:34:10', '2019-08-25 04:21:27', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'maciej-orlos')->get()->first();
        $tale = Tale::where('slug', 'drzewko-aby-baby')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 9,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 08:34:10', '2019-08-25 04:21:27', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'tadeusz-wludarski')->get()->first();
        $tale = Tale::where('slug', 'drzewko-aby-baby')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 10,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 08:34:10', '2019-08-25 04:21:27', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'krystyna-wolanska')->get()->first();
        $tale = Tale::where('slug', 'drzewko-aby-baby')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 11,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 08:34:10', '2019-08-25 04:21:27', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'andrzej-bogucki')->get()->first();
        $tale = Tale::where('slug', 'tomcio-paluch')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 1,
                'characters' => 'Narrator',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 09:49:43', '2019-08-25 01:46:28', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'bogusz-bilewski')->get()->first();
        $tale = Tale::where('slug', 'tomcio-paluch')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 2,
                'characters' => 'Walenty',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 09:49:43', '2019-08-25 01:46:28', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'teresa-lipowska')->get()->first();
        $tale = Tale::where('slug', 'tomcio-paluch')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 3,
                'characters' => 'Walentowa',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 09:49:43', '2019-08-25 01:46:28', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'anna-skaros')->get()->first();
        $tale = Tale::where('slug', 'tomcio-paluch')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 4,
                'characters' => 'Tomcio Paluch',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 09:49:43', '2019-08-25 01:46:28', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'jan-matyjaszkiewicz')->get()->first();
        $tale = Tale::where('slug', 'tomcio-paluch')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 5,
                'characters' => 'Magik',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 09:49:43', '2019-08-25 01:46:28', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'zygmunt-listkiewicz')->get()->first();
        $tale = Tale::where('slug', 'tomcio-paluch')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 6,
                'characters' => 'Zbój I',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 09:49:43', '2019-08-25 01:46:28', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'jerzy-tkaczyk')->get()->first();
        $tale = Tale::where('slug', 'tomcio-paluch')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 7,
                'characters' => 'Zbój II',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 09:49:43', '2019-08-25 01:46:28', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'jerzy-wicik')->get()->first();
        $tale = Tale::where('slug', 'tomcio-paluch')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 8,
                'characters' => 'Wartownik',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 09:49:43', '2019-08-25 01:46:28', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'janusz-zakrzenski')->get()->first();
        $tale = Tale::where('slug', 'tomcio-paluch')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 9,
                'characters' => 'Marszałek',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 09:49:43', '2019-08-25 01:46:28', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'rudolf-golebiowski')->get()->first();
        $tale = Tale::where('slug', 'tomcio-paluch')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 10,
                'characters' => 'Oficer I',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 09:49:43', '2019-08-25 01:46:28', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'wlodzimierz-panasiewicz')->get()->first();
        $tale = Tale::where('slug', 'tomcio-paluch')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 11,
                'characters' => 'Oficer II',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 09:49:43', '2019-08-25 01:46:28', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'jozef-nowak')->get()->first();
        $tale = Tale::where('slug', 'tomcio-paluch')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 12,
                'characters' => 'Tomasz Paluch; Porucznik',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 09:49:43', '2019-08-25 01:46:28', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'zbigniew-krynski')->get()->first();
        $tale = Tale::where('slug', 'tomcio-paluch')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 13,
                'characters' => 'Naczelnik',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 09:49:43', '2019-08-25 01:46:28', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'halina-rowicka')->get()->first();
        $tale = Tale::where('slug', 'tomcio-paluch')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 14,
                'characters' => 'Dorotka',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 09:49:43', '2019-08-25 01:46:28', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'aleksander-gassowski')->get()->first();
        $tale = Tale::where('slug', 'tomcio-paluch')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 15,
                'characters' => 'Lokaj',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 09:49:43', '2019-08-25 01:46:28', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'jan-kobuszewski')->get()->first();
        $tale = Tale::where('slug', 'krol-bul')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 1,
                'characters' => 'Król Bul',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 09:53:29', '2019-08-25 01:47:30', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'gustaw-lutkiewicz')->get()->first();
        $tale = Tale::where('slug', 'krol-bul')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 2,
                'characters' => 'Błazen',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 09:53:29', '2019-08-25 01:47:30', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'stefan-witas')->get()->first();
        $tale = Tale::where('slug', 'krol-bul')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 3,
                'characters' => 'Król Teść',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 09:53:29', '2019-08-25 01:47:30', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'barbara-drapinska')->get()->first();
        $tale = Tale::where('slug', 'krol-bul')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 4,
                'characters' => 'Królowa',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 09:53:29', '2019-08-25 01:47:30', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'kazimiera-utrata')->get()->first();
        $tale = Tale::where('slug', 'krol-bul')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 5,
                'characters' => 'Ochmistrzyni',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 09:53:29', '2019-08-25 01:47:30', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'ilona-kusmierska')->get()->first();
        $tale = Tale::where('slug', 'krol-bul')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 6,
                'characters' => 'Królewna',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 09:53:29', '2019-08-25 01:47:30', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'krzysztof-kowalewski')->get()->first();
        $tale = Tale::where('slug', 'krol-bul')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 7,
                'characters' => 'Kucharz',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 09:53:29', '2019-08-25 01:47:30', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'witold-kaluski')->get()->first();
        $tale = Tale::where('slug', 'krol-bul')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 8,
                'characters' => 'Czarnoksiężnik',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 09:53:29', '2019-08-25 01:47:30', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'ewa-wisniewska')->get()->first();
        $tale = Tale::where('slug', 'krol-bul')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 9,
                'characters' => 'Dama I',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 09:53:30', '2019-08-25 01:47:31', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'malgorzata-niemirska')->get()->first();
        $tale = Tale::where('slug', 'krol-bul')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 10,
                'characters' => 'Dama II',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 09:53:30', '2019-08-25 01:47:31', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'andrzej-tomecki')->get()->first();
        $tale = Tale::where('slug', 'krol-bul')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 11,
                'characters' => 'Rycerz I',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 09:53:30', '2019-08-25 01:47:31', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'jerzy-karaszkiewicz')->get()->first();
        $tale = Tale::where('slug', 'krol-bul')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 12,
                'characters' => 'Rycerz II',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 09:53:30', '2019-08-25 01:47:31', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'anna-romantowska')->get()->first();
        $tale = Tale::where('slug', 'krol-bul')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 13,
                'characters' => 'Kot',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 09:53:30', '2019-08-25 01:47:31', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'wiktor-zborowski')->get()->first();
        $tale = Tale::where('slug', 'krol-bul')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 14,
                'characters' => 'Strażnik I',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 09:53:30', '2019-08-25 01:47:31', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'eugeniusz-robaczewski')->get()->first();
        $tale = Tale::where('slug', 'krol-bul')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 15,
                'characters' => 'Strażnik II',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 09:53:30', '2019-08-25 01:47:31', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'tadeusz-wludarski')->get()->first();
        $tale = Tale::where('slug', 'krol-bul')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 16,
                'characters' => 'Dentysta',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-23 09:53:30', '2019-08-25 01:47:31', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'wiktor-zborowski')->get()->first();
        $tale = Tale::where('slug', 'drzewko-aby-baby')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 12,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 01:17:12', '2019-08-25 04:21:27', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'maria-janecka')->get()->first();
        $tale = Tale::where('slug', 'o-dwoch-takich-co-ukradli-ksiezyc')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 1,
                'characters' => 'Jacek',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 01:55:33', '2019-08-25 01:55:33', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'danuta-mancewicz')->get()->first();
        $tale = Tale::where('slug', 'o-dwoch-takich-co-ukradli-ksiezyc')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 2,
                'characters' => 'Placek',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 01:55:33', '2019-08-25 01:55:33', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'stanislawa-celinska')->get()->first();
        $tale = Tale::where('slug', 'o-dwoch-takich-co-ukradli-ksiezyc')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 3,
                'characters' => 'Matka; Kobieta u ognia',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 01:55:33', '2019-08-25 01:55:33', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'wanda-majerowna')->get()->first();
        $tale = Tale::where('slug', 'o-dwoch-takich-co-ukradli-ksiezyc')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 4,
                'characters' => 'Sąsiadka I',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 01:55:33', '2019-08-25 01:55:33', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'halina-michalska')->get()->first();
        $tale = Tale::where('slug', 'o-dwoch-takich-co-ukradli-ksiezyc')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 5,
                'characters' => 'Sąsiadka II',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 01:55:33', '2019-08-25 01:55:33', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'zygmunt-apostol')->get()->first();
        $tale = Tale::where('slug', 'o-dwoch-takich-co-ukradli-ksiezyc')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 6,
                'characters' => 'Osioł',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 01:55:33', '2019-08-25 01:55:33', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'jadwiga-lubicz')->get()->first();
        $tale = Tale::where('slug', 'o-dwoch-takich-co-ukradli-ksiezyc')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 7,
                'characters' => 'Pszczoła',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 01:55:33', '2019-08-25 01:55:33', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'lech-ordon')->get()->first();
        $tale = Tale::where('slug', 'o-dwoch-takich-co-ukradli-ksiezyc')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 8,
                'characters' => 'Bóbr',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 01:55:33', '2019-08-25 01:55:33', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'tadeusz-bartosik')->get()->first();
        $tale = Tale::where('slug', 'o-dwoch-takich-co-ukradli-ksiezyc')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 9,
                'characters' => 'Pelikan',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 01:55:33', '2019-08-25 01:55:33', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'andrzej-stockinger')->get()->first();
        $tale = Tale::where('slug', 'o-dwoch-takich-co-ukradli-ksiezyc')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 10,
                'characters' => 'Złoty Pan',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 01:55:33', '2019-08-25 01:55:33', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'andrzej-zaorski')->get()->first();
        $tale = Tale::where('slug', 'o-dwoch-takich-co-ukradli-ksiezyc')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 11,
                'characters' => 'Nieborak',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 01:55:33', '2019-08-25 01:55:33', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'janusz-zakrzenski')->get()->first();
        $tale = Tale::where('slug', 'o-dwoch-takich-co-ukradli-ksiezyc')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 12,
                'characters' => 'Herszt',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 01:55:33', '2019-08-25 01:55:33', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'zofia-raciborska')->get()->first();
        $tale = Tale::where('slug', 'o-tadku-niejadku-babci-i-dziadku')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 1,
                'characters' => 'Tadek',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 02:13:14', '2019-08-25 02:13:14', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'tadeusz-bartosik')->get()->first();
        $tale = Tale::where('slug', 'o-tadku-niejadku-babci-i-dziadku')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 2,
                'characters' => 'Dziadek',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 02:13:14', '2019-08-25 02:13:14', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'irena-kwiatkowska')->get()->first();
        $tale = Tale::where('slug', 'o-tadku-niejadku-babci-i-dziadku')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 3,
                'characters' => 'Babcia',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 02:13:14', '2019-08-25 02:13:14', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'barbara-stepniakowna')->get()->first();
        $tale = Tale::where('slug', 'o-tadku-niejadku-babci-i-dziadku')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 4,
                'characters' => 'Sprzedawczyni',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 02:13:14', '2019-08-25 02:13:14', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'zdzislaw-salaburski')->get()->first();
        $tale = Tale::where('slug', 'o-tadku-niejadku-babci-i-dziadku')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 5,
                'characters' => 'Komendant',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 02:13:14', '2019-08-25 02:13:14', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'zbigniew-krynski')->get()->first();
        $tale = Tale::where('slug', 'o-tadku-niejadku-babci-i-dziadku')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 6,
                'characters' => 'Piekarz',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 02:13:14', '2019-08-25 02:13:14', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'bogdan-niewinowski')->get()->first();
        $tale = Tale::where('slug', 'o-tadku-niejadku-babci-i-dziadku')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 7,
                'characters' => 'Żongler',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 02:13:15', '2019-08-25 02:13:15', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'wanda-majerowna')->get()->first();
        $tale = Tale::where('slug', 'o-tadku-niejadku-babci-i-dziadku')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 8,
                'characters' => 'Sąsiadka',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 02:13:15', '2019-08-25 02:13:15', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'm-nanowska')->get()->first();
        $tale = Tale::where('slug', 'o-tadku-niejadku-babci-i-dziadku')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 9,
                'characters' => 'Sąsiadka',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 02:13:15', '2019-08-25 02:13:15', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'elzbieta-gaertner')->get()->first();
        $tale = Tale::where('slug', 'o-tadku-niejadku-babci-i-dziadku')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 10,
                'characters' => 'Sąsiadka',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 02:13:15', '2019-08-25 02:13:15', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'aleksander-gassowski')->get()->first();
        $tale = Tale::where('slug', 'o-tadku-niejadku-babci-i-dziadku')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 11,
                'characters' => 'Przechodzień',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 02:13:15', '2019-08-25 02:13:15', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'wiktor-nanowski')->get()->first();
        $tale = Tale::where('slug', 'o-tadku-niejadku-babci-i-dziadku')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 12,
                'characters' => 'Przechodzień',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 02:13:15', '2019-08-25 02:13:15', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'wojciech-zagorski')->get()->first();
        $tale = Tale::where('slug', 'o-tadku-niejadku-babci-i-dziadku')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 13,
                'characters' => 'Strażak',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 02:13:15', '2019-08-25 02:13:15', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'rudolf-golebiowski')->get()->first();
        $tale = Tale::where('slug', 'o-tadku-niejadku-babci-i-dziadku')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 14,
                'characters' => 'Strażak',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 02:13:15', '2019-08-25 02:13:15', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'jan-matyjaszkiewicz')->get()->first();
        $tale = Tale::where('slug', 'o-krasnoludkach-i-sierotce-marysi')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 2,
                'characters' => 'Lis Sadełko',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 02:33:32', '2019-08-26 01:15:57', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'wojciech-siemion')->get()->first();
        $tale = Tale::where('slug', 'o-krasnoludkach-i-sierotce-marysi')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 3,
                'characters' => 'Skrobek',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 02:33:32', '2019-08-26 01:15:57', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'teresa-lipowska')->get()->first();
        $tale = Tale::where('slug', 'o-krasnoludkach-i-sierotce-marysi')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 5,
                'characters' => 'Królowa Tatra',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 02:33:32', '2019-08-26 01:15:57', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'tadeusz-bartosik')->get()->first();
        $tale = Tale::where('slug', 'o-krasnoludkach-i-sierotce-marysi')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 6,
                'characters' => 'Król Błystek',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 02:33:32', '2019-08-26 01:15:57', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'andrzej-stockinger')->get()->first();
        $tale = Tale::where('slug', 'o-krasnoludkach-i-sierotce-marysi')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 7,
                'characters' => 'Żagiewka',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 02:33:32', '2019-08-26 01:15:57', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'wieslaw-michnikowski')->get()->first();
        $tale = Tale::where('slug', 'o-krasnoludkach-i-sierotce-marysi')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 8,
                'characters' => 'Koszałek',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 02:33:32', '2019-08-26 01:15:57', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'mieczyslaw-czechowicz')->get()->first();
        $tale = Tale::where('slug', 'o-krasnoludkach-i-sierotce-marysi')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 9,
                'characters' => 'Podziomek',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 02:33:32', '2019-08-26 01:15:57', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'maria-janecka')->get()->first();
        $tale = Tale::where('slug', 'o-krasnoludkach-i-sierotce-marysi')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 10,
                'characters' => 'Kuba',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 02:33:32', '2019-08-26 01:15:57', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'anna-rojek')->get()->first();
        $tale = Tale::where('slug', 'o-krasnoludkach-i-sierotce-marysi')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 11,
                'characters' => 'Wojtuś',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 02:33:32', '2019-08-26 01:15:58', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'elzbieta-gaertner')->get()->first();
        $tale = Tale::where('slug', 'o-krasnoludkach-i-sierotce-marysi')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 12,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 02:33:32', '2019-08-26 01:15:58', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'danuta-mancewicz')->get()->first();
        $tale = Tale::where('slug', 'o-krasnoludkach-i-sierotce-marysi')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 13,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 02:33:32', '2019-08-26 01:15:58', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'jerzy-dukay')->get()->first();
        $tale = Tale::where('slug', 'o-krasnoludkach-i-sierotce-marysi')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 14,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 02:33:32', '2019-08-26 01:15:58', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'jerzy-radwan')->get()->first();
        $tale = Tale::where('slug', 'o-krasnoludkach-i-sierotce-marysi')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 15,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 02:33:32', '2019-08-26 01:15:58', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'karol-stepkowski')->get()->first();
        $tale = Tale::where('slug', 'o-krasnoludkach-i-sierotce-marysi')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 16,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 02:33:32', '2019-08-26 01:15:58', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'jolanta-russek')->get()->first();
        $tale = Tale::where('slug', 'o-krasnoludkach-i-sierotce-marysi')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 4,
                'characters' => 'Marysia',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 02:37:07', '2019-08-26 01:15:58', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'magda-zawadzka')->get()->first();
        $tale = Tale::where('slug', 'alicja-w-krainie-czarow')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 1,
                'characters' => 'Alicja',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 03:28:27', '2019-08-25 05:04:32', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'wienczyslaw-glinski')->get()->first();
        $tale = Tale::where('slug', 'alicja-w-krainie-czarow')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 2,
                'characters' => 'Biały Królik',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 03:28:27', '2019-08-25 05:04:32', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'iga-cembrzynska')->get()->first();
        $tale = Tale::where('slug', 'alicja-w-krainie-czarow')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 3,
                'characters' => 'Mysz',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 03:28:27', '2019-08-25 05:04:32', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'wojciech-siemion')->get()->first();
        $tale = Tale::where('slug', 'alicja-w-krainie-czarow')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 4,
                'characters' => 'Bazyli',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 03:28:27', '2019-08-25 05:04:32', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'karol-stepkowski')->get()->first();
        $tale = Tale::where('slug', 'alicja-w-krainie-czarow')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 5,
                'characters' => 'Biś',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 03:28:27', '2019-08-25 05:04:32', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'piotr-fronczewski')->get()->first();
        $tale = Tale::where('slug', 'alicja-w-krainie-czarow')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 6,
                'characters' => 'Pan Gąsienica',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 03:28:27', '2019-08-25 05:04:32', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'renata-kossobudzka')->get()->first();
        $tale = Tale::where('slug', 'alicja-w-krainie-czarow')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 7,
                'characters' => 'Księżna',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 03:28:27', '2019-08-25 05:04:32', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'wieslaw-michnikowski')->get()->first();
        $tale = Tale::where('slug', 'alicja-w-krainie-czarow')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 8,
                'characters' => 'Kot-Dziwak',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 03:28:27', '2019-08-25 05:04:32', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'marian-kociniak')->get()->first();
        $tale = Tale::where('slug', 'alicja-w-krainie-czarow')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 9,
                'characters' => 'Szarak bez Piątej Klepki',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 03:28:27', '2019-08-25 05:04:32', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'mieczyslaw-czechowicz')->get()->first();
        $tale = Tale::where('slug', 'alicja-w-krainie-czarow')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 10,
                'characters' => 'Zwariowany Kapelusznik',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 03:28:27', '2019-08-25 05:04:32', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'jan-kociniak')->get()->first();
        $tale = Tale::where('slug', 'alicja-w-krainie-czarow')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 11,
                'characters' => 'Suseł',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 03:28:27', '2019-08-25 05:04:32', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'jerzy-dukay')->get()->first();
        $tale = Tale::where('slug', 'alicja-w-krainie-czarow')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 12,
                'characters' => 'Siódemka',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 03:28:27', '2019-08-25 05:04:32', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'rudolf-golebiowski')->get()->first();
        $tale = Tale::where('slug', 'alicja-w-krainie-czarow')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 13,
                'characters' => 'Piątka',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 03:28:27', '2019-08-25 05:04:32', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'tadeusz-bartosik')->get()->first();
        $tale = Tale::where('slug', 'alicja-w-krainie-czarow')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 14,
                'characters' => 'Król Kier',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 03:28:27', '2019-08-25 05:04:32', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'irena-kwiatkowska')->get()->first();
        $tale = Tale::where('slug', 'alicja-w-krainie-czarow')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 15,
                'characters' => 'Królowa Kier',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 03:28:27', '2019-08-25 05:04:32', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'krzysztof-orzechowski')->get()->first();
        $tale = Tale::where('slug', 'alicja-w-krainie-czarow')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 16,
                'characters' => 'Walet Kier',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 03:28:27', '2019-08-25 05:04:32', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'andrzej-stockinger')->get()->first();
        $tale = Tale::where('slug', 'alicja-w-krainie-czarow')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 17,
                'characters' => 'Kat',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 03:28:27', '2019-08-25 05:04:32', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'janusz-zakrzenski')->get()->first();
        $tale = Tale::where('slug', 'alicja-w-krainie-czarow')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 18,
                'characters' => 'Smok',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 03:28:27', '2019-08-25 05:04:32', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'andrzej-tomecki')->get()->first();
        $tale = Tale::where('slug', 'alicja-w-krainie-czarow')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 19,
                'characters' => 'Niby Żółw',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 03:28:27', '2019-08-25 05:04:32', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'wieslaw-michnikowski')->get()->first();
        $tale = Tale::where('slug', 'ali-baba-i-czterdziestu-rozbojnikow')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 1,
                'characters' => 'Ali Baba',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 03:35:51', '2019-08-26 01:15:32', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'mieczyslaw-czechowicz')->get()->first();
        $tale = Tale::where('slug', 'ali-baba-i-czterdziestu-rozbojnikow')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 2,
                'characters' => 'Kasim',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 03:35:51', '2019-08-26 01:15:33', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'barbara-krafftowna')->get()->first();
        $tale = Tale::where('slug', 'ali-baba-i-czterdziestu-rozbojnikow')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 3,
                'characters' => 'Zobeida',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 03:35:51', '2019-08-26 01:15:33', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'irena-kwiatkowska')->get()->first();
        $tale = Tale::where('slug', 'ali-baba-i-czterdziestu-rozbojnikow')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 4,
                'characters' => 'Amina',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 03:35:51', '2019-08-26 01:15:33', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'tadeusz-bartosik')->get()->first();
        $tale = Tale::where('slug', 'ali-baba-i-czterdziestu-rozbojnikow')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 5,
                'characters' => 'Herszt',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 03:35:51', '2019-08-26 01:15:33', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'wladyslaw-rzeczycki')->get()->first();
        $tale = Tale::where('slug', 'ali-baba-i-czterdziestu-rozbojnikow')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 6,
                'characters' => 'Zbójca',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 03:35:51', '2019-08-26 01:15:34', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'aleksander-gassowski')->get()->first();
        $tale = Tale::where('slug', 'ali-baba-i-czterdziestu-rozbojnikow')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 7,
                'characters' => 'Zbójca',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 03:35:51', '2019-08-26 01:15:34', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'rudolf-golebiowski')->get()->first();
        $tale = Tale::where('slug', 'ali-baba-i-czterdziestu-rozbojnikow')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 8,
                'characters' => 'Zbójca',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 03:35:51', '2019-08-26 01:15:34', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'piotr-fronczewski')->get()->first();
        $tale = Tale::where('slug', 'ali-baba-i-czterdziestu-rozbojnikow')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 9,
                'characters' => 'Zbójca',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 03:35:51', '2019-08-26 01:15:35', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'zygmunt-kestowicz')->get()->first();
        $tale = Tale::where('slug', 'tanczace-krasnoludki')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 2,
                'characters' => 'Bartek',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:28:56', '2019-08-26 01:16:15', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'barbara-bargielowska')->get()->first();
        $tale = Tale::where('slug', 'tanczace-krasnoludki')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 3,
                'characters' => 'Bartkowa',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:28:56', '2019-08-26 01:16:15', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'tadeusz-bartosik')->get()->first();
        $tale = Tale::where('slug', 'tanczace-krasnoludki')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 4,
                'characters' => 'Ludożerca',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:28:56', '2019-08-26 01:16:15', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'andrzej-tomecki')->get()->first();
        $tale = Tale::where('slug', 'tanczace-krasnoludki')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 5,
                'characters' => 'Krasnoludek',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:28:56', '2019-08-26 01:16:16', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'jerzy-zlotnicki')->get()->first();
        $tale = Tale::where('slug', 'tanczace-krasnoludki')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 6,
                'characters' => 'Krasnoludek',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:28:56', '2019-08-26 01:16:16', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'mieczyslaw-friedel')->get()->first();
        $tale = Tale::where('slug', 'tanczace-krasnoludki')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 7,
                'characters' => 'Krasnoludek',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:28:56', '2019-08-26 01:16:16', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'michal-muskat')->get()->first();
        $tale = Tale::where('slug', 'tanczace-krasnoludki')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 8,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:28:56', '2019-08-26 01:16:16', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'marian-kawski')->get()->first();
        $tale = Tale::where('slug', 'tanczace-krasnoludki')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 9,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:28:56', '2019-08-26 01:16:16', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'damian-damiecki')->get()->first();
        $tale = Tale::where('slug', 'pan-twardowski-na-kogucie')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 1,
                'characters' => 'Twardowski',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:42:20', '2019-08-25 04:42:20', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'wienczyslaw-glinski')->get()->first();
        $tale = Tale::where('slug', 'pan-twardowski-na-kogucie')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 2,
                'characters' => 'Narrator',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:42:20', '2019-08-25 04:42:20', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'jan-kobuszewski')->get()->first();
        $tale = Tale::where('slug', 'pan-twardowski-na-kogucie')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 3,
                'characters' => 'Boruta',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:42:20', '2019-08-25 04:42:20', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'gustaw-lutkiewicz')->get()->first();
        $tale = Tale::where('slug', 'pan-twardowski-na-kogucie')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 4,
                'characters' => 'Kogut',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:42:20', '2019-08-25 04:42:20', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'andrzej-stockinger')->get()->first();
        $tale = Tale::where('slug', 'pan-twardowski-na-kogucie')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 5,
                'characters' => 'Lucyfer',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:42:20', '2019-08-25 04:42:20', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'joanna-sobieska')->get()->first();
        $tale = Tale::where('slug', 'pan-twardowski-na-kogucie')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 6,
                'characters' => 'Zofia',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:42:20', '2019-08-25 04:42:20', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'kazimierz-rogowski')->get()->first();
        $tale = Tale::where('slug', 'pan-twardowski-na-kogucie')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 7,
                'characters' => 'Wojewoda',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:42:20', '2019-08-25 04:42:20', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'maciej-damiecki')->get()->first();
        $tale = Tale::where('slug', 'pan-twardowski-na-kogucie')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 8,
                'characters' => 'Maciek',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:42:20', '2019-08-25 04:42:20', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'miroslawa-krajewska')->get()->first();
        $tale = Tale::where('slug', 'pan-twardowski-na-kogucie')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 9,
                'characters' => 'Wojewodzina',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:42:20', '2019-08-25 04:42:20', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'zdzislaw-slowinski')->get()->first();
        $tale = Tale::where('slug', 'pan-twardowski-na-kogucie')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 10,
                'characters' => 'Rokita',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:42:20', '2019-08-25 04:42:20', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'zofia-rysiowna')->get()->first();
        $tale = Tale::where('slug', 'pan-twardowski-na-kogucie')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 11,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:42:20', '2019-08-25 04:42:20', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'eugeniusz-robaczewski')->get()->first();
        $tale = Tale::where('slug', 'pan-twardowski-na-kogucie')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 12,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:42:20', '2019-08-25 04:42:20', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'tadeusz-wludarski')->get()->first();
        $tale = Tale::where('slug', 'pan-twardowski-na-kogucie')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 13,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:42:20', '2019-08-25 04:42:20', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'tadeusz-bartosik')->get()->first();
        $tale = Tale::where('slug', 'lampa-aladyna')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 1,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:48:34', '2019-08-25 04:48:34', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'monika-gozdzik')->get()->first();
        $tale = Tale::where('slug', 'lampa-aladyna')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 2,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:48:34', '2019-08-25 04:48:34', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'witold-kaluski')->get()->first();
        $tale = Tale::where('slug', 'lampa-aladyna')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 3,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:48:34', '2019-08-25 04:48:34', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'jadwiga-lubicz')->get()->first();
        $tale = Tale::where('slug', 'lampa-aladyna')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 4,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:48:35', '2019-08-25 04:48:35', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'wlodzimierz-bednarski')->get()->first();
        $tale = Tale::where('slug', 'wielka-niedzwiedzica')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 1,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:54:34', '2019-08-25 04:54:34', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'olga-bielska')->get()->first();
        $tale = Tale::where('slug', 'wielka-niedzwiedzica')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 2,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:54:34', '2019-08-25 04:54:34', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'hanna-giza')->get()->first();
        $tale = Tale::where('slug', 'wielka-niedzwiedzica')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 3,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:54:34', '2019-08-25 04:54:34', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'krzysztof-kolbasiuk')->get()->first();
        $tale = Tale::where('slug', 'wielka-niedzwiedzica')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 4,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:54:34', '2019-08-25 04:54:34', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'maciej-maciejewski')->get()->first();
        $tale = Tale::where('slug', 'wielka-niedzwiedzica')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 5,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:54:34', '2019-08-25 04:54:34', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'henryk-machalica')->get()->first();
        $tale = Tale::where('slug', 'wielka-niedzwiedzica')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 6,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:54:34', '2019-08-25 04:54:34', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'tomasz-marzecki')->get()->first();
        $tale = Tale::where('slug', 'wielka-niedzwiedzica')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 7,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:54:34', '2019-08-25 04:54:34', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'franciszek-pieczka')->get()->first();
        $tale = Tale::where('slug', 'wielka-niedzwiedzica')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 8,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:54:34', '2019-08-25 04:54:34', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'eugeniusz-robaczewski')->get()->first();
        $tale = Tale::where('slug', 'wielka-niedzwiedzica')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 9,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:54:34', '2019-08-25 04:54:34', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'halina-rowicka')->get()->first();
        $tale = Tale::where('slug', 'wielka-niedzwiedzica')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 10,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:54:34', '2019-08-25 04:54:34', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'zofia-rysiowna')->get()->first();
        $tale = Tale::where('slug', 'wielka-niedzwiedzica')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 11,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:54:34', '2019-08-25 04:54:34', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'joanna-szczepkowska')->get()->first();
        $tale = Tale::where('slug', 'wielka-niedzwiedzica')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 12,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:54:34', '2019-08-25 04:54:34', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'wojciech-zagorski')->get()->first();
        $tale = Tale::where('slug', 'wielka-niedzwiedzica')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 13,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:54:34', '2019-08-25 04:54:34', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'tomasz-zaliwski')->get()->first();
        $tale = Tale::where('slug', 'wielka-niedzwiedzica')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 14,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 04:54:34', '2019-08-25 04:54:34', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'piotr-fronczewski')->get()->first();
        $tale = Tale::where('slug', 'opowiesc-o-pra-pra-sloniu')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 1,
                'characters' => 'Słoń',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 05:03:04', '2019-08-25 05:03:04', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'tadeusz-wludarski')->get()->first();
        $tale = Tale::where('slug', 'opowiesc-o-pra-pra-sloniu')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 2,
                'characters' => 'Pra-Pra-Słoń',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 05:03:04', '2019-08-25 05:03:04', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'jan-matyjaszkiewicz')->get()->first();
        $tale = Tale::where('slug', 'opowiesc-o-pra-pra-sloniu')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 3,
                'characters' => 'Krokodyl',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 05:03:04', '2019-08-25 05:03:04', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'jan-kobuszewski')->get()->first();
        $tale = Tale::where('slug', 'opowiesc-o-pra-pra-sloniu')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 4,
                'characters' => 'Wąż',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 05:03:04', '2019-08-25 05:03:04', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'wlodzimierz-nowakowski')->get()->first();
        $tale = Tale::where('slug', 'opowiesc-o-pra-pra-sloniu')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 5,
                'characters' => 'Żyrafa',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 05:03:04', '2019-08-25 05:03:04', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'joanna-sobieska')->get()->first();
        $tale = Tale::where('slug', 'opowiesc-o-pra-pra-sloniu')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 6,
                'characters' => 'Papuga',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 05:03:04', '2019-08-25 05:03:04', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'maria-ciesielska')->get()->first();
        $tale = Tale::where('slug', 'opowiesc-o-pra-pra-sloniu')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 7,
                'characters' => 'Papuga',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 05:03:04', '2019-08-25 05:03:04', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'magda-wollejko')->get()->first();
        $tale = Tale::where('slug', 'opowiesc-o-pra-pra-sloniu')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 8,
                'characters' => 'Małpa',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 05:03:04', '2019-08-25 05:03:04', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'jolanta-wollejko')->get()->first();
        $tale = Tale::where('slug', 'opowiesc-o-pra-pra-sloniu')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 9,
                'characters' => 'Małpa',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 05:03:04', '2019-08-25 05:03:04', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'michal-bukowski')->get()->first();
        $tale = Tale::where('slug', 'opowiesc-o-pra-pra-sloniu')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 10,
                'characters' => 'Chłopiec',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 05:03:04', '2019-08-25 05:03:04', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'cezary-kwiecinski')->get()->first();
        $tale = Tale::where('slug', 'opowiesc-o-pra-pra-sloniu')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 11,
                'characters' => 'Pra-Pra-Chłopiec',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 05:03:05', '2019-08-25 05:03:05', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'michal-styczynski')->get()->first();
        $tale = Tale::where('slug', 'opowiesc-o-pra-pra-sloniu')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 12,
                'characters' => 'Pra-Pra-Chłopiec (śpiew)',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 05:03:05', '2019-08-25 05:03:05', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'ilona-kusmierska')->get()->first();
        $tale = Tale::where('slug', 'legenda-o-sielawowym-krolu')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 1,
                'characters' => 'Iza',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 22:15:41', '2019-08-26 04:02:47', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'jerzy-rogowski')->get()->first();
        $tale = Tale::where('slug', 'legenda-o-sielawowym-krolu')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 2,
                'characters' => 'Marek',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 22:15:41', '2019-08-26 04:02:47', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'anna-seniuk')->get()->first();
        $tale = Tale::where('slug', 'legenda-o-sielawowym-krolu')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 3,
                'characters' => 'Mama',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 22:15:41', '2019-08-26 04:02:47', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'marian-kociniak')->get()->first();
        $tale = Tale::where('slug', 'legenda-o-sielawowym-krolu')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 4,
                'characters' => 'Tata',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 22:15:41', '2019-08-26 04:02:47', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'franciszek-pieczka')->get()->first();
        $tale = Tale::where('slug', 'legenda-o-sielawowym-krolu')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 5,
                'characters' => 'Pan Mikołaj',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 22:15:41', '2019-08-26 04:02:47', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'stanislaw-zatloka')->get()->first();
        $tale = Tale::where('slug', 'legenda-o-sielawowym-krolu')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 6,
                'characters' => 'Mikołajek',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 22:15:41', '2019-08-26 04:02:47', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'joanna-sobieska')->get()->first();
        $tale = Tale::where('slug', 'legenda-o-sielawowym-krolu')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 7,
                'characters' => 'Siostra',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 22:15:41', '2019-08-26 04:02:47', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'gustaw-lutkiewicz')->get()->first();
        $tale = Tale::where('slug', 'legenda-o-sielawowym-krolu')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 10,
                'characters' => 'Rybak',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 22:15:41', '2019-08-26 04:02:47', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'emilian-kaminski')->get()->first();
        $tale = Tale::where('slug', 'legenda-o-sielawowym-krolu')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 9,
                'characters' => 'Rybak',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 22:15:41', '2019-08-26 04:02:47', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'gustaw-lutkiewicz')->get()->first();
        $tale = Tale::where('slug', 'legenda-o-sielawowym-krolu')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 10,
                'characters' => 'Rybak',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 22:15:41', '2019-08-26 04:02:47', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'olga-bielska')->get()->first();
        $tale = Tale::where('slug', 'legenda-o-sielawowym-krolu')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 11,
                'characters' => 'Rybaczka',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 22:15:41', '2019-08-26 04:02:47', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'maria-winiarska')->get()->first();
        $tale = Tale::where('slug', 'legenda-o-sielawowym-krolu')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 12,
                'characters' => 'Łabędź',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 22:15:41', '2019-08-26 04:02:47', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'barbara-winiarska')->get()->first();
        $tale = Tale::where('slug', 'legenda-o-sielawowym-krolu')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 13,
                'characters' => 'Łabędź',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-25 22:15:41', '2019-08-26 04:02:47', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'edmund-fidler')->get()->first();
        $tale = Tale::where('slug', 'ali-baba-i-czterdziestu-rozbojnikow')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 10,
                'characters' => 'Narrator',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-26 01:15:35', '2019-08-26 01:15:35', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'edmund-fidler')->get()->first();
        $tale = Tale::where('slug', 'o-krasnoludkach-i-sierotce-marysi')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 1,
                'characters' => 'Narrator',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-26 01:15:57', '2019-08-26 01:15:57', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'edmund-fidler')->get()->first();
        $tale = Tale::where('slug', 'tanczace-krasnoludki')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 1,
                'characters' => 'Narrator',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-26 01:16:15', '2019-08-26 01:16:15', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'marian-kociniak')->get()->first();
        $tale = Tale::where('slug', 'deszczem-wyszywane')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 1,
                'characters' => 'Narrator; ?',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-26 04:09:41', '2019-08-26 04:09:41', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'ola-rojewska')->get()->first();
        $tale = Tale::where('slug', 'deszczem-wyszywane')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 2,
                'characters' => 'Ania',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-26 04:09:41', '2019-08-26 04:09:41', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'artur-pontek')->get()->first();
        $tale = Tale::where('slug', 'deszczem-wyszywane')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 3,
                'characters' => 'Piotruś',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-26 04:09:41', '2019-08-26 04:09:41', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'ilona-kusmierska')->get()->first();
        $tale = Tale::where('slug', 'deszczem-wyszywane')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 4,
                'characters' => 'Jesienna Dziewczynka',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-26 04:09:41', '2019-08-26 04:09:41', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'cezary-kwiecinski')->get()->first();
        $tale = Tale::where('slug', 'deszczem-wyszywane')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 5,
                'characters' => 'Gruby Felek',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-26 04:09:41', '2019-08-26 04:09:41', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'ryszard-dreger')->get()->first();
        $tale = Tale::where('slug', 'deszczem-wyszywane')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 6,
                'characters' => 'Chudy Maniek',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-26 04:09:41', '2019-08-26 04:09:41', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'tomek-michnikowski')->get()->first();
        $tale = Tale::where('slug', 'deszczem-wyszywane')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 7,
                'characters' => 'Mały Teoś',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-26 04:09:41', '2019-08-26 04:09:41', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'anna-skaros')->get()->first();
        $tale = Tale::where('slug', 'deszczem-wyszywane')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 8,
                'characters' => 'Zośka-Kłamczucha',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-26 04:09:41', '2019-08-26 04:09:41', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'jerzy-kryszak')->get()->first();
        $tale = Tale::where('slug', 'deszczem-wyszywane')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 9,
                'characters' => 'Pies',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-26 04:09:41', '2019-08-26 04:09:41', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'bogusz-bilewski')->get()->first();
        $tale = Tale::where('slug', 'deszczem-wyszywane')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 10,
                'characters' => 'Pies',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-26 04:09:41', '2019-08-26 04:09:41', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'ewelina-jaslar')->get()->first();
        $tale = Tale::where('slug', 'deszczem-wyszywane')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr' => 11,
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-26 04:09:41', '2019-08-26 04:09:41', $artist->id, $tale->id]
        );

        $artist = Artist::where('slug', 'robert-golaszewski')->get()->first();
        $tale = Tale::where('slug', 'deszczem-wyszywane')->get()->first();
        $tale->actors()->attach(
            $artist->id,
            [
                'credit_nr'  => 12,
                'characters' => '? (śpiew)',
            ]
        );
        DB::update(
            'update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?',
            ['2019-08-26 04:09:41', '2019-08-26 04:09:41', $artist->id, $tale->id]
        );
    }
}
