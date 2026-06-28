<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Setting;
use Asciisd\Knet\KnetTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function checkout(\App\Models\Order $order): string
    {
        KnetConfigLoader::ensureConfigured();

        $user = Auth::user();
        $amount = (float) $order->total_amount;

        Log::channel('single')->info('KNET checkout', [
            'user_id' => $user->id,
            'order_id' => $order->id,
            'amount' => $amount,
        ]);

        $transaction = $user->pay($amount, [
            'udf1' => 'order',
            'udf2' => (string) $order->id,
            'udf4' => $order->order_number,
        ]);

        $order->update(['knet_track_id' => $transaction->trackid]);

        session(['pending_knet_trackid' => $transaction->trackid]);

        return $transaction->url;
    }

    public function handleResponse(KnetTransaction $transaction): Payment
    {
        $existing = Payment::query()->where('payment_token', $transaction->trackid)->first();

        if ($existing) {
            $existing->exists_before = true;

            return $existing;
        }

        $orderId = (int) $transaction->udf2;
        $order = \App\Models\Order::query()->findOrFail($orderId);

        $isPaid = (bool) $transaction->paid || $transaction->isCaptured();

        $payment = Payment::query()->create([
            'order_id' => $order->id,
            'user_id' => $transaction->user_id,
            'status' => $isPaid,
            'transaction_id' => $transaction->tranid,
            'transaction_payment_id' => $transaction->paymentid,
            'transaction_payment_auth' => $transaction->auth,
            'transaction_payment_ref' => $transaction->ref,
            'payment_token' => $transaction->trackid,
            'price' => $transaction->amt,
            'delivery_fees' => $order->delivery_fee,
            'purpose' => 'order',
        ]);

        $order->update([
            'status' => $isPaid ? 'paid' : 'failed',
            'knet_track_id' => $transaction->trackid,
        ]);

        return $payment;
    }

    public static function defaultDeliveryFee(): float
    {
        return (float) Setting::getValue('default_delivery_fee', 3);
    }
}
