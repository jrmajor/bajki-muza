<?php

use App\Images\Cover;
use App\Models\Tale;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoversTable extends Migration
{
    public function up(): void
    {
        Schema::create('covers', function (Blueprint $table) {
            $table->string('filename', 64)->primary();

            $table->binary('placeholder')->nullable();

            $table->timestamps();
        });

        Tale::whereNotNull('cover')->get()
            ->each(function ($tale) {
                Cover::create([
                    'filename' => $tale->cover,
                    'placeholder' => $tale->cover_placeholder,
                    'created_at' => $tale->updated_at,
                    'updated_at' => now(),
                ]);
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('covers');
    }
}
