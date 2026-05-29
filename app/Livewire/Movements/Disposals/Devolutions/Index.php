<?php

namespace App\Livewire\Movements\Disposals\Devolutions;

use App\Livewire\Traits\Pagination\CanPaginateManually;
use App\Livewire\Traits\SetUser;
use App\Models\Movements\DisposalDevolution;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, CanPaginateManually, SetUser;

    public int $perPage = 15;

    public function render()
    {
        return view('livewire.movements.disposals.devolutions.index', [
            'disposals' => $this->query()
        ]);
    }

    protected function query()
    {
        return $this->paginate(
            DisposalDevolution::queryOwnModels()->orderBy('created_at', 'desc')->get(),
            $this->perPage
        );
    }
}
