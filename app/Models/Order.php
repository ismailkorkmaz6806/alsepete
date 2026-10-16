<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'address',
        'subtotal',
        'discount',
        'shipping',
        'total',
        'payment_status',
    ];

    protected function casts(): array
    {
        return [
            'address' => 'array',
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'shipping' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subOrders()
    {
        return $this->hasMany(SubOrder::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
