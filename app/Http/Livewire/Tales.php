<?php

namespace App\Http\Livewire;

use App\Models\Tale;
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

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $tales = Tale::withActorsPopularity()
            ->unless(blank($this->search), function ($query) {
                $query->where('title', 'like', "%{$this->search}%");
            })
            ->unless(is_null($this->discogs), function ($query) {
                $query->whereNull('discogs', not: (bool) $this->discogs);
            })
            ->orderByDesc('popularity')->orderBy('year')->orderBy('title')->paginate(20);

        return view('tales.index')
            ->with('tales', $tales)
            ->extends('layouts.app');
    }

    public function paginationView()
    {
        return 'vendor.pagination.tailwind';
    }
}
