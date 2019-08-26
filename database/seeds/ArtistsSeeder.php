<?php

use Illuminate\Database\Seeder;
use App\Artist;
use Illuminate\Support\Facades\DB;

class ArtistsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $artist = new Artist();
        $artist->slug = 'adam-markiewicz';
        $artist->name = 'Adam Markiewicz';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 22:15:41', '2019-08-25 22:15:41', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'adam-skorupka';
        $artist->name = 'Adam Skorupka';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-26 04:09:41', '2019-08-26 04:09:41', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'aleksander-dzwonkowski';
        $artist->name = 'Aleksander Dzwonkowski';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 08:02:11', '2019-08-23 08:02:11', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'aleksander-gassowski';
        $artist->name = 'Aleksander Gąssowski';
        $artist->discogs = 588577;
        $artist->imdb = '0309372';
        $artist->wikipedia = 'Aleksander_Gąssowski';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 09:49:43', '2019-08-25 23:22:48', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'aleksander-trabczynski';
        $artist->name = 'Aleksander Trąbczyński';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 07:58:26', '2019-08-23 07:58:26', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'alicja-rojek';
        $artist->name = 'Alicja Rojek';
        $artist->discogs = 4796128;
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 06:08:03', '2019-08-26 03:54:32', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'alina-afanasjew';
        $artist->name = 'Alina Afanasjew';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 08:02:11', '2019-08-23 08:02:11', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'andrzej-bogucki';
        $artist->name = 'Andrzej Bogucki';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 08:02:11', '2019-08-23 08:02:11', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'andrzej-pruski';
        $artist->name = 'Andrzej Pruski';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 04:17:05', '2019-08-25 04:17:05', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'andrzej-stockinger';
        $artist->name = 'Andrzej Stockinger';
        $artist->discogs = 954391;
        $artist->imdb = '0830852';
        $artist->wikipedia = 'Andrzej_Stockinger';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 06:08:03', '2019-08-25 05:56:22', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'andrzej-tomecki';
        $artist->name = 'Andrzej Tomecki';
        $artist->discogs = 1024340;
        $artist->imdb = '0866605';
        $artist->wikipedia = 'Andrzej_Tomecki';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 09:53:30', '2019-08-25 23:23:48', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'andrzej-zaorski';
        $artist->name = 'Andrzej Zaorski';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 01:55:33', '2019-08-25 01:55:33', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'anna-rojek';
        $artist->name = 'Anna Rojek';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 02:33:32', '2019-08-25 02:33:32', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'anna-romantowska';
        $artist->name = 'Anna Romantowska';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 09:53:30', '2019-08-23 09:53:30', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'anna-seniuk';
        $artist->name = 'Anna Seniuk';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 22:15:41', '2019-08-25 22:15:41', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'anna-skaros';
        $artist->name = 'Anna Skaros';
        $artist->discogs = 518239;
        $artist->wikipedia = 'Anna_Skaros';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 06:30:38', '2019-08-26 03:23:01', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'antoni-marianowicz';
        $artist->name = 'Antoni Marianowicz';
        $artist->discogs = 588569;
        $artist->imdb = '1506453';
        $artist->wikipedia = 'Antoni_Marianowicz';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 09:49:43', '2019-08-25 23:24:24', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'artur-pontek';
        $artist->name = 'Artur Pontek';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-26 04:09:41', '2019-08-26 04:09:41', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'barbara-bargielowska';
        $artist->name = 'Barbara Bargiełowska';
        $artist->discogs = 1023685;
        $artist->imdb = '0054569';
        $artist->wikipedia = 'Barbara_Bargiełowska';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 06:30:37', '2019-08-26 03:42:34', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'barbara-drapinska';
        $artist->name = 'Barbara Drapińska';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 09:53:29', '2019-08-23 09:53:29', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'barbara-krafftowna';
        $artist->name = 'Barbara Krafftówna';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 03:35:51', '2019-08-25 03:35:51', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'barbara-stepniakowna';
        $artist->name = 'Barbara Stępniakówna';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 02:13:14', '2019-08-25 02:13:14', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'barbara-winiarska';
        $artist->name = 'Barbara Winiarska';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 22:15:41', '2019-08-25 22:15:41', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'bogdan-niewinowski';
        $artist->name = 'Bogdan Niewinowski';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 02:13:15', '2019-08-25 02:13:15', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'bogusz-bilewski';
        $artist->name = 'Bogusz Bilewski';
        $artist->discogs = 588571;
        $artist->imdb = '0082200';
        $artist->wikipedia = 'Bogusz_Bilewski';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 09:49:43', '2019-08-26 04:11:13', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'bronislaw-pawlik';
        $artist->name = 'Bronisław Pawlik';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 07:58:25', '2019-08-23 07:58:25', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'cezary-kwiecinski';
        $artist->name = 'Cezary Kwieciński';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 05:03:04', '2019-08-25 05:03:04', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'czeslaw-mroczek';
        $artist->name = 'Czesław Mroczek';
        $artist->discogs = 4153396;
        $artist->imdb = '0610705';
        $artist->wikipedia = 'Czesław_Mroczek_(aktor)';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 06:08:03', '2019-08-26 03:47:03', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'damian-damiecki';
        $artist->name = 'Damian Damięcki';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 04:42:20', '2019-08-25 04:42:20', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'danuta-mancewicz';
        $artist->name = 'Danuta Mancewicz';
        $artist->discogs = 1023341;
        $artist->imdb = '1597219';
        $artist->wikipedia = 'Danuta_Mancewicz';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 08:02:12', '2019-08-25 06:00:42', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'danuta-przesmycka';
        $artist->name = 'Danuta Przesmycka';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 07:58:25', '2019-08-23 07:58:25', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'danuta-rastawicka';
        $artist->name = 'Danuta Rastawicka';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 07:58:26', '2019-08-23 07:58:26', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'danuta-wodynska';
        $artist->name = 'Danuta Wodyńska';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 07:58:25', '2019-08-23 07:58:25', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'dorota-gellner';
        $artist->name = 'Dorota Gellner';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-26 04:09:41', '2019-08-26 04:09:41', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'edmund-fidler';
        $artist->name = 'Edmund Fidler';
        $artist->discogs = 1030536;
        $artist->imdb = '9751195';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-26 01:15:32', '2019-08-26 01:17:29', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'elzbieta-bussold';
        $artist->name = 'Elżbieta Bussold';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 06:30:37', '2019-08-23 06:30:37', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'elzbieta-gaertner';
        $artist->name = 'Elżbieta Gaertner';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 02:13:15', '2019-08-25 02:13:15', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'elzbieta-kmiecinska';
        $artist->name = 'Elżbieta Kmiecińska';
        $artist->discogs = 1023687;
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 06:08:03', '2019-08-26 03:41:53', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'elzbieta-nowacka';
        $artist->name = 'Elżbieta Nowacka';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 08:34:10', '2019-08-23 08:34:10', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'emilian-kaminski';
        $artist->name = 'Emilian Kamiński';
        $artist->discogs = 1023690;
        $artist->imdb = '0436717';
        $artist->wikipedia = 'Emilian_Kamiński';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 06:30:38', '2019-08-25 23:20:52', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'eugeniusz-robaczewski';
        $artist->name = 'Eugeniusz Robaczewski';
        $artist->discogs = 591949;
        $artist->imdb = '0730152';
        $artist->wikipedia = 'Eugeniusz_Robaczewski';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 07:58:26', '2019-08-26 01:11:13', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'ewa-konstanciak';
        $artist->name = 'Ewa Konstanciak';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 08:34:10', '2019-08-23 08:34:10', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'ewa-wisniewska';
        $artist->name = 'Ewa Wiśniewska';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 09:53:29', '2019-08-23 09:53:29', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'ewelina-jaslar';
        $artist->name = 'Ewelina Jaślar';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-26 04:09:41', '2019-08-26 04:09:41', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'franciszek-pieczka';
        $artist->name = 'Franciszek Pieczka';
        $artist->discogs = 591955;
        $artist->imdb = '0669813';
        $artist->wikipedia = 'Franciszek_Pieczka';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 04:54:34', '2019-08-25 22:18:18', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'gustaw-lutkiewicz';
        $artist->name = 'Gustaw Lutkiewicz';
        $artist->discogs = 954395;
        $artist->imdb = '0527464';
        $artist->wikipedia = 'Gustaw_Lutkiewicz';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 08:34:10', '2019-08-25 23:18:43', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'halina-michalska';
        $artist->name = 'Halina Michalska';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 01:55:33', '2019-08-25 01:55:33', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'halina-rowicka';
        $artist->name = 'Halina Rowicka';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 09:49:43', '2019-08-23 09:49:43', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'hanna-balinska';
        $artist->name = 'Hanna Balińska';
        $artist->discogs = 7337405;
        $artist->imdb = '1291023';
        $artist->wikipedia = 'Hanna_Balińska';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 06:08:03', '2019-08-26 03:58:05', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'hanna-giza';
        $artist->name = 'Hanna Giza';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 04:54:34', '2019-08-25 04:54:34', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'hanna-skarzanka';
        $artist->name = 'Hanna Skarżanka';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 08:02:12', '2019-08-23 08:02:12', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'henryk-machalica';
        $artist->name = 'Henryk Machalica';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 04:54:34', '2019-08-25 04:54:34', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'iga-cembrzynska';
        $artist->name = 'Iga Cembrzyńska';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 03:28:27', '2019-08-25 03:28:27', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'igor-smialowski';
        $artist->name = 'Igor Śmiałowski';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 07:58:26', '2019-08-23 07:58:26', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'ilona-kusmierska';
        $artist->name = 'Ilona Kuśmierska';
        $artist->discogs = 602488;
        $artist->imdb = '0476350';
        $artist->wikipedia = 'Ilona_Kuśmierska';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 06:08:03', '2019-08-25 06:02:12', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'irena-kwiatkowska';
        $artist->name = 'Irena Kwiatkowska';
        $artist->discogs = 907253;
        $artist->imdb = '0477173';
        $artist->wikipedia = 'Irena_Kwiatkowska';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 08:02:12', '2019-08-25 23:26:08', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'jacek-podkomorzy';
        $artist->name = 'Jacek Podkomorzy';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 04:48:34', '2019-08-25 04:48:34', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'jadwiga-lubicz';
        $artist->name = 'Jadwiga Lubicz';
        $artist->discogs = 1029105;
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 06:08:03', '2019-08-25 05:57:07', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'jan-kobuszewski';
        $artist->name = 'Jan Kobuszewski';
        $artist->discogs = 831423;
        $artist->imdb = '0462204';
        $artist->wikipedia = 'Jan_Kobuszewski';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 09:53:29', '2019-08-25 23:25:25', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'jan-kociniak';
        $artist->name = 'Jan Kociniak';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 03:28:27', '2019-08-25 03:28:27', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'jan-matyjaszkiewicz';
        $artist->name = 'Jan Matyjaszkiewicz';
        $artist->discogs = 588576;
        $artist->imdb = '0560651';
        $artist->wikipedia = 'Jan_Matyjaszkiewicz';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 06:30:38', '2019-08-25 05:54:49', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'jan-zelnik';
        $artist->name = 'Jan Zelnik';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 04:42:20', '2019-08-25 04:42:20', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'janina-gillowa';
        $artist->name = 'Janina Gillowa';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 02:33:32', '2019-08-25 02:33:32', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'janina-traczykowna';
        $artist->name = 'Janina Traczykówna';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 07:58:25', '2019-08-23 07:58:25', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'janusz-zakrzenski';
        $artist->name = 'Janusz Zakrzeński';
        $artist->discogs = 588582;
        $artist->imdb = '0952350';
        $artist->wikipedia = 'Janusz_Zakrzeński';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 06:08:03', '2019-08-25 05:55:41', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'jerzy-afanasjew';
        $artist->name = 'Jerzy Afanasjew';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 08:02:11', '2019-08-23 08:02:11', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'jerzy-dukay';
        $artist->name = 'Jerzy Dukay';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 02:33:32', '2019-08-25 02:33:32', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'jerzy-karaszkiewicz';
        $artist->name = 'Jerzy Karaszkiewicz';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 09:53:30', '2019-08-23 09:53:30', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'jerzy-kryszak';
        $artist->name = 'Jerzy Kryszak';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-26 04:09:41', '2019-08-26 04:09:41', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'jerzy-radwan';
        $artist->name = 'Jerzy Radwan';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 02:33:32', '2019-08-25 02:33:32', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'jerzy-rogowski';
        $artist->name = 'Jerzy Rogowski';
        $artist->discogs = 518241;
        $artist->imdb = '1150716';
        $artist->wikipedia = 'Jerzy_Rogowski';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 07:58:25', '2019-08-25 22:20:21', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'jerzy-tkaczyk';
        $artist->name = 'Jerzy Tkaczyk';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 09:49:43', '2019-08-23 09:49:43', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'jerzy-wicik';
        $artist->name = 'Jerzy Wicik';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 09:49:43', '2019-08-23 09:49:43', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'jerzy-zlotnicki';
        $artist->name = 'Jerzy Złotnicki';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 04:28:56', '2019-08-25 04:28:56', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'joanna-sobieska';
        $artist->name = 'Joanna Sobieska';
        $artist->discogs = 518243;
        $artist->imdb = '1490469';
        $artist->wikipedia = 'Joanna_Sobieska';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 07:58:25', '2019-08-25 23:21:41', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'joanna-szczepkowska';
        $artist->name = 'Joanna Szczepkowska';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 04:54:34', '2019-08-25 04:54:34', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'jolanta-russek';
        $artist->name = 'Jolanta Russek';
        $artist->discogs = 3326039;
        $artist->imdb = '10377574';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 06:08:03', '2019-08-26 03:54:07', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'jolanta-wollejko';
        $artist->name = 'Jolanta Wołłejko';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 05:03:04', '2019-08-25 05:03:04', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'jozef-nalberczak';
        $artist->name = 'Józef Nalberczak';
        $artist->discogs = 3986183;
        $artist->imdb = '0620505';
        $artist->wikipedia = 'Józef_Nalberczak';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 06:08:03', '2019-08-26 03:46:18', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'jozef-nowak';
        $artist->name = 'Józef Nowak';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 08:02:12', '2019-08-23 08:02:12', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'jozef-talarczyk';
        $artist->name = 'Józef Talarczyk';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 08:34:10', '2019-08-23 08:34:10', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'karol-stepkowski';
        $artist->name = 'Karol Stępkowski';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 02:33:32', '2019-08-25 02:33:32', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'katarzyna-lengren';
        $artist->name = 'Katarzyna Lengren';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 09:53:29', '2019-08-23 09:53:29', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'kazimiera-utrata';
        $artist->name = 'Kazimiera Utrata';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 09:53:29', '2019-08-23 09:53:29', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'kazimierz-rogowski';
        $artist->name = 'Kazimierz Rogowski';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 04:42:20', '2019-08-25 04:42:20', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'krystyna-kamienska';
        $artist->name = 'Krystyna Kamieńska';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 08:02:12', '2019-08-23 08:02:12', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'krystyna-miecikowna';
        $artist->name = 'Krystyna Miecikówna';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 07:58:25', '2019-08-23 07:58:25', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'krystyna-wodnicka';
        $artist->name = 'Krystyna Wodnicka';
        $artist->discogs = 972373;
        $artist->imdb = '5688599';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 06:08:02', '2019-08-26 01:38:31', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'krystyna-wolanska';
        $artist->name = 'Krystyna Wolańska';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 08:34:10', '2019-08-23 08:34:10', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'krzysztof-kolbasiuk';
        $artist->name = 'Krzysztof Kołbasiuk';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 04:54:34', '2019-08-25 04:54:34', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'krzysztof-kowalewski';
        $artist->name = 'Krzysztof Kowalewski';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 09:53:29', '2019-08-23 09:53:29', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'krzysztof-kumor';
        $artist->name = 'Krzysztof Kumor';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 08:34:10', '2019-08-23 08:34:10', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'krzysztof-orzechowski';
        $artist->name = 'Krzysztof Orzechowski';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 03:28:27', '2019-08-25 03:28:27', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'krzysztof-wierzbianski';
        $artist->name = 'Krzysztof Wierzbiański';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 04:20:20', '2019-08-25 04:20:20', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'lech-ordon';
        $artist->name = 'Lech Ordon';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 07:58:25', '2019-08-23 07:58:25', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'ludwik-rene';
        $artist->name = 'Ludwik René';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 04:11:32', '2019-08-25 04:11:32', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'm-nanowska';
        $artist->name = 'M. Nanowska';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 02:13:15', '2019-08-25 02:13:15', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'maciej-damiecki';
        $artist->name = 'Maciej Damięcki';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 04:42:20', '2019-08-25 04:42:20', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'maciej-maciejewski';
        $artist->name = 'Maciej Maciejewski';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 04:54:34', '2019-08-25 04:54:34', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'maciej-orlos';
        $artist->name = 'Maciej Orłoś';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 08:34:10', '2019-08-23 08:34:10', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'magda-wollejko';
        $artist->name = 'Magda Wołłejko';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 05:03:04', '2019-08-25 05:03:04', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'magda-zawadzka';
        $artist->name = 'Magda Zawadzka';
        $artist->discogs = 1603288;
        $artist->imdb = '0953843';
        $artist->wikipedia = 'Magdalena_Zawadzka';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 06:30:37', '2019-08-26 03:44:23', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'malgorzata-niemirska';
        $artist->name = 'Małgorzata Niemirska';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 09:53:30', '2019-08-23 09:53:30', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'marek-kulesza';
        $artist->name = 'Marek Kulesza';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 04:54:34', '2019-08-25 04:54:34', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'marek-obertyn';
        $artist->name = 'Marek Obertyn';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 07:58:25', '2019-08-23 07:58:25', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'maria-ciesielska';
        $artist->name = 'Maria Ciesielska';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 05:03:04', '2019-08-25 05:03:04', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'maria-janecka';
        $artist->name = 'Maria Janecka';
        $artist->discogs = 1029104;
        $artist->imdb = '1597120';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 08:02:12', '2019-08-25 05:59:35', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'maria-winiarska';
        $artist->name = 'Maria Winiarska';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 22:15:41', '2019-08-25 22:15:41', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'marian-kawski';
        $artist->name = 'Marian Kawski';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 04:28:56', '2019-08-25 04:28:56', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'marian-kociniak';
        $artist->name = 'Marian Kociniak';
        $artist->discogs = 518236;
        $artist->imdb = '0462550';
        $artist->wikipedia = 'Marian_Kociniak';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 03:28:27', '2019-08-25 22:19:38', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'marian-szalkowski';
        $artist->name = 'Marian Szałkowski';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 04:54:34', '2019-08-25 04:54:34', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'michal-breitenwald';
        $artist->name = 'Michał Breitenwald';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 08:34:10', '2019-08-23 08:34:10', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'michal-bukowski';
        $artist->name = 'Michał Bukowski';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 07:58:26', '2019-08-23 07:58:26', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'michal-muskat';
        $artist->name = 'Michał Muskat';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 04:28:56', '2019-08-25 04:28:56', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'michal-styczynski';
        $artist->name = 'Michał Styczyński';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 05:03:05', '2019-08-25 05:03:05', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'mieczyslaw-czechowicz';
        $artist->name = 'Mieczysław Czechowicz';
        $artist->discogs = 775429;
        $artist->imdb = '0194387';
        $artist->wikipedia = 'Mieczysław_Czechowicz';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 02:33:32', '2019-08-26 01:36:11', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'mieczyslaw-friedel';
        $artist->name = 'Mieczysław Friedel';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 04:28:56', '2019-08-25 04:28:56', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'mieczyslaw-gajda';
        $artist->name = 'Mieczysław Gajda';
        $artist->discogs = 1023686;
        $artist->imdb = '0301468';
        $artist->wikipedia = 'Mieczysław_Gajda_(1931%E2%80%932017)';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 06:30:37', '2019-08-26 03:38:21', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'mieczyslaw-hryniewicz';
        $artist->name = 'Mieczysław Hryniewicz';
        $artist->discogs = 1028300;
        $artist->imdb = '0398721';
        $artist->wikipedia = 'Mieczysław_Hryniewicz';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 06:08:03', '2019-08-26 03:59:29', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'mieczyslaw-janicz';
        $artist->name = 'Mieczysław Janicz';
        $artist->discogs = 1017603;
        $artist->imdb = '10218466';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 06:08:03', '2019-08-26 01:23:13', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'miroslaw-konarowski';
        $artist->name = 'Mirosław Konarowski';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 08:34:10', '2019-08-23 08:34:10', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'miroslaw-lebkowski';
        $artist->name = 'Mirosław Łebkowski';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 08:34:09', '2019-08-23 08:34:09', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'miroslawa-krajewska';
        $artist->name = 'Mirosława Krajewska';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 04:42:20', '2019-08-25 04:42:20', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'monika-gozdzik';
        $artist->name = 'Monika Goździk';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 04:48:34', '2019-08-25 04:48:34', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'ola-rojewska';
        $artist->name = 'Ola Rojewska';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-26 04:09:41', '2019-08-26 04:09:41', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'olga-bielska';
        $artist->name = 'Olga Bielska';
        $artist->discogs = 602485;
        $artist->imdb = '0081458';
        $artist->wikipedia = 'Olga_Bielska';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 04:54:34', '2019-08-25 22:21:31', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'piotr-fronczewski';
        $artist->name = 'Piotr Fronczewski';
        $artist->discogs = 602473;
        $artist->imdb = '0296389';
        $artist->wikipedia = 'Piotr_Fronczewski';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 03:28:27', '2019-08-26 02:48:07', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'piotr-pawlowski';
        $artist->name = 'Piotr Pawłowski';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 08:02:12', '2019-08-23 08:02:12', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'renata-kossobudzka';
        $artist->name = 'Renata Kossobudzka';
        $artist->discogs = 1023691;
        $artist->imdb = '0467309';
        $artist->wikipedia = 'Renata_Kossobudzka';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 06:30:37', '2019-08-26 03:41:16', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'robert-golaszewski';
        $artist->name = 'Robert Gołaszewski';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-26 04:09:41', '2019-08-26 04:09:41', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'rudolf-golebiowski';
        $artist->name = 'Rudolf Gołębiowski';
        $artist->discogs = 588581;
        $artist->imdb = '1588351';
        $artist->wikipedia = 'Rudolf_Gołębiowski';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 09:49:43', '2019-08-26 01:03:22', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'ryszard-dembinski';
        $artist->name = 'Ryszard Dembiński';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 08:34:10', '2019-08-23 08:34:10', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'ryszard-dreger';
        $artist->name = 'Ryszard Dreger';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-26 04:09:41', '2019-08-26 04:09:41', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'ryszard-sielicki';
        $artist->name = 'Ryszard Sielicki';
        $artist->discogs = 588568;
        $artist->imdb = '3356793';
        $artist->wikipedia = 'Ryszard_Sielicki';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 06:30:37', '2019-08-26 02:49:13', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'slawomir-pietrzykowski';
        $artist->name = 'Sławomir Pietrzykowski';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-26 04:09:40', '2019-08-26 04:09:40', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'stanislaw-syrewicz';
        $artist->name = 'Stanisław Syrewicz';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 09:53:29', '2019-08-23 09:53:29', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'stanislaw-werner';
        $artist->name = 'Stanisław Werner';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 08:34:10', '2019-08-23 08:34:10', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'stanislaw-zatloka';
        $artist->name = 'Stanisław Zatłoka';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 22:15:41', '2019-08-25 22:15:41', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'stanislawa-celinska';
        $artist->name = 'Stanisława Celińska';
        $artist->discogs = 602474;
        $artist->imdb = '0148077';
        $artist->wikipedia = 'Stanisława_Celińska';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 01:55:33', '2019-08-26 04:01:07', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'stefan-majchrowski';
        $artist->name = 'Stefan Majchrowski';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 04:54:34', '2019-08-25 04:54:34', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'stefan-witas';
        $artist->name = 'Stefan Witas';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 09:53:29', '2019-08-23 09:53:29', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'stefania-iwinska';
        $artist->name = 'Stefania Iwińska';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 08:34:10', '2019-08-23 08:34:10', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'tadeusz-bartosik';
        $artist->name = 'Tadeusz Bartosik';
        $artist->discogs = 1023394;
        $artist->imdb = '0059302';
        $artist->wikipedia = 'Tadeusz_Bartosik';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 06:08:03', '2019-08-25 05:47:48', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'tadeusz-wludarski';
        $artist->name = 'Tadeusz Włudarski';
        $artist->discogs = 591951;
        $artist->imdb = '1466807';
        $artist->wikipedia = 'Tadeusz_W%C5%82udarski';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 06:30:38', '2019-08-25 05:20:12', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'teresa-lipowska';
        $artist->name = 'Teresa Lipowska';
        $artist->discogs = 588567;
        $artist->imdb = '0513557';
        $artist->wikipedia = 'Teresa_Lipowska';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 06:30:38', '2019-08-26 02:46:37', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'tomasz-marzecki';
        $artist->name = 'Tomasz Marzecki';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 07:58:25', '2019-08-23 07:58:25', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'tomasz-zaliwski';
        $artist->name = 'Tomasz Zaliwski';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 04:54:34', '2019-08-25 04:54:34', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'tomek-michnikowski';
        $artist->name = 'Tomek Michnikowski';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-26 04:09:41', '2019-08-26 04:09:41', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'wanda-chotomska';
        $artist->name = 'Wanda Chotomska';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 02:13:14', '2019-08-25 02:13:14', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'wanda-majerowna';
        $artist->name = 'Wanda Majerówna';
        $artist->discogs = 1029107;
        $artist->imdb = '1293565';
        $artist->wikipedia = 'Wanda_Majerówna';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 07:58:25', '2019-08-25 05:58:40', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'wienczyslaw-glinski';
        $artist->name = 'Wieńczysław Gliński';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 03:28:27', '2019-08-25 03:28:27', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'wieslaw-michnikowski';
        $artist->name = 'Wiesław Michnikowski';
        $artist->discogs = 1023338;
        $artist->imdb = '0585239';
        $artist->wikipedia = 'Wiesław_Michnikowski';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 02:33:32', '2019-08-25 05:21:33', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'wieslaw-opalek';
        $artist->name = 'Wiesław Opałek';
        $artist->discogs = 588574;
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 02:33:32', '2019-08-26 02:44:54', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'wiktor-nanowski';
        $artist->name = 'Wiktor Nanowski';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 02:13:15', '2019-08-25 02:13:15', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'wiktor-zborowski';
        $artist->name = 'Wiktor Zborowski';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 08:34:10', '2019-08-23 08:34:10', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'wilhelm-wichurski';
        $artist->name = 'Wilhelm Wichurski';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 08:02:12', '2019-08-23 08:02:12', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'witold-kaluski';
        $artist->name = 'Witold Kałuski';
        $artist->discogs = 3324704;
        $artist->imdb = '0436265';
        $artist->wikipedia = 'Witold_Kałuski';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 06:08:03', '2019-08-26 02:14:23', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'wladyslaw-hancza';
        $artist->name = 'Władysław Hańcza';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 08:02:11', '2019-08-23 08:02:11', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'wladyslaw-rzeczycki';
        $artist->name = 'Władysław Rzeczycki';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 03:35:51', '2019-08-25 03:35:51', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'wladyslaw-slowinski';
        $artist->name = 'Władysław Słowiński';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 04:42:20', '2019-08-25 04:42:20', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'wlodzimierz-bednarski';
        $artist->name = 'Włodzimierz Bednarski';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 04:54:34', '2019-08-25 04:54:34', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'wlodzimierz-nowakowski';
        $artist->name = 'Włodzimierz Nowakowski';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 05:03:04', '2019-08-25 05:03:04', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'wlodzimierz-panasiewicz';
        $artist->name = 'Włodzimierz Panasiewicz';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 09:49:43', '2019-08-23 09:49:43', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'wlodzimierz-twardowski';
        $artist->name = 'Włodzimierz Twardowski';
        $artist->discogs = 1023689;
        $artist->imdb = '0878508';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 06:30:38', '2019-08-26 03:21:49', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'wojciech-maciejewski';
        $artist->name = 'Wojciech Maciejewski';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 04:19:28', '2019-08-25 04:19:28', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'wojciech-siemion';
        $artist->name = 'Wojciech Siemion';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 02:33:32', '2019-08-25 02:33:32', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'wojciech-zagorski';
        $artist->name = 'Wojciech Zagórski';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 02:13:15', '2019-08-25 02:13:15', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'zbigniew-krynski';
        $artist->name = 'Zbigniew Kryński';
        $artist->discogs = 588573;
        $artist->wikipedia = 'Zbigniew_Kryński';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 09:49:43', '2019-08-26 01:07:38', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'zbigniew-pawelec';
        $artist->name = 'Zbigniew Pawelec';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 04:45:36', '2019-08-25 04:45:36', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'zbigniew-turski';
        $artist->name = 'Zbigniew Turski';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 08:02:11', '2019-08-23 08:02:11', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'zdzislaw-salaburski';
        $artist->name = 'Zdzisław Salaburski';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 02:13:14', '2019-08-25 02:13:14', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'zdzislaw-slowinski';
        $artist->name = 'Zdzisław Słowiński';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 04:42:20', '2019-08-25 04:42:20', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'zofia-raciborska';
        $artist->name = 'Zofia Raciborska';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 02:13:14', '2019-08-25 02:13:14', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'zofia-rysiowna';
        $artist->name = 'Zofia Rysiówna';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 04:42:20', '2019-08-25 04:42:20', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'zygmunt-apostol';
        $artist->name = 'Zygmunt Apostoł';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 01:55:33', '2019-08-25 01:55:33', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'zygmunt-boncza-tomaszewski';
        $artist->name = 'Zygmunt Bończa-Tomaszewski';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 08:02:11', '2019-08-23 08:02:11', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'zygmunt-kestowicz';
        $artist->name = 'Zygmunt Kęstowicz';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-25 04:28:56', '2019-08-25 04:28:56', $artist->id]
        );

        $artist = new Artist();
        $artist->slug = 'zygmunt-listkiewicz';
        $artist->name = 'Zygmunt Listkiewicz';
        $artist->save();
        DB::update(
            "update artists set created_at = ?, updated_at = ? where id = ?",
            ['2019-08-23 09:49:43', '2019-08-23 09:49:43', $artist->id]
        );
    }
}
