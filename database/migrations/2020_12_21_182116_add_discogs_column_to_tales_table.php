<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscogsColumnToTalesTable extends Migration
{
    public function up(): void
    {
        Schema::table('tales', function (Blueprint $table) {
            $table->unsignedInteger('discogs')->nullable()->after('nr');
        });
    }

    public function down(): void
    {
        Schema::table('tales', function (Blueprint $table) {
            $table->dropColumn('discogs');
        });
    }
}
