<?php

namespace App\Http\Livewire;

use App\Tale;
use Livewire\Component;
use Livewire\WithPagination;

class Tales extends Component
{
    use WithPagination;

    public $search;

    protected $updatesQueryString = [
        'search' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->search = request()->query('search');
    }

    public function render()
    {
        $tales = blank($this->search)
            ? Tale::orderBy('year')->orderBy('title')
                ->paginate(20)
            : Tale::where('title', 'like', '%'.$this->search.'%')
                ->orderBy('year')->orderBy('title')
                ->paginate(20);

        return view('tales.index', ['tales' => $tales]);
    }

    public function paginationView()
    {
        return 'vendor.pagination.tailwind';
    }
}
