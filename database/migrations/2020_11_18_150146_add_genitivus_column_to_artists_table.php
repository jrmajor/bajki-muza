<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGenitivusColumnToArtistsTable extends Migration
{
    public function up()
    {
        Schema::table('artists', function (Blueprint $table) {
            $table->char('genetivus', 100)->nullable()->after('name');
        });
    }

    public function down()
    {
        Schema::table('artists', function (Blueprint $table) {
            $table->dropColumn('genetivus');
        });
    }
}
