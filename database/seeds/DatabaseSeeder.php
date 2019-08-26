<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersSeeder::class);
        $this->call(ArtistsSeeder::class);
        $this->call(TalesSeeder::class);
        $this->call(TalesLyricistsSeeder::class);
        $this->call(TalesComposersSeeder::class);
        $this->call(TalesActorsSeeder::class);
    }
}
