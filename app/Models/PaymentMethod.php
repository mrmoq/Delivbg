<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name',
        'knet_transport_id',
        'knet_transport_password',
        'knet_resource_key',
        'test_url',
        'live_url',
        'mode',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status ? 'مفعّل' : 'غير مفعّل';
    }
}
