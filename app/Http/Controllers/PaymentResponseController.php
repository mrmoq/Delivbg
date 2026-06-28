<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Services\PaymentService;
use Asciisd\Knet\KnetTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentResponseController extends Controller
{
    public function __construct(protected PaymentService $paymentService) {}

    public function show()
    {
        $trackId = session('pending_knet_trackid');

        if (! $trackId) {
            return view('payment-response', ['status' => false, 'payment' => null]);
        }

        $transaction = KnetTransaction::query()->where('trackid', $trackId)->first();

        if (! $transaction) {
            return view('payment-response', ['status' => false, 'payment' => null]);
        }

        try {
            DB::beginTransaction();
            $payment = $this->paymentService->handleResponse($transaction);
            DB::commit();

            session()->forget('pending_knet_trackid');

            return view('payment-response', [
                'status' => $payment->status,
                'payment' => $payment->load('order'),
                'paymentMethod' => PaymentMethod::query()->where('status', true)->value('name') ?? 'KNET',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::critical($e->getMessage());

            $existingPayment = \App\Models\Payment::query()
                ->where('payment_token', $trackId)
                ->with('order')
                ->first();

            session()->forget('pending_knet_trackid');

            if ($existingPayment) {
                return view('payment-response', [
                    'status' => $existingPayment->status,
                    'payment' => $existingPayment,
                    'paymentMethod' => PaymentMethod::query()->where('status', true)->value('name') ?? 'KNET',
                ]);
            }

            return view('payment-response', ['status' => false, 'payment' => null]);
        }
    }

    public function errorPage()
    {
        session()->forget('pending_knet_trackid');

        return view('payment-response', ['status' => false, 'payment' => null]);
    }
}
