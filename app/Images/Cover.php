<?php

namespace App\Images;

use App\Images\Jobs\GenerateTaleCoverPlaceholder;
use App\Images\Jobs\GenerateTaleCoverVariants;
use App\Models\Tale;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;

class Cover extends Image
{
    public static function sizes(): Collection
    {
        return collect([
            60, // 3.75rem
            90, // 3.75rem * 1.5
            120, // 3.75rem * 2
            128, // 8rem
            192, // 12rem, 8rem * 1.5
            288, // 12rem * 1.5
            256, // 8rem * 2
            384, // 12rem * 2
        ]);
    }

    protected function process(): void
    {
        Bus::chain([
            new GenerateTaleCoverPlaceholder($this),
            new GenerateTaleCoverVariants($this),
        ])->dispatch();
    }

    protected static function uploadPath(): string
    {
        return 'covers/original';
    }

    public function originalPath(): string
    {
        return "covers/original/{$this->filename()}";
    }

    public function path(int $size): string
    {
        return "covers/{$size}/{$this->filename()}";
    }

    public function tales(): HasMany
    {
        return $this->hasMany(Tale::class, 'cover_filename', 'filename');
    }
}
