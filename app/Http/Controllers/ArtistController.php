<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArtist;
use App\Images\Photo;
use App\Images\Values\ArtistPhotoCrop;
use App\Models\Artist;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
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
            $artist->removePhoto();
        } elseif ($request->file('photo')) {
            $this->storePhoto($request->file('photo'), $photoCrop, $artist);
        } elseif ($data['photo_uri'] ?? null) {
            $this->storePhotoFromUrl($data['photo_uri'], $photoCrop, $artist);
        } elseif (
            $artist->photo
            && $photoCrop?->toArray() != $artist->photo_crop?->toArray()
        ) {
            $artist->photo
                ->setCrop($photoCrop)
                ->reprocess(
                    function (
                        Photo $photo,
                        int $width,
                        int $height,
                        string $placeholder,
                        string $facePlaceholder,
                    ) use ($artist) {
                        $artist->forceFill([
                            'photo_width' => $width,
                            'photo_height' => $height,
                            'photo_crop' => $photo->crop(),
                            'photo_face_placeholder' => $facePlaceholder,
                            'photo_placeholder' => $placeholder,
                        ])->save();
                    },
                );
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

    private function storePhoto(
        File|UploadedFile $photo,
        ArtistPhotoCrop $photoCrop,
        Artist $artist
    ): Photo {
        return Photo::store(
            $photo,
            function (
                Photo $photo,
                int $width,
                int $height,
                string $placeholder,
                string $facePlaceholder,
            ) use ($artist) {
                $artist->forceFill([
                    'photo' => $photo,
                    'photo_width' => $width,
                    'photo_height' => $height,
                    'photo_crop' => $photo->crop(),
                    'photo_face_placeholder' => $facePlaceholder,
                    'photo_placeholder' => $placeholder,
                ])->save();
            },
            $photoCrop,
        );
    }

    private function storePhotoFromUrl(
        string $uri,
        ArtistPhotoCrop $photoCrop,
        Artist $artist,
    ): Photo {
        $contents = Http::get($uri);

        $temporaryDirectory = (new TemporaryDirectory)->create();

        $targetFile = $temporaryDirectory->path('uploaded-photo.jpeg');

        touch($targetFile);

        $targetStream = fopen($targetFile, 'a');

        fwrite($targetStream, $contents);

        fclose($targetStream);

        $file = new File($targetFile, checkPath: true);

        $photo = $this->storePhoto($file, $photoCrop, $artist);

        $temporaryDirectory->delete();

        return $photo;
    }
}
