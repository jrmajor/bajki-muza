<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTalesLyricistsTable extends Migration
{
    public function up()
    {
        Schema::create('tales_lyricists', function (Blueprint $table) {
            $table->smallId();
            $table->smallForeignId('artist_id')
                ->constrained()->restrictOnDelete();
            $table->smallForeignId('tale_id')
                ->constrained()->restrictOnDelete();
            $table->integer('credit_nr')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tales_lyricists');
    }
}
