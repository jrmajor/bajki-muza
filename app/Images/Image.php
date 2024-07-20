<?php

namespace App\Images;

use App\Images\Exceptions\OriginalDoesNotExist;
use App\Images\Jobs\ProcessImage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\File as LaravelFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Interfaces\ImageInterface;
use Psl\File;
use Psl\Filesystem;
use Psl\Str;
use Psl\Type;
use Psl\Vec;

abstract class Image extends Model
{
    protected $primaryKey = 'filename';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $guarded = [];

    abstract public function processVariant(ImageInterface $image, string $variant): ImageInterface;

    abstract protected static function uploadPath(): string;

    abstract public function originalPath(): string;

    abstract public function path(string $variant): string;

    /**
     * @return list<string>
     */
    public static function variants(): array
    {
        return ['default'];
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public static function store(LaravelFile|UploadedFile|string $file, array $attributes = []): static
    {
        if (Type\string()->matches($file)) {
            return self::storeFromUrl($file, $attributes);
        }

        $path = static::disk()->putFile(static::uploadPath(), $file, 'private');

        assert(is_string($path));

        /** @var static */
        return tap(
            static::create(['filename' => Str\after_last($path, '/'), ...$attributes]),
            fn (self $image) => $image->dispatchProcessingJob(),
        );
    }

    /**
     * @param array<string, mixed> $attributes
     */
    protected static function storeFromUrl(string $url, array $attributes): static
    {
        $content = Http::get($url)->throw()->body();

        $tempPath = Filesystem\create_temporary_file();

        File\write($tempPath, $content);

        $photo = self::store(new LaravelFile($tempPath), $attributes);

        Filesystem\delete_file($tempPath);

        return $photo;
    }

    private function dispatchProcessingJob(): void
    {
        Bus::dispatch(new ProcessImage($this));
    }

    public function reprocess(): void
    {
        if ($this->originalMissing()) {
            throw new OriginalDoesNotExist($this->originalPath());
        }

        $this->deleteVariants();

        $this->dispatchProcessingJob();
    }

    /**
     * @return non-empty-string
     */
    public function filename(): string
    {
        return $this->getAttribute('filename');
    }

    public function originalUrl(?Carbon $expiration = null): string
    {
        return $this->disk()->temporaryUrl(
            $this->originalPath(),
            $expiration ?? now()->addMinutes(15),
        );
    }

    public function url(string $variant = 'default'): string
    {
        return $this->disk()->url($this->path($variant));
    }

    public function placeholder(string $variant = 'default'): ?string
    {
        $prefix = $variant === 'default' ? '' : "{$variant}_";

        return $this->getAttributeValue("{$prefix}placeholder");
    }

    public function originalMissing(): bool
    {
        return $this->disk()->missing($this->originalPath());
    }

    public function variantMissing(string $variant): bool
    {
        return $this->disk()->missing($this->path($variant));
    }

    /**
     * @return list<string>
     */
    public function missingVariants(): array
    {
        return Vec\filter(
            static::variants(),
            fn ($size) => $this->variantMissing($size),
        );
    }

    protected function deleteVariants(): bool
    {
        $variantsToDelete = Vec\map(
            static::variants(),
            fn ($variant) => $this->path($variant),
        );

        return $this->disk()->delete($variantsToDelete);
    }

    public static function disk(): FilesystemAdapter
    {
        return Storage::disk(config('filesystems.media'));
    }

    public static function shouldSaveDimensions(): bool
    {
        return false;
    }
}
