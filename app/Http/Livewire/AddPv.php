<?php

namespace App\Http\Livewire;

use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class AddPv extends ModalComponent
{
    public function render()
    {
        return view('livewire.add-pv');
    }
}
