<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\ProductVariant;
use App\Models\Seller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CartService
{
    public function __construct(
        protected CouponService $couponService
    ) {}

    /**
     * Aktif kullanıcının veya misafirin sepetini döner.
     */
    public function current(?int $userId = null, ?string $sessionId = null): Cart
    {
        $userId = $userId ?? Auth::id();
        $sessionId = $sessionId ?? Session::getId();

        if ($userId) {
            $cart = Cart::firstOrCreate(['user_id' => $userId]);
        } else {
            $cart = Cart::firstOrCreate(['session_id' => $sessionId]);
        }

        return $cart;
    }

    /**
     * Sepete ürün varyantı ekler.
     * Hafta 5 Kabul Kriteri: Stoktan fazla ürün sepete eklenemez.
     *
     * @return array{success: bool, message: string}
     */
    public function addItem(int $variantId, int $quantity = 1, ?Cart $cart = null): array
    {
        if ($quantity < 1) {
            return [
                'success' => false,
                'message' => __('Geçersiz ürün adedi.'),
            ];
        }

        $cart = $cart ?? $this->current();

        $variant = ProductVariant::with(['product.seller'])->find($variantId);
        if (! $variant || ! $variant->product || $variant->product->status !== 'aktif') {
            return [
                'success' => false,
                'message' => __('Bu ürün şu anda satışta değil veya bulunamadı.'),
            ];
        }

        if ($variant->stock < 1) {
            return [
                'success' => false,
                'message' => __('Üzgünüz, bu ürünün stoğu tükenmiştir.'),
            ];
        }

        $existingItem = CartItem::where('cart_id', $cart->id)
            ->where('variant_id', $variantId)
            ->first();

        $currentQty = $existingItem ? $existingItem->quantity : 0;
        $targetQty = $currentQty + $quantity;

        if ($targetQty > $variant->stock) {
            $allowedAdd = max(0, $variant->stock - $currentQty);
            if ($allowedAdd === 0) {
                return [
                    'success' => false,
                    'message' => __("Sepetinizde zaten bu üründen {$currentQty} adet var. Mevcut stok sınırı {$variant->stock} adettir."),
                ];
            }

            return [
                'success' => false,
                'message' => __("Yetersiz stok! En fazla {$allowedAdd} adet daha ekleyebilirsiniz (Mevcut stok: {$variant->stock})."),
            ];
        }

        if ($existingItem) {
            $existingItem->update(['quantity' => $targetQty]);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'variant_id' => $variantId,
                'quantity' => $targetQty,
            ]);
        }

        return [
            'success' => true,
            'message' => __('Ürün başarıyla sepete eklendi!'),
        ];
    }

    /**
     * Sepetteki ürün adedini günceller.
     *
     * @return array{success: bool, message: string}
     */
    public function updateItemQuantity(int $itemId, int $quantity, ?Cart $cart = null): array
    {
        $cart = $cart ?? $this->current();

        $item = CartItem::where('cart_id', $cart->id)->where('id', $itemId)->with('variant')->first();
        if (! $item) {
            return [
                'success' => false,
                'message' => __('Sepet öğesi bulunamadı.'),
            ];
        }

        if ($quantity <= 0) {
            $item->delete();

            return [
                'success' => true,
                'message' => __('Ürün sepetten çıkarıldı.'),
            ];
        }

        if ($quantity > $item->variant->stock) {
            return [
                'success' => false,
                'message' => __("Mevcut stok sınırı {$item->variant->stock} adettir."),
            ];
        }

        $item->update(['quantity' => $quantity]);

        return [
            'success' => true,
            'message' => __('Adet güncellendi.'),
        ];
    }

    /**
     * Ürünü sepetten siler.
     */
    public function removeItem(int $itemId, ?Cart $cart = null): void
    {
        $cart = $cart ?? $this->current();

        CartItem::where('cart_id', $cart->id)->where('id', $itemId)->delete();
    }

    /**
     * Sepeti tamamen boşaltır.
     */
    public function clear(?Cart $cart = null): void
    {
        $cart = $cart ?? $this->current();

        CartItem::where('cart_id', $cart->id)->delete();
        $this->couponService->removeAppliedCoupon();
    }

    /**
     * Sepetteki toplam ürün sayısı (tüm adetler toplamı).
     */
    public function itemCount(?Cart $cart = null): int
    {
        $cart = $cart ?? $this->current();

        return (int) CartItem::where('cart_id', $cart->id)->sum('quantity');
    }

    /**
     * Sepetteki ürünleri satıcı bazında gruplar ve kargo durumlarını hesaplar.
     * Hafta 5 Şartnamesi: Sepette ürünler satıcıya göre gruplanır, 300 TL üstü kargo ücretsizdir.
     *
     * @return array<int, array{
     *   seller: Seller,
     *   items: Collection,
     *   subtotal: float,
     *   shipping_fee: float,
     *   free_shipping_threshold: float,
     *   free_shipping_remaining: float,
     *   is_free_shipping: bool,
     *   total: float
     * }>
     */
    public function groupedBySeller(?Cart $cart = null): array
    {
        $cart = $cart ?? $this->current();

        $items = CartItem::where('cart_id', $cart->id)
            ->with(['variant.product.seller', 'variant.product.media'])
            ->get();

        $feePerSeller = (float) config('alsepete.shipping.fee_per_seller', 49.90);
        $threshold = (float) config('alsepete.shipping.free_shipping_threshold', 300.00);

        $groups = [];

        foreach ($items as $item) {
            $seller = $item->variant?->product?->seller;
            if (! $seller) {
                continue;
            }

            $sellerId = $seller->id;
            if (! isset($groups[$sellerId])) {
                $groups[$sellerId] = [
                    'seller' => $seller,
                    'items' => collect(),
                    'subtotal' => 0.00,
                    'shipping_fee' => 0.00,
                    'free_shipping_threshold' => $threshold,
                    'free_shipping_remaining' => $threshold,
                    'is_free_shipping' => false,
                    'total' => 0.00,
                ];
            }

            $itemPrice = (float) $item->variant->final_price;
            $itemTotal = round($itemPrice * $item->quantity, 2);

            $groups[$sellerId]['items']->push($item);
            $groups[$sellerId]['subtotal'] = round($groups[$sellerId]['subtotal'] + $itemTotal, 2);
        }

        // Kargo ücreti ve grup toplamı hesabı
        foreach ($groups as $sellerId => &$group) {
            $isFree = $group['subtotal'] >= $threshold;
            $shippingFee = $isFree ? 0.00 : $feePerSeller;
            $remaining = max(0.00, round($threshold - $group['subtotal'], 2));

            $group['shipping_fee'] = $shippingFee;
            $group['is_free_shipping'] = $isFree;
            $group['free_shipping_remaining'] = $remaining;
            $group['total'] = round($group['subtotal'] + $shippingFee, 2);
        }
        unset($group);

        return $groups;
    }

    /**
     * Sepet genel toplamlarını hesaplar (ara toplam, kargo, indirim, genel toplam).
     *
     * @return array{
     *   subtotal: float,
     *   total_shipping: float,
     *   discount: float,
     *   grand_total: float,
     *   seller_count: int,
     *   coupon: ?Coupon
     * }
     */
    public function calculateTotals(?Cart $cart = null, ?Coupon $coupon = null): array
    {
        $groups = $this->groupedBySeller($cart);

        $subtotal = 0.00;
        $totalShipping = 0.00;

        foreach ($groups as $group) {
            $subtotal += $group['subtotal'];
            $totalShipping += $group['shipping_fee'];
        }

        $subtotal = round($subtotal, 2);
        $totalShipping = round($totalShipping, 2);

        // Kupon indirimi
        $appliedCoupon = $coupon ?? $this->couponService->getAppliedCoupon($subtotal);
        $discount = 0.00;

        if ($appliedCoupon) {
            $validation = $this->couponService->validate($appliedCoupon, $subtotal);
            if ($validation['valid']) {
                $discount = $this->couponService->calculateDiscount($appliedCoupon, $subtotal);
            }
        }

        $grandTotal = max(0.00, round(($subtotal - $discount) + $totalShipping, 2));

        return [
            'subtotal' => $subtotal,
            'total_shipping' => $totalShipping,
            'discount' => $discount,
            'grand_total' => $grandTotal,
            'seller_count' => count($groups),
            'coupon' => $appliedCoupon,
        ];
    }

    /**
     * Misafir kullanıcının sepetini oturum açınca kullanıcı sepetine aktarır/birleştirir.
     * Hafta 5 Şartnamesi: misafir sepeti session'da, giriş yapınca kullanıcı sepetine birleşir.
     */
    public function mergeGuestCart(int $userId, string $guestSessionId): void
    {
        $guestCart = Cart::where('session_id', $guestSessionId)->first();
        if (! $guestCart || $guestCart->items()->count() === 0) {
            return;
        }

        $userCart = Cart::firstOrCreate(['user_id' => $userId]);

        DB::transaction(function () use ($guestCart, $userCart) {
            $guestItems = $guestCart->items()->with('variant')->get();

            foreach ($guestItems as $guestItem) {
                if (! $guestItem->variant || $guestItem->variant->stock < 1) {
                    continue;
                }

                $existingUserItem = CartItem::where('cart_id', $userCart->id)
                    ->where('variant_id', $guestItem->variant_id)
                    ->first();

                if ($existingUserItem) {
                    // İki sepetteki adetleri topla ama mevcut stoğu aşma
                    $combinedQty = min(
                        $existingUserItem->quantity + $guestItem->quantity,
                        $guestItem->variant->stock
                    );
                    $existingUserItem->update(['quantity' => $combinedQty]);
                } else {
                    $newQty = min($guestItem->quantity, $guestItem->variant->stock);
                    CartItem::create([
                        'cart_id' => $userCart->id,
                        'variant_id' => $guestItem->variant_id,
                        'quantity' => $newQty,
                    ]);
                }
            }

            // Misafir sepetini temizle ve sil
            $guestCart->items()->delete();
            $guestCart->delete();
        });
    }
}
