<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoverFilenameColumnInTalesTable extends Migration
{
    public function up(): void
    {
        Schema::table('tales', function (Blueprint $table) {
            $table->renameColumn('cover', 'cover_filename')->nullable();

            $table->foreign('cover_filename')
                ->references('filename')->on('covers');
        });
    }
}
