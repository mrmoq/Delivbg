<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'status',
        'transaction_id',
        'transaction_payment_id',
        'transaction_payment_auth',
        'transaction_payment_ref',
        'payment_token',
        'price',
        'delivery_fees',
        'purpose',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'price' => 'decimal:3',
            'delivery_fees' => 'decimal:3',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status ? 'مدفوع' : 'فشل';
    }
}
