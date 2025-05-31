<?php

namespace App\Livewire;

use Livewire\Component;

class CartCount extends Component
{
    protected $listeners = ['cartUpdated' => '$refresh'];
    
    public function render()
    {
        $count = 5; // replace with session
        
        return view('livewire.cart-count', [
            'count' => $count
        ]);
    }
}
