<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerPayout extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'period',
        'gross',
        'commission',
        'net',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'gross' => 'decimal:2',
            'commission' => 'decimal:2',
            'net' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }
}
