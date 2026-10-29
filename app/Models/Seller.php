<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'store_name',
        'slug',
        'tax_number',
        'iban',
        'status',
        'commission_rate',
    ];

    protected function casts(): array
    {
        return [
            'commission_rate' => 'decimal:2',
        ];
    }

    public function getStoreNameAttribute(?string $val): ?string
    {
        if (app()->getLocale() === 'en' && $val) {
            $trans = __($val);
            if ($trans !== $val) {
                return $trans;
            }
        }

        return $val;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function subOrders()
    {
        return $this->hasMany(SubOrder::class);
    }

    public function payouts()
    {
        return $this->hasMany(SellerPayout::class);
    }

    public function questions()
    {
        return $this->hasMany(ProductQuestion::class);
    }
}
