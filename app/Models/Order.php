<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_RETURNED = 'returned';
    public const STATUS_PARTIALLY_RETURNED = 'partially_returned';

    protected $fillable = [
        'order_number',
        'user_id',
        'total_price',
        'status',
        'pickup_info',
        'pickup_time',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public static function generateOrderNumber(): string
    {
        $date = now()->format('Ymd');
        $count = self::whereDate('created_at', today())->count() + 1;
        return 'ORD-' . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    /**
     * True when every item has been fully returned.
     */
    public function isFullyReturned(): bool
    {
        return $this->items->every(fn ($item) => $item->returnableQuantity() <= 0);
    }

    /**
     * Recalculate and persist the order status from its items'
     * returned quantities. Leaves cancelled orders untouched.
     */
    public function syncReturnStatus(): void
    {
        if ($this->isCancelled()) {
            return;
        }

        $anyReturned = $this->items->sum('returned_quantity') > 0;

        if (! $anyReturned) {
            $this->status = self::STATUS_PENDING;
        } elseif ($this->isFullyReturned()) {
            $this->status = self::STATUS_RETURNED;
        } else {
            $this->status = self::STATUS_PARTIALLY_RETURNED;
        }

        $this->save();
    }
}
