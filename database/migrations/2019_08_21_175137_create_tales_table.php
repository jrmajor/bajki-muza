<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTalesTable extends Migration
{
    public function up()
    {
        Schema::create('tales', function (Blueprint $table) {
            $table->increments('id');
            $table->char('slug', 100);
            $table->char('title', 100);
            $table->year('year')->nullable();
            $table->integer('director_id')->unsigned()->nullable();
            $table->char('nr', 4)->nullable();
            $table->char('cover', 100)->nullable();
            $table->timestamps();

            $table->foreign('director_id')
                ->references('id')->on('artists');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tales');
    }
}
