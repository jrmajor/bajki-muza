<?php

use App\Jobs\ProcessTaleCover;
use App\Models\Tale;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

it('works', function () {
    $this->filename = Str::random('10').'.jpg';

    // Photo by David Grandmougin on Unsplash
    $file = fopen(__DIR__.'/cover.jpg', 'r');

    Storage::cloud()->put("covers/original/$this->filename", $file, 'public');

    $this->tale = Tale::factory()
        ->create(['cover' => $this->filename]);

    ProcessTaleCover::dispatchSync($this->tale);

    expect($this->tale->fresh()->cover_placeholder)
        ->toBe(file_get_contents(__DIR__.'/placeholder.b64'));

    expect(file_get_contents(storage_path("testing/covers/128/$this->filename")))
        ->toBe(file_get_contents(__DIR__.'/cover_128.jpg'));

    Storage::cloud()->delete("covers/original/$this->filename");
    rmdir(storage_path("testing/covers/original"));

    foreach(ProcessTaleCover::$sizes as $size) {
        Storage::cloud()->delete("covers/$size/$this->filename");
        rmdir(storage_path("testing/covers/$size"));
    }
});
