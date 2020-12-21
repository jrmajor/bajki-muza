<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArtist;
use App\Images\Photo;
use App\Images\Values\ArtistPhotoCrop;
use App\Models\Artist;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Http;
use Spatie\TemporaryDirectory\TemporaryDirectory;

class ArtistController extends Controller
{
    public function show(Artist $artist)
    {
        return view('artists.show', ['artist' => $artist]);
    }

    public function edit(Artist $artist)
    {
        return view('artists.edit', ['artist' => $artist]);
    }

    public function update(StoreArtist $request, Artist $artist)
    {
        $artist->fill(
            $data = $request->validated(),
        )->save();

        $photoCrop = $data['photo_crop'] ?? null !== null
            ? ArtistPhotoCrop::fromStrings($data['photo_crop'])
            : null;

        if ($request->boolean('remove_photo')) {
            $artist->photo()->disassociate()->save();
        } elseif ($request->file('photo')) {
            $artist->photo()->associate(
                Photo::store($request->file('photo'), ['crop' => $photoCrop]),
            )->save();
        } elseif ($data['photo_uri'] ?? null) {
            $artist->photo()->associate(
                $this->storePhotoFromUrl($data['photo_uri'], $photoCrop),
            )->save();
        } elseif (
            $artist->photo
            && $photoCrop?->toArray() != $artist->photo_crop?->toArray()
        ) {
            $artist->photo
                ->setCrop($photoCrop)
                ->reprocess();
        }

        $artist->flushCache();

        return redirect()->route('artists.show', $artist);
    }

    public function flushCache(Artist $artist)
    {
        $artist->flushCache();

        return redirect()->route('artists.show', $artist);
    }

    public function destroy(Artist $artist)
    {
        $artist->delete();

        return redirect()->route('artists.index');
    }

    private function storePhotoFromUrl(
        string $uri,
        ArtistPhotoCrop $crop,
    ): Photo {
        $contents = Http::get($uri);

        $temporaryDirectory = (new TemporaryDirectory)->create();

        $targetFile = $temporaryDirectory->path('uploaded-photo.jpeg');

        touch($targetFile);

        $targetStream = fopen($targetFile, 'a');

        fwrite($targetStream, $contents);

        fclose($targetStream);

        $photo = Photo::store(
            new File($targetFile, checkPath: true),
            ['crop' => $crop],
        );

        $temporaryDirectory->delete();

        return $photo;
    }
}
