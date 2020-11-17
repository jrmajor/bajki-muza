<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class DropLyricistsAndComposersTables extends Migration
{
    public function up()
    {
        Schema::drop('tales_lyricists');
        Schema::drop('tales_composers');
    }
}
