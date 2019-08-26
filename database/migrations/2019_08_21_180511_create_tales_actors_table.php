<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTalesActorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tales_actors', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('artist_id')->unsigned();
            $table->integer('tale_id')->unsigned();
            $table->char('characters', 100)->nullable();
            $table->integer('credit_nr')->nullable();
            $table->timestamps();

            $table->foreign('artist_id')
                ->references('id')->on('artists')
                ->onDelete('cascade');

            $table->foreign('tale_id')
                ->references('id')->on('tales')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tales_actors');
    }
}
