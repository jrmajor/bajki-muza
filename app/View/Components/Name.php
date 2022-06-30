<?php

namespace App\View\Components;

use App\Models\Artist;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use stdClass;

final class Name extends Component
{
    public function __construct(
        public Artist $artist,
        public stdClass $loop,
        public string $before = '',
        public bool $genetivus = false,
    ) { }

    public function name(): string
    {
        if (! $this->genetivus) {
            return $this->artist->name;
        }

        return $this->artist->genetivus ?? $this->artist->name;
    }

    public function appearances(): int
    {
        return $this->artist->appearances
            ?? $this->artist->appearances();
    }

    public function render(): View
    {
        return view('components.name');
    }
}
