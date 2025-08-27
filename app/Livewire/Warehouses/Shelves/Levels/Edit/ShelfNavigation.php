<?php

namespace App\Livewire\Warehouses\Shelves\Levels\Edit;

use App\Models\Shelves\Level;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ShelfNavigation extends Component
{
    #[Locked]
    public Level $current_level;

    public function mount(Level $level)
    {
        $this->current_level = $level;
    }

    public function render()
    {
        return view('livewire.warehouses.shelves.levels.edit.shelf-navigation');
    }
}
