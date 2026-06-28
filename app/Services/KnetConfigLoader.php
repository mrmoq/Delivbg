<?php

namespace App\Services;

use App\Models\PaymentMethod;
use Asciisd\Knet\Config\KnetConfig;
use Illuminate\Support\Facades\Schema;

class KnetConfigLoader
{
    public static function applyFromDatabase(): bool
    {
        if (! Schema::hasTable('payment_methods')) {
            return false;
        }

        $paymentMethod = PaymentMethod::query()->where('status', true)->first();

        if (! $paymentMethod) {
            return false;
        }

        if (
            blank($paymentMethod->knet_transport_id)
            || blank($paymentMethod->knet_transport_password)
            || blank($paymentMethod->knet_resource_key)
        ) {
            return false;
        }

        config()->set('knet.transport.id', $paymentMethod->knet_transport_id);
        config()->set('knet.transport.password', $paymentMethod->knet_transport_password);
        config()->set('knet.resource_key', $paymentMethod->knet_resource_key);

        $url = $paymentMethod->mode === 'live_mode'
            ? ($paymentMethod->live_url ?: config('knet.production_url'))
            : ($paymentMethod->test_url ?: config('knet.development_url'));

        config()->set('knet.development_url', $url);
        config()->set('knet.production_url', $url);

        if (app()->bound(KnetConfig::class)) {
            app()->forgetInstance(KnetConfig::class);
        }

        return true;
    }

    public static function ensureConfigured(): void
    {
        if (! static::applyFromDatabase()) {
            throw new \RuntimeException('يرجى إعداد وتفعيل بيانات KNET من لوحة التحكم → إعدادات الدفع.');
        }
    }
}
