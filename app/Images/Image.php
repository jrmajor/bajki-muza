<?php

namespace App\Images;

use App\Images\Exceptions\OriginalDoesNotExist;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

abstract class Image extends Model
{
    protected $primaryKey = 'filename';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $guarded = [];

    abstract public static function sizes(): Collection;

    public static function store(File|UploadedFile $file, array $attributes = []): static
    {
        $path = static::disk()
            ->putFile(static::uploadPath(), $file, 'private');

        $filename = Str::afterLast($path, '/');

        $image = static::create(
            array_merge(compact('filename'), $attributes),
        );

        $image->process();

        return $image;
    }

    abstract protected function process(): void;

    public function reprocess(): void
    {
        if ($this->originalMissing()) {
            throw new OriginalDoesNotExist($this->originalPath());
        }

        $this->deleteResponsiveVariants();

        $this->process();
    }

    public function filename(): string
    {
        return $this->filename;
    }

    abstract protected static function uploadPath(): string;

    abstract public function originalPath(): string;

    abstract public function path(int $size): string;

    public function originalUrl(?Carbon $expiration = null): string
    {
        return $this->disk()->temporaryUrl(
            $this->originalPath(),
            $expiration ?? now()->addMinutes(15),
        );
    }

    public function url(int $size): string
    {
        return $this->disk()->url($this->path($size));
    }

    public function placeholder(): ?string
    {
        return $this->placeholder;
    }

    public function originalMissing(): bool
    {
        return $this->disk()->missing($this->originalPath());
    }

    public function responsiveVariantMissing(int $size): bool
    {
        return $this->disk()->missing($this->path($size));
    }

    public function missingResponsiveVariants(): Collection
    {
        return static::sizes()
            ->filter(fn ($size) => $this->responsiveVariantMissing($size))
            ->values();
    }

    protected function deleteResponsiveVariants(): bool
    {
        $variantsToDelete = static::sizes()
            ->map(fn ($size) => $this->path($size))
            ->all();

        return $this->disk()->delete($variantsToDelete);
    }

    public static function disk(): FilesystemAdapter
    {
        return Storage::disk(config('filesystems.cloud'));
    }
}
