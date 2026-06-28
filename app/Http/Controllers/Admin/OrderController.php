<?php

namespace App\Http\Controllers\Admin;

use App\Exports\OrdersExport;
use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\School;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Order::query()
            ->with(['school', 'governorate', 'area', 'payment'])
            ->whereIn('status', Order::FULFILLMENT_STATUSES)
            ->when($request->filled('school_id'), fn ($q) => $q->where('school_id', $request->school_id))
            ->when($request->filled('area_id'), fn ($q) => $q->where('area_id', $request->area_id))
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($query) use ($search) {
                    $query->where('customer_name', 'like', "%{$search}%")
                        ->orWhere('order_number', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $schools = School::query()->where('is_active', true)->orderBy('name_ar')->get(['id', 'name_ar']);
        $areas = Area::query()->where('is_active', true)->orderBy('name_ar')->get(['id', 'name_ar']);

        return view('admin.orders.index', compact('orders', 'schools', 'areas'));
    }

    public function show(Order $order): View
    {
        $order->load(['school', 'governorate', 'area', 'payment']);

        $paymentMethod = PaymentMethod::query()->where('status', true)->value('name') ?? 'KNET';

        return view('admin.orders.show', compact('order', 'paymentMethod'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:'.implode(',', Order::FULFILLMENT_STATUSES)],
        ]);

        abort_unless(in_array($order->status, Order::FULFILLMENT_STATUSES, true), 403);

        $order->update(['status' => $validated['status']]);

        return back()->with('success', 'تم تحديث حالة الطلب.');
    }

    public function bulkUpdateStatus(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:'.implode(',', Order::FULFILLMENT_STATUSES)],
            'order_ids' => ['required', 'array', 'min:1'],
            'order_ids.*' => ['integer', 'exists:orders,id'],
        ], [
            'order_ids.required' => 'يرجى تحديد طلب واحد على الأقل.',
            'order_ids.min' => 'يرجى تحديد طلب واحد على الأقل.',
        ]);

        $updated = Order::query()
            ->whereIn('id', $validated['order_ids'])
            ->whereIn('status', Order::FULFILLMENT_STATUSES)
            ->update(['status' => $validated['status']]);

        return back()->with('success', "تم تحديث حالة {$updated} طلب.");
    }

    public function bulkPrint(Request $request): View
    {
        $validated = $request->validate([
            'order_ids' => ['required', 'array', 'min:1'],
            'order_ids.*' => ['integer', 'exists:orders,id'],
        ], [
            'order_ids.required' => 'يرجى تحديد طلب واحد على الأقل.',
            'order_ids.min' => 'يرجى تحديد طلب واحد على الأقل.',
        ]);

        $orders = Order::query()
            ->with(['school', 'governorate', 'area', 'payment'])
            ->whereIn('id', $validated['order_ids'])
            ->whereIn('status', Order::FULFILLMENT_STATUSES)
            ->latest()
            ->get();

        abort_if($orders->isEmpty(), 404);

        $paymentMethod = PaymentMethod::query()->where('status', true)->value('name') ?? 'KNET';

        return view('admin.orders.bulk-print', compact('orders', 'paymentMethod'));
    }

    public function export(Request $request): BinaryFileResponse
    {
        return Excel::download(
            new OrdersExport($request->all()),
            'orders-'.now()->format('Y-m-d').'.xlsx'
        );
    }
}
