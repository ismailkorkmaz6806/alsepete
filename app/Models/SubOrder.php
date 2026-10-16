<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'seller_id',
        'status',
        'cargo_company',
        'tracking_number',
        'commission_amount',
    ];

    protected function casts(): array
    {
        return [
            'commission_amount' => 'decimal:2',
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
