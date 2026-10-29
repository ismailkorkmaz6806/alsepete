<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'seller_id',
        'category_id',
        'brand_id',
        'name',
        'slug',
        'image_url',
        'gallery',
        'description',
        'status',
    ];

    protected $casts = [
        'gallery' => 'array',
    ];

    public function getNameAttribute(?string $val): ?string
    {
        if (app()->getLocale() === 'en' && $val) {
            $trans = __($val);
            if ($trans !== $val) {
                return $trans;
            }
        }

        return $val;
    }

    public function getDescriptionAttribute(?string $val): ?string
    {
        if (app()->getLocale() === 'en' && $val) {
            $trans = __($val);
            if ($trans !== $val) {
                return $trans;
            }

            if (str_contains($val, 'resmi distribütör garantili olup')) {
                $sellerName = $this->seller?->store_name ?? 'our authorized seller';

                return "This product is covered by official distributor warranty and shipped by authorized seller {$sellerName} with AlSepete guarantee. 100% authentic with invoice. 14-day hassle-free return policy.";
            }
        }

        return $val;
    }

    public function getFeaturedImageAttribute(): string
    {
        return $this->getFirstMediaUrl('images')
            ?: ($this->image_url ?: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&auto=format&fit=crop&q=80');
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function questions()
    {
        return $this->hasMany(ProductQuestion::class);
    }

    public function answeredQuestions()
    {
        return $this->hasMany(ProductQuestion::class)->approved()->answered()->latest();
    }

    public function getMinPriceAttribute()
    {
        return $this->variants->min(fn ($v) => $v->discount_price ?? $v->price) ?? 0;
    }

    public function getMaxPriceAttribute()
    {
        return $this->variants->max('price') ?? 0;
    }

    public function getTotalStockAttribute(): int
    {
        return (int) $this->variants->sum('stock');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(300)
            ->nonQueued();

        $this->addMediaConversion('large')
            ->width(800)
            ->height(800)
            ->nonQueued();
    }
}
