<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhotoFilenameColumnInArtistsTable extends Migration
{
    public function up(): void
    {
        Schema::table('artists', function (Blueprint $table) {
            $table->renameColumn('photo', 'photo_filename')->nullable();

            $table->foreign('photo_filename')
                ->references('filename')->on('photos');
        });
    }
}
