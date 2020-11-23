<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePlaceholdersToBlobs extends Migration
{
    public function up()
    {
        Schema::table('artists', function (Blueprint $table) {
            $table->binary('photo_face_placeholder')->nullable()->change();
            $table->binary('photo_placeholder')->nullable()->change();
        });

        Schema::table('tales', function (Blueprint $table) {
            $table->binary('cover_placeholder')->nullable()->change();
        });
    }
}
