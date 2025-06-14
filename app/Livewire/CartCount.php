<?php

namespace App\Livewire;

use Livewire\Component;

class CartCount extends Component
{
    protected $listeners = ['cartUpdated' => '$refresh'];
    
    public function render()
    {
        $count = session('guest_cart', [])
            ? count(session('guest_cart'))
            : (auth()->check() ? count(auth()->user()->custom['card'] ?? []) : 0);
        
        return view('livewire.cart-count', [
            'count' => $count
        ]);
    }
}
