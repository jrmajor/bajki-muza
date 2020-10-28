<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhotoCropColumnToArtistsTable extends Migration
{
    public function up()
    {
        Schema::table('artists', function (Blueprint $table) {
            $table->json('photo_crop')->nullable()->after('photo_height');
        });
    }

    public function down()
    {
        Schema::table('artists', function (Blueprint $table) {
            $table->dropColumn('photo_crop');
        });
    }
}
