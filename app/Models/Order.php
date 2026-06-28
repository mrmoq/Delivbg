<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    public const FULFILLMENT_STATUSES = ['paid', 'preparing', 'delivered'];

    public const STATUS_LABELS = [
        'pending' => 'قيد الانتظار',
        'paid' => 'مدفوع',
        'preparing' => 'جاري التجهيز',
        'delivered' => 'تم التوصيل',
        'failed' => 'فشل',
        'cancelled' => 'ملغي',
    ];
    protected $fillable = [
        'order_number',
        'school_id',
        'customer_name',
        'customer_phone',
        'governorate_id',
        'area_id',
        'block',
        'street',
        'building',
        'floor',
        'avenue',
        'delivery_fee',
        'total_amount',
        'status',
        'knet_track_id',
    ];

    protected function casts(): array
    {
        return [
            'delivery_fee' => 'decimal:3',
            'total_amount' => 'decimal:3',
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function governorate(): BelongsTo
    {
        return $this->belongsTo(Governorate::class);
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->governorate?->name_ar,
            $this->area?->name_ar,
            $this->block ? 'قطعة '.$this->block : null,
            $this->street ? 'شارع '.$this->street : null,
            $this->avenue ? 'جادة '.$this->avenue : null,
            $this->building ? 'بناية '.$this->building : null,
            $this->floor ? 'دور '.$this->floor : null,
        ]);

        return implode(' — ', $parts);
    }

    public function getFormattedPhoneAttribute(): ?string
    {
        if (! $this->customer_phone) {
            return null;
        }

        if (str_starts_with($this->customer_phone, '+965')) {
            return $this->customer_phone;
        }

        if (preg_match('/^\d{8}$/', $this->customer_phone)) {
            return '+965'.$this->customer_phone;
        }

        return $this->customer_phone;
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? 'قيد الانتظار';
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return $this->status_color_class;
    }

    public function getStatusColorClassAttribute(): string
    {
        return match ($this->status) {
            'paid' => 'status-paid',
            'preparing' => 'status-preparing',
            'delivered' => 'status-delivered',
            'failed' => 'status-failed',
            'cancelled' => 'status-cancelled',
            default => 'status-default',
        };
    }

    public static function statusColorClass(string $status): string
    {
        return match ($status) {
            'paid' => 'status-paid',
            'preparing' => 'status-preparing',
            'delivered' => 'status-delivered',
            default => 'status-default',
        };
    }

    public static function generateOrderNumber(): string
    {
        do {
            $number = 'ORD-'.now()->format('ymd').'-'.strtoupper(substr(uniqid(), -5));
        } while (static::query()->where('order_number', $number)->exists());

        return $number;
    }
}
