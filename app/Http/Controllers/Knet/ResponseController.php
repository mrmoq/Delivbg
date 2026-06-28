<?php

namespace App\Http\Controllers\Knet;

use App\Http\Controllers\Controller;
use App\Services\KnetConfigLoader;
use Asciisd\Knet\Services\KnetPaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ResponseController extends Controller
{
    public function __invoke(Request $request, KnetPaymentService $knetPaymentService): RedirectResponse
    {
        KnetConfigLoader::applyFromDatabase();

        try {
            $payload = $request->attributes->get('knet_payload');

            $transaction = $knetPaymentService->handlePaymentResponse($payload);

            session(['pending_knet_trackid' => $transaction->trackid]);

            return redirect()->route('payment.result');
        } catch (\Throwable $e) {
            Log::error('KNET response handling failed', [
                'message' => $e->getMessage(),
            ]);

            return redirect()->route('payment.error');
        }
    }
}
