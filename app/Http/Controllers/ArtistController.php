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

        $photoCrop = $request->photoCrop();

        $photoSource = $data['photo_source'] ?? null;

        if ($request->boolean('remove_photo')) {
            $artist->photo()->disassociate()->save();
        } elseif ($request->file('photo')) {
            $artist->photo()->associate(
                Photo::store($request->file('photo'), [
                    'crop' => $photoCrop,
                    'source' => $photoSource,
                ]),
            )->save();
        } elseif ($data['photo_uri'] ?? null) {
            $artist->photo()->associate(
                $this->storePhotoFromUrl(
                    $data['photo_uri'], $photoCrop, $photoSource,
                ),
            )->save();
        } elseif (
            $artist->photo
            && $photoCrop?->toArray() != $artist->photo_crop?->toArray()
        ) {
            $artist->photo
                ->setAttribute('source', $photoSource)
                ->setCrop($photoCrop)
                ->reprocess();
        } elseif ($artist->photo) {
            $artist->photo
                ->setAttribute('source', $photoSource)
                ->save();
        }

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
        string $source,
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
            compact('crop', 'source'),
        );

        $temporaryDirectory->delete();

        return $photo;
    }
}
