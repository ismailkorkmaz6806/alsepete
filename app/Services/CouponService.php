<?php

namespace App\Services;

use App\Models\Coupon;
use Illuminate\Support\Facades\Session;

class CouponService
{
    public const SESSION_KEY = 'applied_coupon_code';

    /**
     * Kupon kodunu bulur (büyük/küçük harf duyarsız).
     */
    public function find(string $code): ?Coupon
    {
        $cleanCode = strtoupper(trim($code));

        return Coupon::where('code', $cleanCode)->first();
    }

    /**
     * Kuponun geçerliliğini kontrol eder ve sonuç döner.
     * Hafta 5 Kabul Kriteri: Süresi dolmuş kupon hata mesajı verir.
     *
     * @return array{valid: bool, message: string}
     */
    public function validate(?Coupon $coupon, float $subtotal): array
    {
        if (! $coupon) {
            return [
                'valid' => false,
                'message' => __('Geçersiz veya bulunamayan kupon kodu.'),
            ];
        }

        if ($coupon->starts_at && now()->lt($coupon->starts_at)) {
            return [
                'valid' => false,
                'message' => __('Bu kupon henüz aktif hale gelmemiştir.'),
            ];
        }

        if ($coupon->ends_at && now()->gt($coupon->ends_at)) {
            return [
                'valid' => false,
                'message' => __('Bu kuponun kullanım süresi dolmuştur.'),
            ];
        }

        if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
            return [
                'valid' => false,
                'message' => __('Bu kuponun toplam kullanım limiti dolmuştur.'),
            ];
        }

        if ($subtotal < (float) $coupon->min_total) {
            $formattedMin = number_format((float) $coupon->min_total, 2, ',', '.');

            return [
                'valid' => false,
                'message' => __("Bu kuponu kullanabilmek için sepet tutarınız en az {$formattedMin} TL olmalıdır."),
            ];
        }

        return [
            'valid' => true,
            'message' => __('Kupon başarıyla uygulandı!'),
        ];
    }

    /**
     * Kupon indirim tutarını hesaplar.
     * Fiyat her zaman decimal(10,2) kuralına uygun 2 basamağa yuvarlanır.
     */
    public function calculateDiscount(Coupon $coupon, float $subtotal): float
    {
        if ($subtotal <= 0) {
            return 0.00;
        }

        if ($coupon->type === 'yuzde') {
            $discount = $subtotal * ((float) $coupon->value / 100);
        } else {
            // Sabit tutar indirimi
            $discount = (float) $coupon->value;
        }

        // İndirim asla sepet tutarını aşamaz
        $discount = min($discount, $subtotal);

        return round($discount, 2);
    }

    /**
     * Kuponu oturuma kaydeder.
     */
    public function applyToSession(Coupon $coupon): void
    {
        Session::put(self::SESSION_KEY, $coupon->code);
    }

    /**
     * Oturumdaki kuponu getirir (hala geçerliyse döner, değilse oturumdan siler).
     */
    public function getAppliedCoupon(float $subtotal = 0.00): ?Coupon
    {
        $code = Session::get(self::SESSION_KEY);
        if (! $code) {
            return null;
        }

        $coupon = $this->find($code);
        if (! $coupon) {
            $this->removeAppliedCoupon();

            return null;
        }

        if ($subtotal > 0) {
            $check = $this->validate($coupon, $subtotal);
            if (! $check['valid']) {
                $this->removeAppliedCoupon();

                return null;
            }
        }

        return $coupon;
    }

    /**
     * Oturumdaki kuponu kaldırır.
     */
    public function removeAppliedCoupon(): void
    {
        Session::forget(self::SESSION_KEY);
    }
}
