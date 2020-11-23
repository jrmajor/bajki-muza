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

            $table->string('slug', 100);
            $table->string('name', 100);
            $table->string('genetivus', 100)->nullable();

            $table->integer('discogs')->nullable();
            $table->string('imdb', 10)->nullable();
            $table->integer('filmpolski')->nullable();
            $table->string('wikipedia', 100)->nullable();

            $table->string('photo', 100)->nullable();
            $table->string('photo_source', 128)->nullable();
            $table->unsignedSmallInteger('photo_width')->nullable();
            $table->unsignedSmallInteger('photo_height')->nullable();
            $table->json('photo_crop')->nullable();
            $table->binary('photo_face_placeholder')->nullable();
            $table->binary('photo_placeholder')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('artists');
    }
}
