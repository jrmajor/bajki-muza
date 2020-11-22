<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAsColumnToCreditsTable extends Migration
{
    public function up()
    {
        Schema::table('credits', function (Blueprint $table) {
            $table->char('as', 32)->nullable()->after('type');
        });
    }

    public function down()
    {
        Schema::table('credits', function (Blueprint $table) {
            $table->dropColumn('as');
        });
    }
}
