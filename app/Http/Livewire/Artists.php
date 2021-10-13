<?php

namespace App\Http\Livewire;

use App\Models\Artist;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class Artists extends Component
{
    use WithPagination;

    public $search;

    public $min;

    public $max;

    protected $queryString = [
        'search' => ['except' => ''],
        'min' => ['except' => ''],
        'max' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $artists = Artist::query()
            ->countAppearances()
            ->unless(blank($this->search), function (Builder $query) {
                $query->where('name', 'like', "%{$this->search}%");
            })
            ->unless(blank($this->min), function (Builder $query) {
                $query->having('appearances', '>=', (int) $this->min);
            })
            ->unless(blank($this->max), function (Builder $query) {
                $query->having('appearances', '<=', (int) $this->max);
            })
            ->orderByDesc('appearances')->orderBy('name')->paginate(30);

        return view('artists.index')
            ->with('artists', $artists)
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
