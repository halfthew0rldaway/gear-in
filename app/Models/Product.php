<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'summary',
        'description',
        'specifications',
        'price',
        'stock',
        'image_path',
        'is_featured',
        'is_active',
        'discount_percentage',
        'discount_starts_at',
        'discount_expires_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'discount_starts_at' => 'datetime',
        'discount_expires_at' => 'datetime',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'specifications' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            if (! $product->slug) {
                $product->slug = Str::slug($product->name);
            }
            if (! $product->sku) {
                $product->sku = strtoupper(Str::random(8));
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    public function getAverageRatingAttribute()
    {
        return $this->approvedReviews()->avg('rating') ?: 0;
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class)->where('is_active', true);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Check if product has active discount
     */
    public function hasActiveDiscount(): bool
    {
        if ($this->discount_percentage <= 0) {
            return false;
        }

        $now = now();
        if ($this->discount_starts_at && $now < $this->discount_starts_at) {
            return false;
        }
        if ($this->discount_expires_at && $now > $this->discount_expires_at) {
            return false;
        }

        return true;
    }

    /**
     * Get final price after discount
     */
    public function getFinalPriceAttribute(): float
    {
        if (!$this->hasActiveDiscount()) {
            return (float) $this->price;
        }

        $discount = $this->price * ($this->discount_percentage / 100);
        return (float) ($this->price - $discount);
    }

    /**
     * Get formatted final price
     */
    public function getFormattedFinalPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->final_price, 0, ',', '.');
    }
}
