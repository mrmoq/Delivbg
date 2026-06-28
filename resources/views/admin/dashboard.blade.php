@extends('layouts.master')
@section('title', 'لوحة التحكم')

@section('content')
@include('components.breadcrumb', ['title' => 'لوحة التحكم', 'li_1' => 'الرئيسية'])

<div class="row g-3">
    <div class="col-md-4">
        <div class="card dashboard-stat-card dashboard-stat-card--orders card-animate overflow-hidden h-100">
            <div class="card-body position-relative">
                <div class="dashboard-stat-icon-wrap">
                    <span class="dashboard-stat-icon bg-primary-subtle text-primary">
                        <i class="ri-shopping-bag-3-line"></i>
                    </span>
                </div>
                <p class="dashboard-stat-label text-muted mb-2">إجمالي الطلبات</p>
                <h2 class="dashboard-stat-value mb-3">{{ number_format($stats['orders_total']) }}</h2>
                <div class="dashboard-stat-footer">
                    <span class="badge dashboard-stat-badge bg-primary-subtle text-primary">
                        <i class="ri-file-list-3-line align-middle me-1"></i>
                        كل الطلبات المسجلة
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card dashboard-stat-card dashboard-stat-card--revenue card-animate overflow-hidden h-100">
            <div class="card-body position-relative">
                <div class="dashboard-stat-icon-wrap">
                    <span class="dashboard-stat-icon bg-success-subtle text-success">
                        <i class="ri-wallet-3-line"></i>
                    </span>
                </div>
                <p class="dashboard-stat-label text-muted mb-2">الإيرادات</p>
                <h2 class="dashboard-stat-value mb-1">
                    {{ number_format($stats['revenue'], 3) }}
                    <span class="dashboard-stat-currency">د.ك</span>
                </h2>
                <p class="text-muted fs-12 mb-3">من عمليات الدفع الناجحة</p>
                <div class="dashboard-stat-footer">
                    <span class="badge dashboard-stat-badge bg-success-subtle text-success">
                        <i class="ri-check-double-line align-middle me-1"></i>
                        مدفوعات مؤكدة
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        @php
            $paymentsTotalCard = $stats['payments_success'] + $stats['payments_failed'];
            $paymentSuccessRate = $paymentsTotalCard > 0 ? round(($stats['payments_success'] / $paymentsTotalCard) * 100) : 0;
        @endphp
        <div class="card dashboard-stat-card dashboard-stat-card--payments card-animate overflow-hidden h-100">
            <div class="card-body position-relative">
                <div class="dashboard-stat-icon-wrap">
                    <span class="dashboard-stat-icon bg-info-subtle text-info">
                        <i class="ri-bank-card-2-line"></i>
                    </span>
                </div>
                <p class="dashboard-stat-label text-muted mb-2">عمليات الدفع</p>
                <h2 class="dashboard-stat-value mb-3">{{ number_format($paymentsTotalCard) }}</h2>
                <div class="dashboard-stat-payment-bar mb-3">
                    <div class="progress progress-sm rounded-pill">
                        <div class="progress-bar bg-success" role="progressbar"
                             style="width: {{ $paymentSuccessRate }}%"
                             aria-valuenow="{{ $paymentSuccessRate }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="dashboard-stat-footer d-flex justify-content-between align-items-center gap-2">
                    <span class="dashboard-stat-mini text-success">
                        <i class="ri-checkbox-circle-line align-middle"></i>
                        {{ number_format($stats['payments_success']) }} ناجحة
                    </span>
                    <span class="dashboard-stat-mini text-danger">
                        <i class="ri-close-circle-line align-middle"></i>
                        {{ number_format($stats['payments_failed']) }} فاشلة
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-xl-9">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">توزيع حالات الطلب</h5>
                <p class="text-muted mb-0 fs-12">آخر 30 يوم — خط لكل حالة</p>
            </div>
            <div class="card-body">
                <div id="order-status-chart"></div>
                <div class="row g-2 mt-4 pt-3 border-top">
                    @foreach($stats['order_status_labels'] as $status => $label)
                        @php
                            $count = $stats['order_status_counts'][$status] ?? 0;
                            $pct = $stats['orders_total'] > 0 ? round(($count / $stats['orders_total']) * 100) : 0;
                        @endphp
                        <div class="col-6 col-md-4 col-xl-2">
                            <div class="border rounded p-2 text-center h-100">
                                <span class="status-dot {{ \App\Models\Order::statusColorClass($status) }} d-inline-block mb-2"></span>
                                <p class="text-muted mb-1 fs-12">{{ $label }}</p>
                                <h5 class="mb-0 fw-semibold">{{ $count }}</h5>
                                <span class="text-muted fs-11">{{ $pct }}%</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">حالات الدفع</h5>
            </div>
            <div class="card-body">
                @php
                    $paymentsTotal = $stats['payments_success'] + $stats['payments_failed'];
                    $successPct = $paymentsTotal > 0 ? round(($stats['payments_success'] / $paymentsTotal) * 100) : 0;
                    $failedPct = $paymentsTotal > 0 ? round(($stats['payments_failed'] / $paymentsTotal) * 100) : 0;
                @endphp
                <div id="payment-status-chart" class="dashboard-payment-chart mx-auto"></div>
                <div class="mt-3 pt-3 border-top">
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-bottom-dashed">
                        <span class="fs-13">
                            <span class="status-dot me-2" style="background-color:#0ab39c"></span>
                            دفع ناجح
                        </span>
                        <span class="fw-semibold fs-13">{{ $stats['payments_success'] }} <span class="text-muted fw-normal">({{ $successPct }}%)</span></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2">
                        <span class="fs-13">
                            <span class="status-dot me-2" style="background-color:#f06548"></span>
                            دفع فاشل
                        </span>
                        <span class="fw-semibold fs-13">{{ $stats['payments_failed'] }} <span class="text-muted fw-normal">({{ $failedPct }}%)</span></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center pt-2 mt-1 border-top">
                        <span class="text-muted fs-12">إجمالي العمليات</span>
                        <span class="fw-semibold fs-13">{{ $paymentsTotal }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@php
    $maxAreaCount = $topAreas->max('orders_count') ?: 1;
    $maxSchoolCount = $topSchools->max('orders_count') ?: 1;
@endphp

<div class="row mt-4">
    <div class="col-xl-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">المناطق الأكثر طلباً</h5>
            </div>
            <div class="card-body">
                @forelse($topAreas as $index => $area)
                    <div class="d-flex align-items-center gap-3 {{ !$loop->last ? 'mb-3 pb-3 border-bottom border-bottom-dashed' : '' }}">
                        <span class="dashboard-rank bg-primary-subtle text-primary">{{ $index + 1 }}</span>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="d-flex justify-content-between align-items-center mb-1 gap-2">
                                <span class="fw-medium text-truncate">{{ $area->name_ar }}</span>
                                <span class="badge bg-primary-subtle text-primary flex-shrink-0">{{ $area->orders_count }} طلب</span>
                            </div>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-primary" role="progressbar"
                                     style="width: {{ round(($area->orders_count / $maxAreaCount) * 100) }}%"
                                     aria-valuenow="{{ $area->orders_count }}" aria-valuemin="0" aria-valuemax="{{ $maxAreaCount }}">
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center mb-0 py-4">لا توجد بيانات</p>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">المدارس الأكثر طلبات</h5>
            </div>
            <div class="card-body">
                @forelse($topSchools as $index => $school)
                    <div class="d-flex align-items-center gap-3 {{ !$loop->last ? 'mb-3 pb-3 border-bottom border-bottom-dashed' : '' }}">
                        <span class="dashboard-rank bg-info-subtle text-info">{{ $index + 1 }}</span>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="d-flex justify-content-between align-items-center mb-1 gap-2">
                                <span class="fw-medium text-truncate">{{ $school->name_ar }}</span>
                                <span class="badge bg-info-subtle text-info flex-shrink-0">{{ $school->orders_count }} طلب</span>
                            </div>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-info" role="progressbar"
                                     style="width: {{ round(($school->orders_count / $maxSchoolCount) * 100) }}%"
                                     aria-valuenow="{{ $school->orders_count }}" aria-valuemin="0" aria-valuemax="{{ $maxSchoolCount }}">
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center mb-0 py-4">لا توجد بيانات</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script>
window.dashboardStats = @json($dashboardStats);
</script>
<script src="{{ asset('assets/js/pages/dashboard-stats.init.js') }}"></script>
@endsection
