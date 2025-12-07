<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';
    public const STATUS_SHIPPED = 'shipped';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'user_id',
        'code',
        'subtotal',
        'shipping_fee',
        'total',
        'status',
        'payment_method',
        'shipping_method',
        'tracking_number',
        'payment_status',
        'customer_name',
        'customer_email',
        'customer_phone',
        'address_line1',
        'address_line2',
        'city',
        'postal_code',
        'notes',
        'placed_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'total' => 'decimal:2',
        'placed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            if (! $order->code) {
                $order->code = 'ORD-' . strtoupper(Str::random(8));
            }
            if (! $order->status) {
                $order->status = self::STATUS_PENDING;
            }
            if (! $order->payment_status) {
                $order->payment_status = 'unpaid';
            }
        });

        static::created(function (Order $order) {
            $order->statusHistories()->create([
                'status' => $order->status,
                'user_id' => $order->user_id,
                'note' => 'Pesanan dibuat',
            ]);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeForCustomer($query, User $user)
    {
        return $query->where('user_id', $user->id);
    }

    public function statusHistories()
    {
        return $this->hasMany(OrderStatusHistory::class)->orderBy('created_at');
    }
}
