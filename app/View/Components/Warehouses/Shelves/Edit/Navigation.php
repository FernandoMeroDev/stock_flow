<?php

namespace App\View\Components\Warehouses\Shelves\Edit;

use App\Models\Shelf;
use App\Models\Shelves\Level;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Navigation extends Component
{
    public Shelf $shelf;

    public Level $level;

    public ?int $previous_shelf_id = null;

    public ?int $next_shelf_id = null;

    public ?int $previous_level_id = null;

    public ?int $next_level_id = null;

    /**
     * Create a new component instance.
     */
    public function __construct(Level $level)
    {
        $this->level = $level;
        $this->shelf = $level->shelf;
        
        $levels = $this->shelf->levels()->orderBy('number')->get();
        $this->findSilbings('level', $levels);

        $shelves = $this->shelf->warehouse->shelves()->orderBy('number')->get();
        $this->findSilbings('shelf', $shelves);
    }

    private function findSilbings($object_name, $collection): void
    {
        for($i = 1; $i <= $collection->count(); $i++){
            $object = $collection->get($i - 1);
            if($object->id == $this->{$object_name}->id){
                $this->{'previous_'.$object_name.'_id'} = $i > 1 ? $collection->get($i - 2)->id : null;
                $this->{'next_'.$object_name.'_id'} = $i < $collection->count() ? $collection->get($i)->id : null;
            }
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.warehouses.shelves.edit.navigation');
    }

    public function getLevelRoute($position): string
    {
        return is_null($this->{$position.'_level_id'}) ? '' : 'href=' . route('levels.edit', $this->{$position.'_level_id'});
    }

    public function getShelfRoute($position): string
    {
        if( ! is_null($this->{$position.'_shelf_id'}) ) {
            $level = Shelf::find($this->{$position.'_shelf_id'})->levels()->where('number', 1)->first();
            return 'href=' . route('levels.edit', $level->id);
        }
        return '';
    }
}
