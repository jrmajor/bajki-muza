<?php

namespace App\Images;

use App\Images\Exceptions\OriginalDoesNotExist;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

abstract class Image implements Castable
{
    protected string $disk;

    public function __construct(
        private string $filename,
    ) {
        $this->disk = config('filesystems.cloud');
    }

    abstract public static function sizes(): Collection;

    public static function store(File|UploadedFile $file, Closure $callback, ...$args)
    {
        $path = static::defaultDisk()
            ->putFile(static::uploadPath(), $file, 'private');

        $filename = Str::afterLast($path, '/');

        $image = new static($filename, ...$args);

        $image->process($callback);

        return $image;
    }

    abstract protected function process(Closure $callback): void;

    public function reprocess(Closure $callback): void
    {
        if ($this->originalMissing()) {
            throw new OriginalDoesNotExist($this->originalPath());
        }

        $this->deleteResponsiveVariants();

        $this->process($callback);
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
            ->filter(fn ($size) => $this->responsiveVariantMissing($size));
    }

    protected function deleteResponsiveVariants(): bool
    {
        $variantsToDelete = static::sizes()
            ->map(fn ($size) => $this->path($size))
            ->all();

        return $this->disk()->delete($variantsToDelete);
    }

    protected static function defaultDisk(): FilesystemAdapter
    {
        return Storage::disk(config('filesystems.cloud'));
    }

    protected function disk(): FilesystemAdapter
    {
        return Storage::disk($this->disk);
    }

    public static function castUsing(array $arguments)
    {
        return new ImageCast(static::class);
    }
}
