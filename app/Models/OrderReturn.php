<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderReturn extends Model
{
    use HasFactory;

    protected $table = 'returns';

    protected $fillable = [
        'order_item_id',
        'reason',
        'status',
        'refund_amount',
    ];

    protected function casts(): array
    {
        return [
            'refund_amount' => 'decimal:2',
        ];
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}
