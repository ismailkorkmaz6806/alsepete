<?php

namespace App\Livewire;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Favorilerim - AlSepete')]
class FavoritesPage extends Component
{
    use WithPagination;

    public ?string $message = null;

    public function remove(int $productId): void
    {
        Auth::user()->favorites()->detach($productId);
        $this->message = __('Ürün favorilerinizden kaldırıldı.');
        $this->dispatch('favorite-updated');
    }

    public function addToCart(int $productId, CartService $cartService): void
    {
        $product = Product::with('variants')->find($productId);
        if (! $product) {
            return;
        }

        $variant = $product->variants->firstWhere('stock', '>', 0) ?? $product->variants->first();
        if (! $variant) {
            $this->message = __('Bu ürün için uygun stoklu seçenek bulunamadı.');

            return;
        }

        $result = $cartService->addItem($variant->id, 1);
        if ($result['success']) {
            $this->message = __('Ürün sepete eklendi!');
            $this->dispatch('cart-updated');
        } else {
            $this->message = $result['message'];
        }
    }

    public function render()
    {
        $favorites = Auth::user()
            ->favorites()
            ->with(['seller', 'variants', 'media'])
            ->latest('favorites.created_at')
            ->paginate(12);

        return view('livewire.favorites-page', compact('favorites'));
    }
}
