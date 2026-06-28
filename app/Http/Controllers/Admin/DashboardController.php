<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Order;
use App\Models\Payment;
use App\Models\School;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $ordersTotal = Order::query()->count();

        $orderStatusCounts = [];
        foreach (array_keys(Order::STATUS_LABELS) as $status) {
            $orderStatusCounts[$status] = Order::query()->where('status', $status)->count();
        }

        $paymentsTotal = Payment::query()->count();
        $paymentsSuccess = Payment::query()->where('status', true)->count();
        $paymentsFailed = Payment::query()->where('status', false)->count();

        $stats = [
            'orders_total' => $ordersTotal,
            'revenue' => Payment::query()->where('status', true)->sum('price'),
            'order_status_counts' => $orderStatusCounts,
            'order_status_labels' => Order::STATUS_LABELS,
            'payments_total' => $paymentsTotal,
            'payments_success' => $paymentsSuccess,
            'payments_failed' => $paymentsFailed,
        ];

        $topAreas = Area::query()
            ->whereHas('orders')
            ->withCount('orders')
            ->orderByDesc('orders_count')
            ->limit(10)
            ->get(['id', 'name_ar']);

        $topSchools = School::query()
            ->whereHas('orders')
            ->withCount('orders')
            ->orderByDesc('orders_count')
            ->limit(10)
            ->get(['id', 'name_ar']);

        $orderStatusTrend = $this->buildOrderStatusTrend();

        $dashboardStats = [
            'ordersTotal' => $ordersTotal,
            'orderStatusCounts' => $orderStatusCounts,
            'orderStatusLabels' => Order::STATUS_LABELS,
            'orderStatusTrend' => $orderStatusTrend,
            'paymentsTotal' => $paymentsTotal,
            'paymentsSuccess' => $paymentsSuccess,
            'paymentsFailed' => $paymentsFailed,
            'topAreas' => $topAreas->map(fn ($a) => ['name' => $a->name_ar, 'count' => $a->orders_count])->values(),
            'topSchools' => $topSchools->map(fn ($s) => ['name' => $s->name_ar, 'count' => $s->orders_count])->values(),
            'emptyText' => 'لا توجد بيانات',
        ];

        return view('admin.dashboard', compact('stats', 'topAreas', 'topSchools', 'dashboardStats'));
    }

    /**
     * @return array{labels: list<string>, series: list<array{name: string, status: string, data: list<int>}>}
     */
    private function buildOrderStatusTrend(int $days = 30): array
    {
        $startDate = now()->subDays($days - 1)->startOfDay();
        $dateKeys = [];
        $labels = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $dateKeys[] = $date->format('Y-m-d');
            $labels[] = $date->format('d/m');
        }

        $rows = Order::query()
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as order_date, status, COUNT(*) as total')
            ->groupByRaw('DATE(created_at), status')
            ->get();

        $countsByDate = $rows->groupBy('order_date');

        $series = [];
        foreach (Order::STATUS_LABELS as $status => $label) {
            $data = [];
            foreach ($dateKeys as $dateKey) {
                $dayRow = $countsByDate->get($dateKey)?->firstWhere('status', $status);
                $data[] = (int) ($dayRow?->total ?? 0);
            }
            $series[] = [
                'name' => $label,
                'status' => $status,
                'data' => $data,
            ];
        }

        return [
            'labels' => $labels,
            'series' => $series,
        ];
    }
}
