<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropDirectorColumnFromTalesTable extends Migration
{
    public function up()
    {
        Schema::table('tales', function (Blueprint $table) {
            $table->dropForeign('tales_director_id_foreign');
            $table->dropColumn('director_id');
        });
    }
}
