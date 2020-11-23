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

            $table->string('slug', 100);
            $table->string('title', 100);
            $table->year('year')->nullable();
            $table->string('nr', 4)->nullable();

            $table->string('cover', 100)->nullable();
            $table->string('cover_placeholder', 8192)->nullable();

            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tales');
    }
}
