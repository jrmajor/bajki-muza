<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditsTable extends Migration
{
    public function up()
    {
        Schema::create('credits', function (Blueprint $table) {
            $table->smallId('id');

            $table->smallForeignId('tale_id')
                ->constrained()->restrictOnDelete();

            $table->smallForeignId('artist_id')
                ->constrained()->restrictOnDelete();

            $table->char('type', 32);
            $table->char('as', 32)->nullable();
            $table->tinyInteger('nr')->unsigned();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('credits');
    }
}
