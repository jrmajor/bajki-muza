<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhotoMetadataAndPlaceholderToArtistsTable extends Migration
{
    public function up()
    {
        Schema::table('artists', function (Blueprint $table) {
            $table->string('photo', 100)->nullable()->after('wikipedia');
            $table->unsignedSmallInteger('photo_width')->nullable()->after('photo');
            $table->unsignedSmallInteger('photo_height')->nullable()->after('photo_width');
            $table->string('photo_placeholder', 8192)->nullable()->after('photo_height');
        });
    }

    public function down()
    {
        Schema::table('artists', function (Blueprint $table) {
            $table->dropColumn('photo');
            $table->dropColumn('photo_width');
            $table->dropColumn('photo_height');
            $table->dropColumn('photo_placeholder');
        });
    }
}
