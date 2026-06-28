<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Governorate;
use App\Models\Order;
use App\Models\School;
use App\Models\Setting;
use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $schools = School::query()->where('is_active', true)->orderBy('name_ar')->get();
        $governorates = Governorate::query()->where('is_active', true)->orderBy('name_ar')->get();
        $defaultFee = PaymentService::defaultDeliveryFee();

        return view('home', compact('schools', 'governorates', 'defaultFee'));
    }

    public function areas(Governorate $governorate): JsonResponse
    {
        $areas = $governorate->areas()
            ->where('is_active', true)
            ->orderBy('name_ar')
            ->get(['id', 'name_ar', 'name_en', 'delivery_fee']);

        return response()->json($areas);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'school_id' => ['required', 'exists:schools,id'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'digits:8'],
            'governorate_id' => ['required', 'exists:governorates,id'],
            'area_id' => ['required', 'exists:areas,id'],
            'block' => ['nullable', 'string', 'max:100'],
            'street' => ['nullable', 'string', 'max:255'],
            'building' => ['nullable', 'string', 'max:100'],
            'floor' => ['nullable', 'string', 'max:50'],
            'avenue' => ['nullable', 'string', 'max:100'],
        ]);

        $area = Area::query()->findOrFail($validated['area_id']);

        if ((int) $area->governorate_id !== (int) $validated['governorate_id']) {
            return back()->withErrors(['area_id' => 'المنطقة لا تتبع المحافظة المختارة.'])->withInput();
        }

        $deliveryFee = (float) ($area->delivery_fee ?: PaymentService::defaultDeliveryFee());

        try {
            DB::beginTransaction();

            $order = Order::query()->create([
                'order_number' => Order::generateOrderNumber(),
                'school_id' => $validated['school_id'],
                'customer_name' => $validated['customer_name'],
                'customer_phone' => '+965'.$validated['customer_phone'],
                'governorate_id' => $validated['governorate_id'],
                'area_id' => $validated['area_id'],
                'block' => $validated['block'] ?? null,
                'street' => $validated['street'] ?? null,
                'building' => $validated['building'] ?? null,
                'floor' => $validated['floor'] ?? null,
                'avenue' => $validated['avenue'] ?? null,
                'delivery_fee' => $deliveryFee,
                'total_amount' => $deliveryFee,
                'status' => 'pending',
            ]);

            $guestUserId = Setting::getValue('knet_guest_user_id');
            $guestUser = User::query()->find($guestUserId);

            if (! $guestUser) {
                throw new \RuntimeException('لم يتم إعداد مستخدم الدفع. راجع إعدادات النظام.');
            }

            Auth::login($guestUser);

            $paymentUrl = app(PaymentService::class)->checkout($order);

            DB::commit();

            return redirect()->away($paymentUrl);
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['payment' => $e->getMessage()])->withInput();
        }
    }
}
