<?php

namespace App\Http\Controllers\Knet;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class HandleController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        return redirect()->route('payment.result');
    }
}
