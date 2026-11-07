<?php

namespace App\Services;

class InstallmentService
{
    /**
     * İyzico / Banka kart ailelerine göre taksit tablosu üretir
     */
    public function getInstallmentPlans(float $price): array
    {
        $cards = [
            [
                'name' => 'Bonus',
                'bank' => 'Garanti BBVA',
                'badge_color' => 'bg-emerald-600',
                'installments' => [2, 3, 6, 9, 12],
            ],
            [
                'name' => 'Maximum',
                'bank' => 'İş Bankası',
                'badge_color' => 'bg-indigo-700',
                'installments' => [2, 3, 6, 9, 12],
            ],
            [
                'name' => 'World',
                'bank' => 'Yapı Kredi',
                'badge_color' => 'bg-purple-700',
                'installments' => [2, 3, 6, 9, 12],
            ],
            [
                'name' => 'Axess',
                'bank' => 'Akbank',
                'badge_color' => 'bg-amber-600',
                'installments' => [2, 3, 6, 9],
            ],
            [
                'name' => 'CardFinans',
                'bank' => 'QNB',
                'badge_color' => 'bg-blue-600',
                'installments' => [2, 3, 6, 9],
            ],
            [
                'name' => 'Paraf',
                'bank' => 'Halkbank',
                'badge_color' => 'bg-teal-600',
                'installments' => [2, 3, 6],
            ],
        ];

        // Taksit vade farkı katsayıları
        $rates = [
            1 => 0.00,
            2 => 0.029,
            3 => 0.045,
            6 => 0.089,
            9 => 0.129,
            12 => 0.169,
        ];

        $plans = [];
        foreach ($cards as $card) {
            $table = [];
            // Tek çekim
            $table[] = [
                'installment' => 1,
                'monthly' => $price,
                'total' => $price,
                'is_commission_free' => true,
            ];

            foreach ($card['installments'] as $inst) {
                $rate = $rates[$inst] ?? 0.05;
                $total = round($price * (1 + $rate), 2);
                $monthly = round($total / $inst, 2);

                $table[] = [
                    'installment' => $inst,
                    'monthly' => $monthly,
                    'total' => $total,
                    'is_commission_free' => ($rate == 0),
                ];
            }

            $plans[] = [
                'name' => $card['name'],
                'bank' => $card['bank'],
                'color' => $card['badge_color'],
                'table' => $table,
            ];
        }

        return $plans;
    }
}
