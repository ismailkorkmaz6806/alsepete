<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sub_order_id',
        'variant_id',
        'product_name',
        'price',
        'quantity',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'quantity' => 'integer',
        ];
    }

    public function subOrder()
    {
        return $this->belongsTo(SubOrder::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function returnRequest()
    {
        return $this->hasOne(OrderReturn::class, 'order_item_id');
    }

    public function review()
    {
        return $this->hasOne(Review::class, 'order_item_id');
    }
}
