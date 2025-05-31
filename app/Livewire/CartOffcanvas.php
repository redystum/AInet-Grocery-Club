<?php

namespace App\Livewire;

use Livewire\Component;

class CartOffcanvas extends Component
{
    public $open = false;
    public $cartItems = [];
    public $totalAmount = 0;

    protected $listeners = ['openCart', 'closeCart', 'updateCartItem', 'removeCartItem'];

    public function mount()
    {
        // replace with session
        $this->cartItems = [
            [
                'id' => 1, 
                'name' => 'Organic Avocado', 
                'price' => 3.99, 
                'quantity' => 1, 
                'image' => 'https://via.placeholder.com/80',
                'description' => '250g each'
            ],
            [
                'id' => 2, 
                'name' => 'Fresh Milk', 
                'price' => 4.50, 
                'quantity' => 2, 
                'image' => 'https://via.placeholder.com/80',
                'description' => '1 Liter'
            ],
        ];
        
        $this->calculateTotal();
    }

    public function openCart()
    {
        $this->open = true;
    }

    public function closeCart()
    {
        $this->open = false;
    }

    public function updateCartItem($itemId, $quantity)
    {
        foreach ($this->cartItems as $index => $item) {
            if ($item['id'] == $itemId) {
                $this->cartItems[$index]['quantity'] = max(1, $quantity);
                break;
            }
        }
        
        $this->calculateTotal();
    }

    public function incrementQuantity($itemId)
    {
        foreach ($this->cartItems as $index => $item) {
            if ($item['id'] == $itemId) {
                $this->cartItems[$index]['quantity']++;
                break;
            }
        }
        
        $this->calculateTotal();
    }

    public function decrementQuantity($itemId)
    {
        foreach ($this->cartItems as $index => $item) {
            if ($item['id'] == $itemId) {
                if ($this->cartItems[$index]['quantity'] > 1) {
                    $this->cartItems[$index]['quantity']--;
                }
                break;
            }
        }
        
        $this->calculateTotal();
    }

    public function removeCartItem($itemId)
    {
        $this->cartItems = array_filter($this->cartItems, function($item) use ($itemId) {
            return $item['id'] != $itemId;
        });
        
        $this->calculateTotal();
    }

    private function calculateTotal()
    {
        $this->totalAmount = array_reduce($this->cartItems, function($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);
    }

    public function getCartCount()
    {
        return array_sum(array_column($this->cartItems, 'quantity'));
    }

    public function render()
    {
        return view('livewire.cart-offcanvas');
    }
}
