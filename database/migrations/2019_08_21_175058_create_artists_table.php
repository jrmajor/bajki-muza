<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArtistsTable extends Migration
{
    public function up()
    {
        Schema::create('artists', function (Blueprint $table) {
            $table->smallId();
            $table->char('slug', 100);
            $table->char('name', 100);
            $table->integer('discogs')->nullable();
            $table->char('imdb', 10)->nullable();
            $table->char('wikipedia', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('artists');
    }
}
