<?php

namespace App\Livewire;

use App\Services\CartService;
use App\Services\CouponService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Sepetim - AlSepete')]
class CartPage extends Component
{
    public string $couponCode = '';

    public ?string $couponMessage = null;

    public string $couponMessageType = 'info';

    public function increment(int $itemId, int $currentQuantity, CartService $cartService): void
    {
        $result = $cartService->updateItemQuantity($itemId, $currentQuantity + 1);
        if (! $result['success']) {
            $this->couponMessage = $result['message'];
            $this->couponMessageType = 'error';
        } else {
            $this->dispatch('cart-updated');
        }
    }

    public function decrement(int $itemId, int $currentQuantity, CartService $cartService): void
    {
        $result = $cartService->updateItemQuantity($itemId, $currentQuantity - 1);
        $this->dispatch('cart-updated');
    }

    public function removeItem(int $itemId, CartService $cartService): void
    {
        $cartService->removeItem($itemId);
        $this->couponMessage = __('Ürün sepetten silindi.');
        $this->couponMessageType = 'info';
        $this->dispatch('cart-updated');
    }

    public function clearCart(CartService $cartService): void
    {
        $cartService->clear();
        $this->couponMessage = __('Sepet tamamen temizlendi.');
        $this->couponMessageType = 'info';
        $this->dispatch('cart-updated');
    }

    public function applyCoupon(CartService $cartService, CouponService $couponService): void
    {
        $code = trim($this->couponCode);
        if (empty($code)) {
            $this->couponMessage = __('Lütfen bir kupon kodu giriniz.');
            $this->couponMessageType = 'error';

            return;
        }

        $coupon = $couponService->find($code);
        $totals = $cartService->calculateTotals();

        $validation = $couponService->validate($coupon, $totals['subtotal']);

        if (! $validation['valid']) {
            $this->couponMessage = $validation['message'];
            $this->couponMessageType = 'error';

            return;
        }

        $couponService->applyToSession($coupon);
        $this->couponMessage = __('Kupon kodu başarıyla uygulandı!');
        $this->couponMessageType = 'success';
        $this->couponCode = '';
        $this->dispatch('cart-updated');
    }

    public function removeCoupon(CouponService $couponService): void
    {
        $couponService->removeAppliedCoupon();
        $this->couponMessage = __('Kupon kaldırıldı.');
        $this->couponMessageType = 'info';
        $this->dispatch('cart-updated');
    }

    public function render(CartService $cartService)
    {
        $groups = $cartService->groupedBySeller();
        $totals = $cartService->calculateTotals();
        $itemCount = $cartService->itemCount();

        return view('livewire.cart-page', compact('groups', 'totals', 'itemCount'));
    }
}
