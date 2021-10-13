<?php

namespace App\Http\Livewire;

use App\Models\Tale;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class Tales extends Component
{
    use WithPagination;

    public $search;

    public $discogs;

    protected $queryString = [
        'search' => ['except' => ''],
        'discogs' => ['except' => null],
    ];

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
            ->unless(is_null($this->discogs), function (Builder $query) {
                $query->whereNull('discogs', not: (bool) $this->discogs);
            })
            ->orderByDesc('popularity')->orderBy('year')->orderBy('title')->paginate(20);

        return view('tales.index')
            ->with('tales', $tales)
            ->extends('layouts.app');
    }

    /**
     * @phpstan-return view-string
     */
    public function paginationView(): string
    {
        return 'vendor.pagination.tailwind';
    }
}
