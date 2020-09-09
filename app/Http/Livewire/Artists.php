<?php

namespace App\Http\Livewire;

use App\Models\Artist;
use Livewire\Component;
use Livewire\WithPagination;

class Artists extends Component
{
    use WithPagination;

    public $search;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $artists = blank($this->search)
            ? Artist::countAppearances()->orderBy('name')->paginate(30)
            : Artist::where('name', 'like', '%'.$this->search.'%')
                ->countAppearances()->orderBy('name')->paginate(30);

        return view('artists.index', ['artists' => $artists])
            ->extends('layouts.app');
    }

    public function paginationView()
    {
        return 'vendor.pagination.tailwind';
    }
}
