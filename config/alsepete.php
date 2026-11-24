<?php

return [
    /*
     * Çok Satıcılı Kargo Ücretlendirme Kuralları (Hafta 5)
     */
    'shipping' => [
        // Satıcı başına standart kargo ücreti (TL)
        'fee_per_seller' => 49.90,

        // Satıcı bazında ücretsiz kargo eşik tutarı (TL) - PDF: "ör. 300 TL üstü ücretsiz"
        'free_shipping_threshold' => 300.00,
    ],
];
