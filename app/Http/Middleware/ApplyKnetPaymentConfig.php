<?php

namespace App\Http\Middleware;

use App\Services\KnetConfigLoader;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplyKnetPaymentConfig
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('knet/*') || $request->is('payment/knet-response') || $request->is('orders') || $request->is('payment-result')) {
            KnetConfigLoader::applyFromDatabase();
        }

        return $next($request);
    }
}
