<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class FavoriteCounter extends Component
{
    #[On('favorite-updated')]
    public function refreshCounter(): void
    {
        // Livewire re-renders on event
    }

    public function render()
    {
        $count = Auth::check() ? Auth::user()->favorites()->count() : 0;

        return view('livewire.favorite-counter', compact('count'));
    }
}
