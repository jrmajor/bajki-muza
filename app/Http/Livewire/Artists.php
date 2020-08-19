<?php

namespace App\Http\Livewire;

use App\Artist;
use Livewire\Component;
use Livewire\WithPagination;

class Artists extends Component
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
        $artists = blank($this->search)
            ? Artist::orderBy('name')->paginate(30)
            : Artist::where('name', 'like', '%'.$this->search.'%')
                ->orderBy('name')->paginate(30);

        return view('artists.index', ['artists' => $artists]);
    }

    public function paginationView()
    {
        return 'vendor.pagination.tailwind';
    }
}
