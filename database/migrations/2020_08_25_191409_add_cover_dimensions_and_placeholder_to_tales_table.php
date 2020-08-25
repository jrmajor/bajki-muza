<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCoverDimensionsAndPlaceholderToTalesTable extends Migration
{
    public function up()
    {
        Schema::table('tales', function (Blueprint $table) {
            $table->string('cover_placeholder', 8192)->nullable()->after('cover');
        });
    }

    public function down()
    {
        Schema::table('tales', function (Blueprint $table) {
            $table->dropColumn('cover_placeholder');
        });
    }
}
