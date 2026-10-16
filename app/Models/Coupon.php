<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_total',
        'starts_at',
        'ends_at',
        'usage_limit',
        'used_count',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'min_total' => 'decimal:2',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'usage_limit' => 'integer',
            'used_count' => 'integer',
        ];
    }

    public function isValid(float $subtotal): bool
    {
        if ($this->starts_at && now()->lt($this->starts_at)) {
            return false;
        }
        if ($this->ends_at && now()->gt($this->ends_at)) {
            return false;
        }
        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return false;
        }
        if ($subtotal < (float)$this->min_total) {
            return false;
        }
        return true;
    }
}
