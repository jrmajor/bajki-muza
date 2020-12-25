<?php

namespace App\Http\Livewire;

use App\Models\Artist;
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

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $artists = Artist::countAppearances()
            ->unless(blank($this->search), function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
            })
            ->unless(blank($this->min), function ($query) {
                $query->having('appearances', '>=', (int) $this->min);
            })
            ->unless(blank($this->max), function ($query) {
                $query->having('appearances', '<=', (int) $this->max);
            })
            ->orderByDesc('appearances')->orderBy('name')->paginate(30);

        return view('artists.index')
            ->with('artists', $artists)
            ->extends('layouts.app');
    }

    public function paginationView()
    {
        return 'vendor.pagination.tailwind';
    }
}
