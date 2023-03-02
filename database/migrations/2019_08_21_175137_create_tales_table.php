<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTalesTable extends Migration
{
    public function up(): void
    {
        Schema::create('tales', function (Blueprint $table) {
            $table->smallId();

            $table->string('slug', 100);
            $table->string('title', 100);
            $table->year('year')->nullable();
            $table->string('nr', 4)->nullable();

            $table->string('cover', 64)->nullable();
            $table->binary('cover_placeholder')->nullable();

            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tales');
    }
}
