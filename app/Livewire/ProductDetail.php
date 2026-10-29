<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductVariant;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ProductDetail extends Component
{
    public Product $product;

    public int $selectedVariantId = 0;

    public string $selectedColor = '';

    public string $selectedSize = '';

    public int $quantity = 1;

    public string $activeImage = '';

    public bool $addedToCart = false;

    public function mount(Product $product): void
    {
        $this->product = $product->loadMissing(['seller', 'variants', 'media', 'brand', 'category']);

        $defaultVariant = $this->product->variants->first();
        if ($defaultVariant) {
            $this->selectedVariantId = $defaultVariant->id;
            $this->selectedColor = (string) $defaultVariant->color;
            $this->selectedSize = (string) $defaultVariant->size;
        }

        // Ana görsel
        $this->activeImage = $this->product->featured_image;
    }

    #[Computed]
    public function selectedVariant(): ?ProductVariant
    {
        return $this->product->variants->firstWhere('id', $this->selectedVariantId)
            ?? $this->product->variants->first();
    }

    #[Computed]
    public function availableColors(): array
    {
        return $this->product->variants->pluck('color')->filter()->unique()->values()->all();
    }

    #[Computed]
    public function availableSizes(): array
    {
        return $this->product->variants->pluck('size')->filter()->unique()->values()->all();
    }

    public function selectColor(string $color): void
    {
        $this->selectedColor = $color;

        // Seçilen renge ve mevcut bedene uyan varyantı bul
        $matched = $this->product->variants->first(fn ($v) => $v->color === $color && $v->size === $this->selectedSize)
            ?? $this->product->variants->firstWhere('color', $color);

        if ($matched) {
            $this->selectedVariantId = $matched->id;
            $this->selectedSize = (string) $matched->size;
        }

        $this->quantity = 1;
        $this->addedToCart = false;
    }

    public function selectSize(string $size): void
    {
        $this->selectedSize = $size;

        // Seçilen bedene ve mevcut renge uyan varyantı bul
        $matched = $this->product->variants->first(fn ($v) => $v->size === $size && $v->color === $this->selectedColor)
            ?? $this->product->variants->firstWhere('size', $size);

        if ($matched) {
            $this->selectedVariantId = $matched->id;
            $this->selectedColor = (string) $matched->color;
        }

        $this->quantity = 1;
        $this->addedToCart = false;
    }

    public function incrementQuantity(): void
    {
        $maxStock = $this->selectedVariant?->stock ?? 1;
        if ($this->quantity < $maxStock) {
            $this->quantity++;
        }
    }

    public function decrementQuantity(): void
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function setActiveImage(string $url): void
    {
        $this->activeImage = $url;
    }

    public function addToCart(): void
    {
        $variant = $this->selectedVariant;
        if (! $variant || $variant->stock < 1) {
            return;
        }

        $this->addedToCart = true;
        session()->flash('cart_success', __('Ürün başarıyla sepete eklendi!'));
    }

    public function render()
    {
        return view('livewire.product-detail');
    }
}
