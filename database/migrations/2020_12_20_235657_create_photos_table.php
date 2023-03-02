<?php

use App\Images\Photo;
use App\Models\Artist;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhotosTable extends Migration
{
    public function up(): void
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->string('filename', 64)->primary();

            $table->string('source', 128)->nullable();

            $table->unsignedSmallInteger('width')->nullable();
            $table->unsignedSmallInteger('height')->nullable();

            $table->json('crop');

            $table->binary('placeholder')->nullable();
            $table->binary('face_placeholder')->nullable();

            $table->timestamps();
        });

        Artist::whereNotNull('photo')->get()
            ->each(function ($artist) {
                Photo::create([
                    'filename' => $artist->photo,
                    'source' => $artist->photo_source,
                    'width' => $artist->photo_width,
                    'height' => $artist->photo_height,
                    'crop' => $artist->photo_crop,
                    'placeholder' => $artist->photo_placeholder,
                    'face_placeholder' => $artist->photo_face_placeholder,
                    'created_at' => $artist->updated_at,
                    'updated_at' => now(),
                ]);
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
}
