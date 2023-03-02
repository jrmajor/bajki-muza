<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditsTable extends Migration
{
    public function up(): void
    {
        Schema::create('credits', function (Blueprint $table) {
            $table->smallId('id');

            $table->smallForeignId('tale_id')
                ->constrained()->restrictOnDelete();

            $table->smallForeignId('artist_id')
                ->constrained()->restrictOnDelete();

            $table->string('type', 32);
            $table->string('as', 32)->nullable();

            $table->tinyInteger('nr')->unsigned();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credits');
    }
}
