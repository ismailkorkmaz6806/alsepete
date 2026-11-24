<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FavoriteButton extends Component
{
    public int $productId;

    public bool $isFavorite = false;

    public string $size = 'normal'; // 'normal', 'sm', 'card'

    public function mount(int $productId, string $size = 'normal'): void
    {
        $this->productId = $productId;
        $this->size = $size;

        if (Auth::check()) {
            $this->isFavorite = Auth::user()->favorites()->where('product_id', $this->productId)->exists();
        }
    }

    public function toggle(): void
    {
        if (! Auth::check()) {
            session()->flash('auth_required', __('Favorilere eklemek için lütfen giriş yapın.'));
            $this->redirect(route('login'));

            return;
        }

        $user = Auth::user();

        if ($this->isFavorite) {
            $user->favorites()->detach($this->productId);
            $this->isFavorite = false;
        } else {
            $user->favorites()->syncWithoutDetaching([$this->productId]);
            $this->isFavorite = true;
        }

        $this->dispatch('favorite-updated');
    }

    public function render()
    {
        return view('livewire.favorite-button');
    }
}
