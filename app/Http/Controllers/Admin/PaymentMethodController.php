<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentMethodController extends Controller
{
    public function index(): View
    {
        $paymentMethods = PaymentMethod::query()->latest()->paginate(10);

        return view('admin.payment-methods.index', compact('paymentMethods'));
    }

    public function edit(PaymentMethod $paymentMethod): View
    {
        return view('admin.payment-methods.edit', compact('paymentMethod'));
    }

    public function update(Request $request, PaymentMethod $paymentMethod): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'knet_transport_id' => ['nullable', 'string', 'max:255'],
            'knet_transport_password' => ['nullable', 'string', 'max:255'],
            'knet_resource_key' => ['nullable', 'string', 'size:16'],
            'test_url' => ['nullable', 'url', 'max:500'],
            'live_url' => ['nullable', 'url', 'max:500'],
            'mode' => ['required', 'in:test_mode,live_mode'],
            'status' => ['nullable', 'boolean'],
        ], [
            'knet_resource_key.size' => 'Terminal Resource Key يجب أن يكون 16 حرفاً بالضبط.',
        ]);

        $validated['status'] = $request->boolean('status');

        if ($validated['status']) {
            PaymentMethod::query()->where('id', '!=', $paymentMethod->id)->update(['status' => false]);
        }

        $paymentMethod->update($validated);

        return redirect()->route('admin.payment-methods.index')->with('success', 'تم تحديث إعدادات الدفع.');
    }
}
