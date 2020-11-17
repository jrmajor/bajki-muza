<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTalesTable extends Migration
{
    public function up()
    {
        Schema::create('tales', function (Blueprint $table) {
            $table->smallId();
            $table->char('slug', 100);
            $table->char('title', 100);
            $table->year('year')->nullable();
            $table->smallForeignId('director_id')->nullable()
                    ->references('id')->on('artists')
                    ->restrictOnDelete();
            $table->char('nr', 4)->nullable();
            $table->char('cover', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tales');
    }
}
