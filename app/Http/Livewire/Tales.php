<?php

namespace App\Http\Livewire;

use App\Models\Tale;
use Livewire\Component;
use Livewire\WithPagination;

class Tales extends Component
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
        $tales = blank($this->search)
            ? Tale::orderBy('year')->orderBy('title')
                ->paginate(20)
            : Tale::where('title', 'like', '%'.$this->search.'%')
                ->orderBy('year')->orderBy('title')
                ->paginate(20);

        return view('tales.index')
            ->with('tales', $tales)
            ->extends('layouts.app');
    }

    public function paginationView()
    {
        return 'vendor.pagination.tailwind';
    }
}
