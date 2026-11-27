<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'returned_quantity',
        'price',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Includes products since removed from QuickBooks, so order history and
     * order emails keep showing the real part name.
     */
    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    /**
     * Quantity still eligible to be returned.
     */
    public function returnableQuantity(): int
    {
        return $this->quantity - $this->returned_quantity;
    }
}
