<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'customer_email',
        'customer_phone',
        'total_price',
        'status'
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
    ];

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
