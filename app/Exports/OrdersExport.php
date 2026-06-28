<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class OrdersExport implements FromView
{
    public function __construct(protected array $filters = []) {}

    public function view(): View
    {
        $orders = Order::query()
            ->with(['school', 'governorate', 'area', 'payment'])
            ->whereIn('status', Order::FULFILLMENT_STATUSES)
            ->when(! empty($this->filters['school_id']), fn ($q) => $q->where('school_id', $this->filters['school_id']))
            ->when(! empty($this->filters['area_id']), fn ($q) => $q->where('area_id', $this->filters['area_id']))
            ->when(! empty($this->filters['search']), function ($q) {
                $search = $this->filters['search'];
                $q->where(function ($query) use ($search) {
                    $query->where('customer_name', 'like', "%{$search}%")
                        ->orWhere('order_number', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get();

        return view('admin.exports.orders', compact('orders'));
    }
}
