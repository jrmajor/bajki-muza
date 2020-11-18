<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotesColumnToTalesTable extends Migration
{
    public function up()
    {
        Schema::table('tales', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('cover_placeholder');
        });
    }

    public function down()
    {
        Schema::table('tales', function (Blueprint $table) {
            $table->removeColumn('notes');
        });
    }
}
