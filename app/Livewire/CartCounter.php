<?php

namespace App\Livewire;

use App\Services\CartService;
use Livewire\Attributes\On;
use Livewire\Component;

class CartCounter extends Component
{
    #[On('cart-updated')]
    public function refreshCounter(): void
    {
        // Livewire re-renders on event
    }

    public function render(CartService $cartService)
    {
        $count = $cartService->itemCount();

        return view('livewire.cart-counter', compact('count'));
    }
}
