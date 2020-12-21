<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveImgageRelatedColumns extends Migration
{
    public function up()
    {
        Schema::table('tales', function (Blueprint $table) {
            $table->dropColumn('cover_placeholder');
        });

        Schema::table('artists', function (Blueprint $table) {
            $table->dropColumn(
                'photo_source',
                'photo_width',
                'photo_height',
                'photo_crop',
                'photo_placeholder',
                'photo_face_placeholder',
            );

        });
    }
}
