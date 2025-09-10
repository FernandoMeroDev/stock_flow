<?php

namespace App\Livewire\Sales\Day;

use Carbon\Carbon;

trait Navigable {
    public function nextDay()
    {
        $this->validate();
        $this->date = (new Carbon($this->date))->addDay(1)->format('Y-m-d');
    }

    public function previousDay()
    {
        $this->validate();
        $this->date = (new Carbon($this->date))->subDay(1)->format('Y-m-d');
    }
}