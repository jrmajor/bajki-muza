<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTalesComposersTable extends Migration
{
    public function up()
    {
        Schema::create('tales_composers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('artist_id')->unsigned();
            $table->integer('tale_id')->unsigned();
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

    public function down()
    {
        Schema::dropIfExists('tales_composers');
    }
}
