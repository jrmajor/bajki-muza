<?php

namespace App\Livewire;

use App\Models\Tale;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class Tales extends Component
{
    use WithPagination;

    public string $search = '';

    /** @phpstan-var numeric-string|'' */
    public string $discogs = '';

    /** @var list<string> */
    protected $queryString = [
        'search',
        'discogs',
    ];

    public function mount(): void
    {
        if ($this->discogs !== '') {
            $this->discogs = (string) (int) $this->discogs;
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $tales = Tale::query()
            ->withActorsPopularity()
            ->unless(blank($this->search), function (Builder $query) {
                $query->where('title', 'like', "%{$this->search}%");
            })
            ->unless(blank($this->discogs), function (Builder $query) {
                $query->whereNull('discogs', not: (bool) $this->discogs);
            })
            ->orderByDesc('popularity')->orderBy('year')->orderBy('title')->paginate(20);

        return view('tales.index')
            ->with('tales', $tales)
            ->extends('layouts.app-classic');
    }

    /**
     * @phpstan-return view-string
     */
    public function paginationView(): string
    {
        return 'vendor.pagination.tailwind';
    }
}
