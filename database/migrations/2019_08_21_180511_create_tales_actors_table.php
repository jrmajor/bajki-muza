<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTalesActorsTable extends Migration
{
    public function up(): void
    {
        Schema::create('tales_actors', function (Blueprint $table) {
            $table->smallId();

            $table->smallForeignId('artist_id')
                ->constrained()->restrictOnDelete();

            $table->smallForeignId('tale_id')
                ->constrained()->restrictOnDelete();

            $table->string('characters', 100)->nullable();
            $table->integer('credit_nr')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tales_actors');
    }
}
